<?php
$pageTitle = 'Dashboard';
$role      = currentUser()['role'];
require ROOT_PATH . '/app/views/layouts/header.php';
require ROOT_PATH . '/app/views/layouts/sidebar.php';
?>
<div class="main-content">
<?php require ROOT_PATH . '/app/views/layouts/navbar.php'; ?>
<main class="page-content">

    <!-- Greeting -->
    <div class="mb-4">
        <h5 class="fw-bold mb-0">Selamat datang, <?= sanitize(currentUser()['nama']) ?> 👋</h5>
        <p class="text-muted small mb-0"><?= date('l, d F Y') ?> &nbsp;·&nbsp;
            <span class="badge bg-primary bg-opacity-10 text-primary"><?= ucfirst($role) ?></span>
        </p>
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

    <?php if (in_array($role, ['admin', 'operator'])): ?>
    <!-- ── Stat cards — admin & operator ───────────────────── -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-inbox-fill"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-primary"><?= $data['total_masuk'] ?? 0 ?></div>
                        <div class="text-muted small">Total Surat Masuk</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="icon-box bg-success bg-opacity-10 text-success">
                        <i class="bi bi-send-fill"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-success"><?= $data['total_keluar'] ?? 0 ?></div>
                        <div class="text-muted small">Total Surat Keluar</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-warning"><?= $data['masuk_by_status']['baru'] ?? 0 ?></div>
                        <div class="text-muted small">Surat Masuk Baru</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="icon-box bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-danger"><?= $data['disposisi_unread'] ?></div>
                        <div class="text-muted small">Disposisi Belum Dibaca</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel ringkasan admin & operator -->
    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <span><i class="bi bi-inbox me-2 text-primary"></i>Surat Masuk Terbaru</span>
                    <a href="<?= APP_URL ?>/surat-masuk" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr>
                                <th class="ps-3">Perihal</th><th>Dari</th><th>Status</th>
                            </tr></thead>
                            <tbody>
                            <?php if (empty($data['surat_masuk_baru'])): ?>
                                <tr><td colspan="3" class="text-center text-muted py-4">Belum ada surat masuk baru</td></tr>
                            <?php else: ?>
                                <?php foreach ($data['surat_masuk_baru'] as $sm): ?>
                                <tr>
                                    <td class="ps-3">
                                        <a href="<?= APP_URL ?>/surat-masuk/<?= $sm['id'] ?>"
                                           class="text-decoration-none fw-semibold text-dark text-truncate d-block"
                                           style="max-width:160px">
                                            <?= sanitize($sm['perihal']) ?>
                                        </a>
                                        <small class="text-muted"><?= formatTanggal($sm['tanggal_terima']) ?></small>
                                    </td>
                                    <td class="small"><?= sanitize($sm['asal_surat']) ?></td>
                                    <td><?= statusBadge($sm['status']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <span><i class="bi bi-send me-2 text-success"></i>Surat Keluar Terbaru</span>
                    <a href="<?= APP_URL ?>/surat-keluar" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr>
                                <th class="ps-3">Perihal</th><th>Tujuan</th><th>Status</th>
                            </tr></thead>
                            <tbody>
                            <?php if (empty($data['surat_keluar_baru'])): ?>
                                <tr><td colspan="3" class="text-center text-muted py-4">Belum ada surat keluar</td></tr>
                            <?php else: ?>
                                <?php foreach ($data['surat_keluar_baru'] as $sk): ?>
                                <tr>
                                    <td class="ps-3">
                                        <a href="<?= APP_URL ?>/surat-keluar/<?= $sk['id'] ?>"
                                           class="text-decoration-none fw-semibold text-dark text-truncate d-block"
                                           style="max-width:160px">
                                            <?= sanitize($sk['perihal']) ?>
                                        </a>
                                        <small class="text-muted"><?= formatTanggal($sk['tanggal_surat']) ?></small>
                                    </td>
                                    <td class="small"><?= sanitize($sk['tujuan']) ?></td>
                                    <td><?= statusBadge($sk['status']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php else: ?>
    <!-- ── Dashboard khusus Anggota ────────────────────────── -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="icon-box bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-danger"><?= $data['disposisi_unread'] ?></div>
                        <div class="text-muted small">Disposisi Belum Dibaca</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-arrow-right-square-fill"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-primary"><?= count($data['disposisi_list']) ?></div>
                        <div class="text-muted small">Disposisi Masuk</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Disposisi terbaru — tampil untuk semua role -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <span><i class="bi bi-arrow-right-square me-2 text-info"></i>Disposisi Terbaru untuk Saya</span>
            <a href="<?= APP_URL ?>/disposisi" class="btn btn-sm btn-outline-info">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr>
                        <th class="ps-3">Perihal Surat</th>
                        <th>Dari</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr></thead>
                    <tbody>
                    <?php if (empty($data['disposisi_list'])): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-bell-slash fs-2 d-block mb-2 opacity-50"></i>
                            Belum ada disposisi untuk Anda
                        </td></tr>
                    <?php else: ?>
                        <?php foreach ($data['disposisi_list'] as $d): ?>
                        <tr class="<?= $d['status']==='belum_dibaca' ? 'table-warning' : '' ?>">
                            <td class="ps-3 fw-semibold"><?= sanitize($d['perihal']) ?></td>
                            <td class="small"><?= sanitize($d['dari_nama']) ?></td>
                            <td class="small text-nowrap"><?= formatTanggal($d['tanggal']) ?></td>
                            <td><?= statusBadge($d['status']) ?></td>
                            <td class="text-center">
                                <?php if ($d['status'] === 'belum_dibaca'): ?>
                                <a href="<?= APP_URL ?>/disposisi/baca/<?= $d['id'] ?>"
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-check me-1"></i>Baca
                                </a>
                                <?php elseif ($d['status'] === 'sudah_dibaca'): ?>
                                <a href="<?= APP_URL ?>/disposisi/selesai/<?= $d['id'] ?>"
                                   class="btn btn-sm btn-success">
                                    <i class="bi bi-check-all me-1"></i>Selesai
                                </a>
                                <?php else: ?>
                                <span class="text-muted small">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>
</div>
<?php require ROOT_PATH . '/app/views/layouts/footer.php'; ?>
