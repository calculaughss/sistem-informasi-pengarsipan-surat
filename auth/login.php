<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — <?= APP_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/public/css/style.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-card card shadow-lg p-4">
        <!-- Logo / judul -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3"
                 style="width:60px;height:60px;font-size:26px">
                <i class="bi bi-envelope-paper-fill"></i>
            </div>
            <h5 class="fw-bold mb-0"><?= APP_NAME ?></h5>
            <p class="text-muted small mt-1">Badan Eksekutif Mahasiswa</p>
        </div>

        <!-- Flash messages -->
        <?php if ($err = getFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= sanitize($err) ?>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Form login -->
        <form action="<?= APP_URL ?>/auth/proses" method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?= csrf() ?>">

            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input type="email" id="email" name="email"
                           class="form-control border-start-0"
                           placeholder="admin@bem.ac.id" required autofocus
                           value="<?= sanitize($_POST['email'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label" for="password">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" id="password" name="password"
                           class="form-control border-start-0"
                           placeholder="••••••••" required>
                    <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePwd">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                <i class="bi bi-box-arrow-in-right me-2"></i> Masuk
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('togglePwd').addEventListener('click', function () {
    const pwd  = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type  = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        pwd.type  = 'password';
        icon.className = 'bi bi-eye';
    }
});
</script>
</body>
</html>