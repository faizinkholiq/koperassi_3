<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Anggota</h6>
    </div>
    <div class="card-body">
        <button class="btn my-btn-primary"><i class="fas fw fa-user-plus mr-1"></i> Anggota</button>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered" id="anggotaTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" width="10">No</th>
                        <th class="text-center">NIK</th>
                        <th class="text-center">TMK</th>
                        <th class="text-center">Nama Anggota</th>
                        <th class="text-center">No. Telp</th>
                        <th class="text-center">Tgl. Keanggotaan</th>
                        <th class="text-center">Status Keanggotaan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>123456789</td>
                        <td>986574</td>
                        <td>Fulan</td>
                        <td>089786321</td>
                        <td>10-10-2022</td>
                        <td>Aktif</td>
                        <td>
                            <button class="btn my-btn-primary">Ubah</button>
                            <button class="btn btn-primary">Detail</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>

<script>

    // Call the dataTables jQuery plugin
    $(document).ready(function() {
        $('#anggotaTable').DataTable();
    });

</script>