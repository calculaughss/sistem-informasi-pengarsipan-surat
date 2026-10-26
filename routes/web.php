<?php
function route(): void {
    $uri    = trim($_SERVER['PATH_INFO'] ?? '/', '/');
    $parts  = explode('/', $uri);
    $seg0   = $parts[0] ?? '';
    $seg1   = $parts[1] ?? '';
    $id     = isset($parts[2]) ? (int)$parts[2] : null;

    switch ($seg0) {
        // ── Auth ────────────────────────────────────────
        case '':
        case 'auth':
            $ctrl = new AuthController();
            if ($seg1 === 'login')    { $ctrl->loginForm(); break; }
            if ($seg1 === 'proses')   { $ctrl->login();     break; }
            if ($seg1 === 'logout')   { $ctrl->logout();    break; }
            if ($seg1 === 'register') { $ctrl->registerForm(); break; }
            if ($seg1 === 'daftar')   { $ctrl->register();  break; }
            redirect(APP_URL . '/auth/login');
            break;

        // ── Dashboard ───────────────────────────────────
        case 'dashboard':
            (new DashboardController())->index();
            break;

        // ── Surat Masuk ─────────────────────────────────
        case 'surat-masuk':
            $ctrl = new SuratMasukController();
            if ($seg1 === '')         { $ctrl->index();          break; }
            if ($seg1 === 'tambah')   { $ctrl->tambah();         break; }
            if ($seg1 === 'simpan')   { $ctrl->simpan();         break; }
            if ($seg1 === 'edit'  && $id) { $ctrl->edit($id);   break; }
            if ($seg1 === 'update'&& $id) { $ctrl->update($id); break; }
            if ($seg1 === 'hapus' && $id) { $ctrl->hapus($id);  break; }
            if (is_numeric($seg1))        { $ctrl->detail((int)$seg1); break; }
            redirect(APP_URL . '/surat-masuk');
            break;

        // ── Surat Keluar ────────────────────────────────
        case 'surat-keluar':
            $ctrl = new SuratKeluarController();
            if ($seg1 === '')         { $ctrl->index();          break; }
            if ($seg1 === 'tambah')   { $ctrl->tambah();         break; }
            if ($seg1 === 'simpan')   { $ctrl->simpan();         break; }
            if ($seg1 === 'edit'  && $id) { $ctrl->edit($id);   break; }
            if ($seg1 === 'update'&& $id) { $ctrl->update($id); break; }
            if ($seg1 === 'hapus' && $id) { $ctrl->hapus($id);  break; }
            if (is_numeric($seg1))        { $ctrl->detail((int)$seg1); break; }
            redirect(APP_URL . '/surat-keluar');
            break;

        // ── Disposisi ───────────────────────────────────
        case 'disposisi':
            $ctrl = new DisposisiController();
            if ($seg1 === '')             { $ctrl->index();          break; }
            if ($seg1 === 'simpan')       { $ctrl->simpan();         break; }
            if ($seg1 === 'baca'  && $id) { $ctrl->baca($id);        break; }
            if ($seg1 === 'selesai'&& $id){ $ctrl->selesai($id);     break; }
            if ($seg1 === 'hapus' && $id) { $ctrl->hapus($id);       break; }
            redirect(APP_URL . '/disposisi');
            break;

        // ── Klasifikasi ─────────────────────────────────
        case 'klasifikasi':
            $ctrl = new KlasifikasiController();
            if ($seg1 === '')             { $ctrl->index();          break; }
            if ($seg1 === 'simpan')       { $ctrl->simpan();         break; }
            if ($seg1 === 'update'&& $id) { $ctrl->update($id);      break; }
            if ($seg1 === 'hapus' && $id) { $ctrl->hapus($id);       break; }
            redirect(APP_URL . '/klasifikasi');
            break;

        // ── Users ───────────────────────────────────────
        case 'users':
            $ctrl = new UserController();
            if ($seg1 === '')              { $ctrl->index();           break; }
            if ($seg1 === 'edit'  && $id)  { $ctrl->edit($id);         break; }
            if ($seg1 === 'update'&& $id)  { $ctrl->update($id);       break; }
            if ($seg1 === 'toggle'&& $id)  { $ctrl->toggle($id);       break; }
            if ($seg1 === 'hapus' && $id)  { $ctrl->hapus($id);        break; }
            redirect(APP_URL . '/users');
            break;

        // ── Serve file surat ────────────────────────────
        case 'file':
            // Wajib login dulu sebelum apapun
            requireLogin();

            // Susun path mentah (belum realpath) untuk validasi awal
            $relativeParts = array_slice($parts, 1);

            // Tolak path traversal sejak dini
            foreach ($relativeParts as $p) {
                if ($p === '..' || $p === '.' || $p === '') {
                    http_response_code(403); exit('Forbidden');
                }
            }

            $rawPath = STORAGE_PATH . DIRECTORY_SEPARATOR
                     . implode(DIRECTORY_SEPARATOR, $relativeParts);

            // Cek file ada sebelum realpath (realpath() return false jika tidak ada)
            if (!file_exists($rawPath)) {
                http_response_code(404); exit('File tidak ditemukan');
            }

            // Sekarang aman pakai realpath
            $realPath       = realpath($rawPath);
            $realStorageBase = realpath(STORAGE_PATH);

            // Pastikan masih di dalam folder storage (cegah path traversal)
            if (!$realPath || !$realStorageBase ||
                !str_starts_with($realPath, $realStorageBase . DIRECTORY_SEPARATOR)) {
                http_response_code(403); exit('Forbidden');
            }

            // Serve file
            $mime = mime_content_type($realPath) ?: 'application/octet-stream';
            header('Content-Type: ' . $mime);
            header('Content-Length: ' . filesize($realPath));
            header('Content-Disposition: inline; filename="' . basename($realPath) . '"');
            header('Cache-Control: private, max-age=3600');
            readfile($realPath);
            exit;

        default:
            http_response_code(404);
            echo '<h1>404 – Halaman tidak ditemukan</h1>';
    }
}
