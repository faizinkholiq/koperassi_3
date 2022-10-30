<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

<div class="card mb-4 shadow">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6 font-weight-bold border-right">
                <div class="text-lg mb-2">Plafon: <span class="text-danger ml-2">Rp<?= $data['summary']['plafon'] ?></span></div>
                <div class="text-lg">Limit Pinjaman: <span class="text-danger ml-2">Rp<?= $data['summary']['limit'] ?></span></div>
            </div>
            <div class="col-lg-6 font-weight-bold text-right">
                <div class="text-lg mb-2">Gaji Pokok: <span class="text-danger ml-2">Rp<?= $data['summary']['gaji'] ?></span></div>
                <div class="text-lg">Total Simpanan: <span class="text-danger ml-2">Rp<?= $data['summary']['simpanan'] ?></span></div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Simpanan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="simpananTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="10">No</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Uraian</th>
                        <th class="text-center">Pemasukan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['rows'] as $key => $row): ?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><?=$row["date"]?></td>
                        <td><?=$row["type"]?></td>
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