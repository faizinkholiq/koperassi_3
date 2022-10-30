<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Simpanan Wajib</h6>
    </div>
    <div class="card-body">
        <a href="<?= site_url('simpanan/input/wajib') ?>" class="btn my-btn-primary mr-2"><i class="fas fw fa-plus mr-1"></i> Tambah baru</a>
        <a href="#!" class="btn btn-danger"><i class="fas fw fa-file-import mr-1"></i> Import Data</a>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered" id="simpananTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="10">No</th>
                        <th class="text-center">NIK</th>
                        <th class="text-center">TMK</th>
                        <th class="text-center">Nama Anggota</th>
                        <th class="text-center">No. Telp</th>
                        <th class="text-center">Tgl. Keanggotaan</th>
                        <th class="text-center">Jml. Simpanan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $key => $row): ?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><?=$row["nik"]?></td>
                        <td><?=$row["tmk"]?></td>
                        <td><?=$row["name"]?></td>
                        <td><?=$row["phone"]?></td>
                        <td><?=$row["join_date"]?></td>
                        <td><?=$row["balance"]?></td>
                    </tr>
                    <?php endforeach; ?>
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
        $('#simpananTable').DataTable();
    });

</script>