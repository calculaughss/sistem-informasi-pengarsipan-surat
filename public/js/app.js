$(function () {
    // DataTable default
    if ($('.datatable').length) {
        $('.datatable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json'
            },
            pageLength: 10,
            order: [],
            responsive: true
        });
    }

    // Konfirmasi hapus
    $(document).on('click', '.btn-hapus', function (e) {
        if (!confirm('Yakin ingin menghapus data ini?')) {
            e.preventDefault();
        }
    });

    // Preview file sebelum upload
    $('#file_surat').on('change', function () {
        const file = this.files[0];
        const preview = $('#file-preview');
        preview.html('');
        if (!file) return;

        const allowed = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!allowed.includes(file.type)) {
            preview.html('<span class="text-danger small">Tipe file tidak diizinkan.</span>');
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            preview.html('<span class="text-danger small">Ukuran file melebihi 5 MB.</span>');
            return;
        }
        preview.html(`<span class="file-badge"><i class="bi bi-paperclip me-1"></i>${file.name} (${(file.size/1024).toFixed(1)} KB)</span>`);
    });

    // Set tanggal default hari ini
    $('input[type="date"][data-today]').each(function () {
        if (!$(this).val()) {
            const today = new Date().toISOString().split('T')[0];
            $(this).val(today);
        }
    });
});