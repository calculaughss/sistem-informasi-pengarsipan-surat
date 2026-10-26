    </main><!-- /main-content -->
</div><!-- /wrapper -->

<!-- ── Modal Konfirmasi Logout ──────────────────────────── -->
<div id="backdropLogout"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1040;"
     onclick="closeLogout()"></div>

<div id="modalLogout" class="modal" tabindex="-1"
     style="display:none;z-index:1050;">
    <div class="modal-dialog modal-dialog-centered" style="max-width:380px">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden">

            <!-- Header -->
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 rounded-circle"
                         style="width:46px;height:46px;flex-shrink:0">
                        <i class="bi bi-box-arrow-right text-danger fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Keluar dari Sistem</h6>
                        <p class="text-muted small mb-0">Sesi Anda akan diakhiri</p>
                    </div>
                </div>
                <button type="button" class="btn-close ms-auto" onclick="closeLogout()"></button>
            </div>

            <!-- Body -->
            <div class="modal-body px-4 py-3">
                <p class="text-muted small mb-0">
                    Apakah Anda yakin ingin keluar? Pastikan semua pekerjaan sudah tersimpan
                    sebelum melanjutkan.
                </p>
            </div>

            <!-- Footer -->
            <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm px-4"
                        onclick="closeLogout()">
                    Batal
                </button>
                <a href="<?= APP_URL ?>/auth/logout"
                   class="btn btn-danger btn-sm px-4">
                    <i class="bi bi-box-arrow-right me-1"></i> Ya, Keluar
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function closeLogout() {
    document.getElementById('modalLogout').style.display   = 'none';
    document.getElementById('modalLogout').classList.remove('show');
    document.getElementById('backdropLogout').style.display = 'none';
}
// Tutup dengan tombol Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLogout();
});
</script>

<!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<!-- Custom JS -->
<script src="<?= APP_URL ?>/public/js/app.js"></script>

<script>
// Sidebar toggle (mobile)
document.getElementById('sidebarToggle')?.addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('show');
});

// Auto-hide flash message
setTimeout(() => {
    document.querySelectorAll('.alert-dismissible').forEach(el => {
        new bootstrap.Alert(el).close();
    });
}, 4000);
</script>
</body>
</html>
