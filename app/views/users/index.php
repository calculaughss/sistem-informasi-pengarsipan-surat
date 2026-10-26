<?php
$pageTitle = 'Manajemen Pengguna';
require ROOT_PATH . '/app/views/layouts/header.php';
require ROOT_PATH . '/app/views/layouts/sidebar.php';
$me = currentUser();
?>
<div class="main-content">
<?php require ROOT_PATH . '/app/views/layouts/navbar.php'; ?>
<main class="page-content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Manajemen Pengguna</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pengguna</li>
                </ol>
            </nav>
        </div>
        <a href="<?= APP_URL ?>/auth/register" class="btn btn-primary">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna
        </a>
    </div>

    <?php if ($ok = getFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i><?= $ok ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($err = getFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= sanitize($err) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3" width="50">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Terdaftar</th>
                            <th class="text-center" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($users)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-5">Belum ada data pengguna</td></tr>
                    <?php else: ?>
                        <?php $no = $paging['offset'] + 1; ?>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="ps-3 text-muted"><?= $no++ ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center
                                                justify-content-center fw-bold"
                                         style="width:32px;height:32px;font-size:13px;flex-shrink:0">
                                        <?= strtoupper(substr($u['nama'], 0, 1)) ?>
                                    </div>
                                    <span class="fw-semibold"><?= sanitize($u['nama']) ?></span>
                                    <?php if ($u['id'] == $me['id']): ?>
                                        <span class="badge bg-secondary" style="font-size:10px">Saya</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="small text-muted"><?= sanitize($u['email']) ?></td>
                            <td>
                                <?php
                                $roleColor = match($u['role']) {
                                    'admin'    => 'danger',
                                    'operator' => 'warning',
                                    default    => 'secondary',
                                };
                                ?>
                                <span class="badge bg-<?= $roleColor ?> bg-opacity-25 text-<?= $roleColor ?> border border-<?= $roleColor ?>">
                                    <?= ucfirst($u['role']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($u['is_active']): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted"><?= formatTanggal($u['created_at'], 'd/m/Y') ?></td>
                            <td class="text-center text-nowrap">
                                <button class="btn btn-sm btn-outline-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit"
                                        data-id="<?= $u['id'] ?>"
                                        data-nama="<?= sanitize($u['nama']) ?>"
                                        data-email="<?= sanitize($u['email']) ?>"
                                        data-role="<?= $u['role'] ?>"
                                        data-active="<?= $u['is_active'] ?>"
                                        title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <?php if ($u['id'] != $me['id']): ?>
                                    <?php if ($u['is_active']): ?>
                                    <a href="<?= APP_URL ?>/users/toggle/<?= $u['id'] ?>"
                                       class="btn btn-sm btn-outline-danger btn-toggle-status"
                                       data-nama="<?= sanitize($u['nama']) ?>"
                                       data-status="aktif"
                                       title="Nonaktifkan">
                                        <i class="bi bi-person-dash"></i>
                                    </a>
                                    <?php else: ?>
                                    <a href="<?= APP_URL ?>/users/toggle/<?= $u['id'] ?>"
                                       class="btn btn-sm btn-outline-success btn-toggle-status"
                                       data-nama="<?= sanitize($u['nama']) ?>"
                                       data-status="nonaktif"
                                       title="Aktifkan">
                                        <i class="bi bi-person-check"></i>
                                    </a>
                                    <?php endif; ?>
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
                <?= $paging['offset']+1 ?>–<?= min($paging['offset']+ROWS_PER_PAGE,$paging['total']) ?>
                dari <?= $paging['total'] ?> pengguna
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

<!-- Modal Edit User -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Edit Pengguna</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEdit" action="" method="POST">
                <input type="hidden" name="csrf_token" value="<?= csrf() ?>">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" id="editNama" name="nama" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" id="editEmail" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select id="editRole" name="role" class="form-select">
                                <option value="anggota">Anggota</option>
                                <option value="operator">Operator</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select id="editActive" name="is_active" class="form-select">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control"
                                   placeholder="Kosongkan jika tidak diganti">
                            <div class="form-text">Isi hanya jika ingin mengganti password</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="bi bi-save me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Isi form modal edit
document.getElementById('modalEdit').addEventListener('show.bs.modal', function (e) {
    const btn = e.relatedTarget;
    document.getElementById('formEdit').action  = '<?= APP_URL ?>/users/update/' + btn.dataset.id;
    document.getElementById('editNama').value   = btn.dataset.nama;
    document.getElementById('editEmail').value  = btn.dataset.email;
    document.getElementById('editRole').value   = btn.dataset.role;
    document.getElementById('editActive').value = btn.dataset.active;
});

// Konfirmasi toggle aktif/nonaktif
document.querySelectorAll('.btn-toggle-status').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
        const nama   = btn.dataset.nama;
        const status = btn.dataset.status; // 'aktif' atau 'nonaktif'
        let pesan;
        if (status === 'aktif') {
            pesan = 'Nonaktifkan pengguna "' + nama + '"?
Pengguna tidak bisa login setelah dinonaktifkan.';
        } else {
            pesan = 'Aktifkan kembali pengguna "' + nama + '"?
Pengguna bisa login setelah diaktifkan.';
        }
        if (!confirm(pesan)) {
            e.preventDefault();
        }
    });
});
</script>

<?php require ROOT_PATH . '/app/views/layouts/footer.php'; ?>
