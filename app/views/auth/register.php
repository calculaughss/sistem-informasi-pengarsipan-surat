<?php
$pageTitle = 'Tambah Pengguna';
require ROOT_PATH . '/app/views/layouts/header.php';
require ROOT_PATH . '/app/views/layouts/sidebar.php';
?>
<div class="main-content">
<?php require ROOT_PATH . '/app/views/layouts/navbar.php'; ?>
<main class="page-content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Tambah Pengguna</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/users">Pengguna</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </nav>
        </div>
    </div>

    <?php if ($err = getFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= sanitize($err) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body p-4">
            <form action="<?= APP_URL ?>/auth/daftar" method="POST" novalidate>
                <input type="hidden" name="csrf_token" value="<?= csrf() ?>">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" id="nama" name="nama" class="form-control"
                               placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" class="form-control"
                               placeholder="contoh@bem.ac.id" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="Minimal 6 karakter" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="role">Role <span class="text-danger">*</span></label>
                        <select id="role" name="role" class="form-select">
                            <option value="anggota">Anggota</option>
                            <option value="operator">Operator</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Simpan
                    </button>
                    <a href="<?= APP_URL ?>/users" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

</main>
</div>
<?php require ROOT_PATH . '/app/views/layouts/footer.php'; ?>