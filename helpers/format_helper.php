<?php
if (defined('_FORMAT_HELPER_LOADED_')) return;
define('_FORMAT_HELPER_LOADED_', true);

function formatTanggal(string $date, string $format = 'd F Y'): string {
    $bulan = [
        '01' => 'Januari',  '02' => 'Februari', '03' => 'Maret',
        '04' => 'April',    '05' => 'Mei',       '06' => 'Juni',
        '07' => 'Juli',     '08' => 'Agustus',   '09' => 'September',
        '10' => 'Oktober',  '11' => 'November',  '12' => 'Desember',
    ];
    if ($format === 'd F Y') {
        [$y, $m, $d] = explode('-', $date);
        return (int)$d . ' ' . ($bulan[$m] ?? $m) . ' ' . $y;
    }
    return date($format, strtotime($date));
}

function sanitize(mixed $input): string {
    return htmlspecialchars(trim((string)$input), ENT_QUOTES, 'UTF-8');
}

function statusBadge(string $status): string {
    $map = [
        'baru'         => ['bg-primary',  'Baru'],
        'diproses'     => ['bg-warning text-dark', 'Diproses'],
        'selesai'      => ['bg-success',  'Selesai'],
        'diarsipkan'   => ['bg-secondary','Diarsipkan'],
        'draft'        => ['bg-secondary','Draft'],
        'dikirim'      => ['bg-success',  'Dikirim'],
        'belum_dibaca' => ['bg-danger',   'Belum Dibaca'],
        'sudah_dibaca' => ['bg-info',     'Sudah Dibaca'],
    ];
    [$cls, $label] = $map[$status] ?? ['bg-light text-dark', $status];
    return '<span class="badge ' . $cls . '">' . $label . '</span>';
}

function paginate(int $total, int $page, int $perPage = ROWS_PER_PAGE): array {
    $totalPages = (int)ceil($total / $perPage);
    $page       = max(1, min($page, $totalPages));
    $offset     = ($page - 1) * $perPage;
    return [
        'total'       => $total,
        'per_page'    => $perPage,
        'current'     => $page,
        'total_pages' => $totalPages,
        'offset'      => $offset,
    ];
}

function nomorAgenda(int $id): string {
    return 'AGD-' . date('Y') . '-' . str_pad($id, 4, '0', STR_PAD_LEFT);
}