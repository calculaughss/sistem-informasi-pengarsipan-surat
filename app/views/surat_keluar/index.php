<?php
$pageTitle = 'Surat Keluar';
require ROOT_PATH . '/app/views/layouts/header.php';
require ROOT_PATH . '/app/views/layouts/sidebar.php';
$user = currentUser();
?>
<div class="main-content">
<?php require ROOT_PATH . '/app/views/layouts/navbar.php'; ?>
<main class="page-content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Surat Keluar</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Surat Keluar</li>
                </ol>
            </nav>
        </div>
        <?php if (in_array($user['role'], ['admin','operator'])): ?>
        <a href="<?= APP_URL ?>/surat-keluar/tambah" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Tambah Surat Keluar
        </a>
        <?php endif; ?>
    </div>

    <?php if ($ok = getFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i><?= sanitize($ok) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($err = getFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= sanitize($err) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filter -->
    <div class="card mb-3">
        <div class="card-body py-3">
            <form method="GET" action="<?= APP_URL ?>/surat-keluar" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Cari nomor, perihal, tujuan..." value="<?= sanitize($filter['search']) ?>">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="draft"      <?= $filter['status']==='draft'      ?'selected':'' ?>>Draft</option>
                        <option value="dikirim"    <?= $filter['status']==='dikirim'    ?'selected':'' ?>>Dikirim</option>
                        <option value="diarsipkan" <?= $filter['status']==='diarsipkan' ?'selected':'' ?>>Diarsipkan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="klasifikasi_id" class="form-select form-select-sm">
                        <option value="">Semua Klasifikasi</option>
                        <?php foreach ($klasifikasiList as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= $filter['klasifikasi_id']==$k['id']?'selected':'' ?>>
                            <?= sanitize($k['nama_klasifikasi']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="dari" class="form-control form-control-sm"
                           value="<?= sanitize($filter['dari']) ?>">
                </div>
                <div class="col-md-2">
                    <input type="date" name="sampai" class="form-control form-control-sm"
                           value="<?= sanitize($filter['sampai']) ?>">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3" width="40">No</th>
                            <th>Nomor Surat</th>
                            <th>Tanggal</th>
                            <th>Tujuan</th>
                            <th>Perihal</th>
                            <th>Klasifikasi</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($suratList)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-send fs-2 d-block mb-2 text-muted opacity-50"></i>
                            Tidak ada data surat keluar
                        </td></tr>
                    <?php else: ?>
                        <?php $no = $paging['offset'] + 1; ?>
                        <?php foreach ($suratList as $s): ?>
                        <tr>
                            <td class="ps-3 text-muted"><?= $no++ ?></td>
                            <td class="small fw-semibold"><?= sanitize($s['nomor_surat']) ?></td>
                            <td class="small text-nowrap"><?= formatTanggal($s['tanggal_surat']) ?></td>
                            <td class="small"><?= sanitize($s['tujuan']) ?></td>
                            <td>
                                <div class="fw-semibold text-truncate" style="max-width:180px">
                                    <?= sanitize($s['perihal']) ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($s['nama_klasifikasi']): ?>
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                        <?= sanitize($s['nama_klasifikasi']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= statusBadge($s['status']) ?></td>
                            <td class="text-center text-nowrap">
                                <a href="<?= APP_URL ?>/surat-keluar/<?= $s['id'] ?>"
                                   class="btn btn-sm btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if (in_array($user['role'], ['admin','operator'])): ?>
                                <a href="<?= APP_URL ?>/surat-keluar/edit/<?= $s['id'] ?>"
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (in_array($user['role'], ['admin','operator'])): ?>
                                <a href="<?= APP_URL ?>/surat-keluar/hapus/<?= $s['id'] ?>"
                                   class="btn btn-sm btn-outline-danger btn-hapus" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if ($paging['total_pages'] > 1): ?>
        <div class="card-footer d-flex justify-content-between align-items-center py-2 bg-transparent">
            <small class="text-muted">
                Menampilkan <?= $paging['offset']+1 ?>–<?= min($paging['offset']+ROWS_PER_PAGE, $paging['total']) ?>
                dari <?= $paging['total'] ?> data
            </small>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <?php for ($p = 1; $p <= $paging['total_pages']; $p++): ?>
                    <li class="page-item <?= $p==$paging['current']?'active':'' ?>">
                        <a class="page-link" href="?<?= http_build_query(array_merge($filter, ['page'=>$p])) ?>">
                            <?= $p ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>

</main>
</div>
<?php require ROOT_PATH . '/app/views/layouts/footer.php'; ?>