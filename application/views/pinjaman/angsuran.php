<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table style="width:100%;" class="table table-bordered display nowrap" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">Bulan</th>
                        <th class="text-center">Tahun</th>
                        <th class="text-center">Bulan Ke-</th>
                        <th class="text-center">Sisa Hutang</th>
                        <th class="text-center">Pokok</th>
                        <th class="text-center">Bunga</th>
                        <th class="text-center">Angsuran</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($data)): foreach ($data as $key => $value): ?>
                        <tr>
                            <td><?= $value['month'] ?></td>
                            <td><?= $value['year'] ?></td>
                            <td><?= $value['month_no'] ?></td>
                            <td><?= $value['sisa'] ?></td>
                            <td><?= $value['pokok'] ?></td>
                            <td><?= $value['bunga'] ?></td>
                            <td><?= $value['angsuran'] ?></td>
                            <td><?= $value['status'] ?></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No Rows Result Set</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>