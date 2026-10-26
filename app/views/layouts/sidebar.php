<?php
$currentUser  = currentUser();
$role         = $currentUser['role'];
$dispUnread   = (new Disposisi())->countUnread($currentUser['id']);
$currentUri   = trim($_SERVER['PATH_INFO'] ?? '/', '/');

function isActive(string $prefix, string $uri): string {
    return str_starts_with($uri, $prefix) ? 'active' : '';
}

// ── Hak akses per menu ─────────────────────────────────────
// Dashboard  : semua role
// Surat Masuk: admin, operator
// Surat Keluar: admin, operator
// Disposisi  : semua role
// Klasifikasi: admin saja
// Pengguna   : admin saja
$canSuratMasuk  = in_array($role, ['admin', 'operator']);
$canSuratKeluar = in_array($role, ['admin', 'operator']);
$canDisposisi   = true; // semua role
$canKlasifikasi = $role === 'admin';
$canPengguna    = $role === 'admin';
?>
<!-- Sidebar -->
<nav id="sidebar" class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-primary text-white">
    <!-- Brand -->
    <a href="<?= APP_URL ?>/dashboard" class="d-flex align-items-center mb-4 text-white text-decoration-none">
        <img src="<?= APP_URL ?>/assets/img/logo.png" 
            alt="Logo BEM"
            style="width:45px;height:45px;object-fit:cover;border-radius:50%;margin-right:10px;">
            
        <span class="fw-semibold" style="font-size:14px; line-height:1.2;">
            <?= APP_NAME ?>
        </span>
    </a>

    <hr class="border-light my-0 mb-3">

    <!-- Nav items -->
    <ul class="nav nav-pills flex-column mb-auto gap-1">

        <!-- Dashboard — semua role -->
        <li class="nav-item">
            <a href="<?= APP_URL ?>/dashboard"
               class="nav-link text-white <?= isActive('dashboard', $currentUri) ?>">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        <!-- Surat Masuk — admin & operator -->
        <?php if ($canSuratMasuk): ?>
        <li class="nav-item">
            <a href="<?= APP_URL ?>/surat-masuk"
               class="nav-link text-white <?= isActive('surat-masuk', $currentUri) ?>">
                <i class="bi bi-inbox-fill me-2"></i> Surat Masuk
            </a>
        </li>
        <?php endif; ?>

        <!-- Surat Keluar — admin & operator -->
        <?php if ($canSuratKeluar): ?>
        <li class="nav-item">
            <a href="<?= APP_URL ?>/surat-keluar"
               class="nav-link text-white <?= isActive('surat-keluar', $currentUri) ?>">
                <i class="bi bi-send-fill me-2"></i> Surat Keluar
            </a>
        </li>
        <?php endif; ?>

        <!-- Disposisi — semua role -->
        <li class="nav-item">
            <a href="<?= APP_URL ?>/disposisi"
               class="nav-link text-white <?= isActive('disposisi', $currentUri) ?>">
                <i class="bi bi-arrow-right-square-fill me-2"></i> Disposisi
                <?php if ($dispUnread > 0): ?>
                    <span class="badge bg-danger ms-1"><?= $dispUnread ?></span>
                <?php endif; ?>
            </a>
        </li>

        <!-- Klasifikasi & Pengguna — admin saja -->
        <?php if ($canKlasifikasi || $canPengguna): ?>
        <hr class="border-light my-1">
        <?php endif; ?>

        <?php if ($canKlasifikasi): ?>
        <li class="nav-item">
            <a href="<?= APP_URL ?>/klasifikasi"
               class="nav-link text-white <?= isActive('klasifikasi', $currentUri) ?>">
                <i class="bi bi-tags-fill me-2"></i> Klasifikasi
            </a>
        </li>
        <?php endif; ?>

        <?php if ($canPengguna): ?>
        <li class="nav-item">
            <a href="<?= APP_URL ?>/users"
               class="nav-link text-white <?= isActive('users', $currentUri) ?>">
                <i class="bi bi-people-fill me-2"></i> Pengguna
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= APP_URL ?>/auth/register"
               class="nav-link text-white <?= isActive('auth/register', $currentUri) ?>">
                <i class="bi bi-person-plus-fill me-2"></i> Tambah User
            </a>
        </li>
        <?php endif; ?>

    </ul>

    <hr class="border-light">

    <!-- User info & logout -->
    <div class="d-flex align-items-center gap-2">
        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center"
             style="width:36px;height:36px;font-weight:700;flex-shrink:0">
            <?= strtoupper(substr($currentUser['nama'], 0, 1)) ?>
        </div>
        <div class="overflow-hidden">
            <div class="fw-semibold text-truncate small"><?= sanitize($currentUser['nama']) ?></div>
            <div class="text-white-50 text-truncate" style="font-size:11px"><?= ucfirst($role) ?></div>
        </div>
        <a href="#" onclick="document.getElementById('modalLogout').classList.add('show');
                document.getElementById('modalLogout').style.display='block';
                document.getElementById('backdropLogout').style.display='block'; return false;"
           class="ms-auto text-white-50 text-decoration-none" title="Logout">
            <i class="bi bi-box-arrow-right fs-5"></i>
        </a>
    </div>
</nav>
