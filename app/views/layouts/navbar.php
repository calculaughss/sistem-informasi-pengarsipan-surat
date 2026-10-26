<nav class="navbar navbar-expand-lg bg-white border-bottom px-4 py-2 sticky-top shadow-sm">
    <!-- Toggle sidebar (mobile) -->
    <button class="btn btn-sm btn-outline-secondary me-3 d-lg-none" id="sidebarToggle">
        <i class="bi bi-list fs-5"></i>
    </button>

    <!-- Page title -->
    <span class="navbar-brand mb-0 h6 fw-semibold text-muted">
        <?= sanitize($pageTitle ?? 'Dashboard') ?>
    </span>

    <div class="ms-auto d-flex align-items-center gap-3">
        <!-- Notifikasi disposisi -->
        <?php
        $u = currentUser();
        $unread = (new Disposisi())->countUnread($u['id']);
        ?>
        <a href="<?= APP_URL ?>/disposisi" class="position-relative text-secondary text-decoration-none">
            <i class="bi bi-bell-fill fs-5"></i>
            <?php if ($unread > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                  style="font-size:9px"><?= $unread ?></span>
            <?php endif; ?>
        </a>

        <!-- User dropdown -->
        <div class="dropdown">
            <a class="d-flex align-items-center gap-2 text-decoration-none text-dark dropdown-toggle"
               href="#" data-bs-toggle="dropdown">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                     style="width:32px;height:32px;font-weight:700;font-size:13px">
                    <?= strtoupper(substr($u['nama'], 0, 1)) ?>
                </div>
                <span class="d-none d-md-inline small fw-semibold"><?= sanitize($u['nama']) ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li><span class="dropdown-item-text small text-muted"><?= ucfirst($u['role']) ?></span></li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <a class="dropdown-item text-danger" href="#"
                       onclick="document.getElementById('modalLogout').classList.add('show');
                                document.getElementById('modalLogout').style.display='block';
                                document.getElementById('backdropLogout').style.display='block'; return false;">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
