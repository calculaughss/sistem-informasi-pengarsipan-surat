<?php
if (defined('_DASHBOARD_CTRL_LOADED_')) return;
define('_DASHBOARD_CTRL_LOADED_', true);

class DashboardController {
    public function index(): void {
        requireLogin();

        $user = currentUser();
        $role = $user['role'];
        $disp = new Disposisi();

        // Data yang tersedia untuk semua role
        $data = [
            'disposisi_unread' => $disp->countUnread($user['id']),
            'disposisi_list'   => $disp->getByUser($user['id'], 0, 5),
        ];

        // Data ekstra hanya untuk admin & operator
        if (in_array($role, ['admin', 'operator'])) {
            $sm = new SuratMasuk();
            $sk = new SuratKeluar();

            $data['total_masuk']       = $sm->count();
            $data['total_keluar']      = $sk->count();
            $data['masuk_by_status']   = $sm->countByStatus();
            $data['keluar_by_status']  = $sk->countByStatus();
            $data['surat_masuk_baru']  = $sm->getAll(['status' => 'baru'], 0, 5);
            $data['surat_keluar_baru'] = $sk->getAll(['status' => 'draft'], 0, 5);
        }

        require ROOT_PATH . '/app/views/dashboard/index.php';
    }
}
