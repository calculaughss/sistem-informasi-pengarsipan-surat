<?php
$pageTitle = 'Disposisi';
require ROOT_PATH . '/app/views/layouts/header.php';
require ROOT_PATH . '/app/views/layouts/sidebar.php';
$user = currentUser();
?>
<div class="main-content">
<?php require ROOT_PATH . '/app/views/layouts/navbar.php'; ?>
<main class="page-content">

    <div class="mb-4">
        <h5 class="fw-bold mb-0">Kotak Disposisi</h5>
        <p class="text-muted small mb-0">Daftar surat masuk yang didisposisikan kepada Anda</p>
    </div>

    <?php if ($ok = getFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i><?= sanitize($ok) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3" width="40">No</th>
                            <th>Nomor Surat</th>
                            <th>Perihal</th>
                            <th>Asal Surat</th>
                            <th>Dari</th>
                            <th>Tanggal</th>
                            <th>Catatan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($list)): ?>
                        <tr><td colspan="9" class="text-center text-muted py-5">
                            <i class="bi bi-bell-slash fs-2 d-block mb-2 opacity-50"></i>
                            Belum ada disposisi untuk Anda
                        </td></tr>
                    <?php else: ?>
                        <?php $no = $paging['offset'] + 1; ?>
                        <?php foreach ($list as $d): ?>
                        <tr class="<?= $d['status']==='belum_dibaca' ? 'table-warning' : '' ?>">
                            <td class="ps-3 text-muted"><?= $no++ ?></td>
                            <td class="small fw-semibold"><?= sanitize($d['nomor_surat']) ?></td>
                            <td>
                                <a href="<?= APP_URL ?>/surat-masuk/<?= $d['surat_masuk_id'] ?>"
                                   class="text-decoration-none fw-semibold text-dark">
                                    <?= sanitize($d['perihal']) ?>
                                </a>
                            </td>
                            <td class="small"><?= sanitize($d['asal_surat']) ?></td>
                            <td class="small"><?= sanitize($d['dari_nama']) ?></td>
                            <td class="small text-nowrap"><?= formatTanggal($d['tanggal']) ?></td>
                            <td class="small">
                                <?php if (!empty($d['catatan'])): ?>
                                    <span class="text-dark"><?= sanitize($d['catatan']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= statusBadge($d['status']) ?></td>
                            <td class="text-center text-nowrap">
                                <?php if ($d['status'] === 'belum_dibaca'): ?>
                                <a href="<?= APP_URL ?>/disposisi/baca/<?= $d['id'] ?>"
                                   class="btn btn-sm btn-primary" title="Tandai Dibaca">
                                    <i class="bi bi-check"></i> Baca
                                </a>
                                <?php elseif ($d['status'] === 'sudah_dibaca'): ?>
                                <a href="<?= APP_URL ?>/disposisi/selesai/<?= $d['id'] ?>"
                                   class="btn btn-sm btn-success" title="Tandai Selesai">
                                    <i class="bi bi-check-all"></i> Selesai
                                </a>
                                <?php else: ?>
                                <span class="text-muted small">Selesai</span>
                                <?php endif; ?>
                                <?php if ($user['role'] === 'admin'): ?>
                                <a href="<?= APP_URL ?>/disposisi/hapus/<?= $d['id'] ?>"
                                   class="btn btn-sm btn-outline-danger btn-hapus ms-1">
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

        <?php if ($paging['total_pages'] > 1): ?>
        <div class="card-footer d-flex justify-content-between align-items-center py-2 bg-transparent">
            <small class="text-muted">
                <?= $paging['offset']+1 ?>–<?= min($paging['offset']+ROWS_PER_PAGE, $paging['total']) ?>
                dari <?= $paging['total'] ?> disposisi
            </small>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <?php for ($p = 1; $p <= $paging['total_pages']; $p++): ?>
                    <li class="page-item <?= $p==$paging['current']?'active':'' ?>">
                        <a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a>
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
