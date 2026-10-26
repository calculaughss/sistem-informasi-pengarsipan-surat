<?php
declare(strict_types=1);

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';

// Helpers
require_once __DIR__ . '/helpers/auth_helper.php';
require_once __DIR__ . '/helpers/file_helper.php';
require_once __DIR__ . '/helpers/format_helper.php';

// Models
require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/models/Klasifikasi.php';
require_once __DIR__ . '/app/models/SuratMasuk.php';
require_once __DIR__ . '/app/models/SuratKeluar.php';
require_once __DIR__ . '/app/models/Disposisi.php';
require_once __DIR__ . '/app/models/LogAktivitas.php';

// Controllers
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/DashboardController.php';
require_once __DIR__ . '/app/controllers/SuratMasukController.php';
require_once __DIR__ . '/app/controllers/SuratKeluarController.php';
require_once __DIR__ . '/app/controllers/DisposisiController.php';
require_once __DIR__ . '/app/controllers/KlasifikasiController.php';
require_once __DIR__ . '/app/controllers/UserController.php';

// Routes
require_once __DIR__ . '/routes/web.php';

startSession();
route();