<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= html_escape($title) ?></title>
    <link href="<?= base_url('assets/adminsb/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body id="page-top">
<div id="wrapper">
    <?php $this->load->view('admin/01_sidebar'); ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php $this->load->view('admin/02_topbar'); ?>
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div><h1 class="h3 text-gray-800 mb-0">Pembuatan Token</h1><small class="text-muted">Token akses untuk aplikasi desktop HCIS</small></div>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#tokenModal"><i class="fas fa-plus"></i> Generate Token</button>
                </div>
                <div class="card shadow mb-4"><div class="card-body"><div class="table-responsive">
                    <table class="table table-bordered">
                        <thead><tr><th>Nama</th><th>Prefix</th><th>Kedaluwarsa</th><th>Terakhir Dipakai</th><th>Status</th><th>Aksi</th></tr></thead>
                        <tbody>
                        <?php if (!$tokens): ?><tr><td colspan="6" class="text-center text-muted">Belum ada token.</td></tr><?php endif; ?>
                        <?php foreach ($tokens as $token): ?>
                            <?php $active = !$token['revoked_at'] && (!$token['expires_at'] || $token['expires_at'] >= date('Y-m-d H:i:s')); ?>
                            <tr>
                                <td><?= html_escape($token['name']) ?></td>
                                <td><code><?= html_escape($token['token_prefix']) ?>...</code></td>
                                <td><?= $token['expires_at'] ? html_escape($token['expires_at']) : 'Tidak pernah' ?></td>
                                <td><?= $token['last_used_at'] ? html_escape($token['last_used_at']) : '-' ?></td>
                                <td><span class="badge badge-<?= $active ? 'success' : 'secondary' ?>"><?= $active ? 'Aktif' : 'Tidak aktif' ?></span></td>
                                <td><?php if ($active): ?><button class="btn btn-sm btn-outline-danger btn-revoke" data-id="<?= (int) $token['id'] ?>">Cabut</button><?php else: ?>-<?php endif; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div></div></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="tokenModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form id="tokenForm">
        <div class="modal-header"><h5 class="modal-title">Generate Token Desktop</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
        <div class="modal-body">
            <div class="form-group"><label>Nama Token</label><input name="name" class="form-control" maxlength="100" placeholder="HCIS Desktop Utama" required></div>
            <div class="form-group"><label>Berlaku Sampai <small class="text-muted">(kosong = tidak kedaluwarsa)</small></label><input type="date" name="expires_at" class="form-control"></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button class="btn btn-primary" type="submit">Generate</button></div>
    </form>
</div></div></div>
<script src="<?= base_url('assets/adminsb/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/adminsb/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/adminsb/js/sb-admin-2.min.js') ?>"></script>
<script>
$('#tokenForm').on('submit', function (event) {
    event.preventDefault();
    $.post('<?= site_url('admin/tokens/generate') ?>', $(this).serialize(), function (response) {
        if (!response.success) { Swal.fire('Gagal', response.message, 'error'); return; }
        $('#tokenModal').modal('hide');
        Swal.fire({ icon: 'success', title: 'Token berhasil dibuat', html: '<p>Simpan token ini sekarang. Token tidak dapat ditampilkan ulang.</p><textarea class="form-control" rows="3" readonly id="generatedToken">' + $('<div>').text(response.token).html() + '</textarea><button class="btn btn-primary mt-3" onclick="copyToken()">Salin Token</button>', showConfirmButton: true, confirmButtonText: 'Selesai' }).then(function () { window.location.reload(); });
    }, 'json').fail(function (xhr) { Swal.fire('Gagal', xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Token gagal dibuat.', 'error'); });
});
function copyToken() { var field = document.getElementById('generatedToken'); field.select(); document.execCommand('copy'); }
$('.btn-revoke').on('click', function () {
    var id = $(this).data('id');
    Swal.fire({ title: 'Cabut token?', text: 'Aplikasi desktop tidak akan bisa memakai token ini lagi.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Cabut', cancelButtonText: 'Batal' }).then(function (result) {
        if (!result.isConfirmed) return;
        $.post('<?= site_url('admin/tokens/revoke') ?>/' + id, function (response) { Swal.fire(response.success ? 'Berhasil' : 'Gagal', response.message, response.success ? 'success' : 'error').then(function () { window.location.reload(); }); }, 'json');
    });
});
</script>
</body>
</html>
