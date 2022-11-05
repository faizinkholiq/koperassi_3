<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <a href="<?=site_url('anggota/create') ?>" class="btn my-btn-primary"><i class="fas fw fa-user-plus mr-1"></i> Anggota</a>
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
                    <?php foreach($data as $key => $row): ?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><?=$row["nik"]?></td>
                        <td><?=$row["tmk"]?></td>
                        <td><?=$row["name"]?></td>
                        <td><?=$row["phone"]?></td>
                        <td><?=$row["join_date"]?></td>
                        <td><?=$row["status"]?></td>
                        <td>
                            <a href="<?=site_url('anggota/edit/'.$row["id"])?>" class="btn my-btn-primary">Ubah</a>
                            <a href="<?=site_url('anggota/detail/'.$row["id"])?>" class="btn btn-primary">Detail</a>
                        </td>
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
    const url = {
        "site": "<?= site_url() ?>",
        "base": "<?= base_url() ?>",
    }

    // Call the dataTables jQuery plugin
    $(document).ready(function() {
        $('#anggotaTable').DataTable();
    });

</script>