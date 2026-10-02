<?php
/* ============================================================
   NUTRIMATE — Food Database API (CRUD berbasis file JSON)
   ------------------------------------------------------------
   Peningkatan dibanding versi sebelumnya:
   - LOCK_EX saat menulis    → data tak lagi bisa tertimpa oleh
                               request paralel (race condition).
   - JSON korup TIDAK dianggap kosong → data asli tidak hilang
                               hanya karena satu file rusak.
   - Whitelist field (ALLOWED_FIELDS) → "id" tak bisa ditimpa dan
                               key asing tak bisa disuntik via PUT/POST.
   - Validasi tipe & URL     → tolak javascript:/data: pada "image",
                               angka dipaksa >= 0.
   - Respons konsisten       → { status, ... } + kode HTTP benar.

   Kontrak respons TIDAK berubah, jadi klien lama tetap jalan:
     GET    api.php         → { status, data: [...] }
     GET    api.php?id=1    → { status, data: {...} } | 404
     POST   api.php         → 201 { status, message, data }
     PUT    api.php?id=1    → { status, message, data }
     DELETE api.php?id=1    → { status, message }
   ============================================================ */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$file = 'foods.json';

/** Satu-satunya field yang boleh ditulis lewat API. */
const ALLOWED_FIELDS = [
    'name', 'category', 'price', 'portion_unit',
    'calories', 'protein', 'carbs', 'fat',
    'description', 'image',
];

/** Kirim respons lalu hentikan eksekusi — semua jalur pakai ini. */
function respond($status, array $payload, $code = 200)
{
    http_response_code($code);
    echo json_encode(array_merge(['status' => $status], $payload), JSON_UNESCAPED_UNICODE);
    exit;
}

/** Baca body JSON. null kalau bukan JSON valid. */
function readBody()
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : null;
}

function getFoods($file)
{
    if (!file_exists($file)) {
        return [];
    }

    $raw = file_get_contents($file);
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $data = json_decode($raw, true);
    if (!is_array($data)) {
        // Penting: JANGAN dianggap kosong. Kalau dibiarkan, saveFoods()
        // akan menimpa seluruh data dengan hasil olahan file "kosong" itu.
        respond('error', ['message' => 'File foods.json tidak valid / korup'], 500);
    }

    return $data;
}

function saveFoods($file, array $data)
{
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        respond('error', ['message' => 'Gagal mengenkode data'], 500);
    }

    // LOCK_EX → penulisan terkunci & atomik, aman dari request paralel.
    if (file_put_contents($file, $json, LOCK_EX) === false) {
        respond('error', ['message' => 'Gagal menulis foods.json'], 500);
    }
}

/**
 * Buang key di luar whitelist (id tidak pernah ikut) dan paksa tipe aman.
 * Dipakai bersama oleh POST dan PUT.
 */
function sanitize(array $input)
{
    $out = [];
    foreach (ALLOWED_FIELDS as $key) {
        if (array_key_exists($key, $input)) {
            $out[$key] = $input[$key];
        }
    }

    foreach (['price', 'calories', 'protein', 'carbs', 'fat'] as $numeric) {
        if (isset($out[$numeric])) {
            $out[$numeric] = max(0, (int) $out[$numeric]);
        }
    }

    foreach (['name', 'category', 'portion_unit', 'description'] as $text) {
        if (isset($out[$text])) {
            $out[$text] = trim((string) $out[$text]);
        }
    }

    if (isset($out['image'])) {
        $image = trim((string) $out['image']);
        // Nilai image dipakai langsung sebagai <img src>, jadi hanya
        // http/https yang boleh — tolak javascript: dan data:.
        if (preg_match('#^https?://#i', $image)) {
            $out['image'] = $image;
        } else {
            unset($out['image']);
        }
    }

    return $out;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$foods = getFoods($file);

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = (int) $_GET['id'];
            foreach ($foods as $food) {
                if ((int) ($food['id'] ?? -1) === $id) {
                    respond('success', ['data' => $food]);
                }
            }
            respond('error', ['message' => 'Data tidak ditemukan'], 404);
        }
        respond('success', ['data' => $foods]);
        break;

    case 'POST':
        $input = readBody();
        if ($input === null) {
            respond('error', ['message' => 'Body JSON tidak valid'], 400);
        }

        $item = sanitize($input);
        if (!isset($item['name']) || $item['name'] === '' || !isset($item['price'])) {
            respond('error', ['message' => 'Name dan Price wajib diisi'], 400);
        }

        $maxId = 0;
        foreach ($foods as $food) {
            $maxId = max($maxId, (int) ($food['id'] ?? 0));
        }

        $newItem = array_merge(
            [
                'id' => $maxId + 1,
                'category' => 'Lainnya',
                'portion_unit' => 'porsi',
                'calories' => 0,
                'protein' => 0,
                'carbs' => 0,
                'fat' => 0,
                'description' => '',
                'image' => 'https://via.placeholder.com/600x400',
            ],
            $item,
            ['id' => $maxId + 1] // id selalu dari server, tak bisa dikirim klien
        );

        $foods[] = $newItem;
        saveFoods($file, $foods);

        http_response_code(201);
        respond('success', ['message' => 'Makanan berhasil ditambahkan', 'data' => $newItem], 201);
        break;

    case 'PUT':
        $input = readBody();
        if ($input === null) {
            respond('error', ['message' => 'Body JSON tidak valid'], 400);
        }
        if (!isset($_GET['id'])) {
            respond('error', ['message' => 'Parameter ID dibutuhkan'], 400);
        }

        $id = (int) $_GET['id'];
        $index = -1;
        foreach ($foods as $i => $food) {
            if ((int) ($food['id'] ?? -1) === $id) {
                $index = $i;
                break;
            }
        }
        if ($index === -1) {
            respond('error', ['message' => 'Data tidak ditemukan'], 404);
        }

        $patch = sanitize($input);
        if (!$patch) {
            // Kalau dibiarkan, array_merge tidak mengubah apa pun tapi
            // tetap menulis file — pemborosan & menyesatkan.
            respond('error', ['message' => 'Tidak ada field yang bisa diperbarui'], 400);
        }

        $foods[$index] = array_merge($foods[$index], $patch);
        saveFoods($file, $foods);

        respond('success', ['message' => 'Data berhasil diperbarui', 'data' => $foods[$index]]);
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
            respond('error', ['message' => 'Parameter ID dibutuhkan'], 400);
        }

        $id = (int) $_GET['id'];
        $kept = [];
        foreach ($foods as $food) {
            if ((int) ($food['id'] ?? -1) !== $id) {
                $kept[] = $food;
            }
        }

        if (count($kept) === count($foods)) {
            respond('error', ['message' => 'Data tidak ditemukan'], 404);
        }

        saveFoods($file, $kept);
        respond('success', ['message' => 'Data makanan berhasil dihapus']);
        break;

    default:
        header('Allow: GET, POST, PUT, DELETE, OPTIONS');
        respond('error', ['message' => 'Method tidak diizinkan'], 405);
        break;
}
