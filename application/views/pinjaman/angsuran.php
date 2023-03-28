<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table style="width:100%;" class="table table-bordered display nowrap" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">Bulan</th>
                        <th width="100" class="text-center">Tahun</th>
                        <th width="80" class="text-center">Bulan Ke-</th>
                        <th width="160" class="text-center">Sisa Hutang</th>
                        <th width="160" class="text-center">Pokok</th>
                        <th width="160" class="text-center">Bunga</th>
                        <th width="160" class="text-center">Angsuran</th>
                        <th width="130" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $month_list = [
                            "Januari",
                            "Februari",
                            "Maret",
                            "April",
                            "Mei",
                            "Juni",
                            "Juli",
                            "Agustus",
                            "September",
                            "Oktober",
                            "November",
                            "Desember"
                        ];

                        if(count($data)): foreach ($data as $key => $value): ?>
                        <tr>
                            <td><?= $month_list[$value['month']-1] ?></td>
                            <td><?= $value['year'] ?></td>
                            <td class="text-center"><?= $value['month_no'] ?></td>
                            <td><?= rupiah($value['sisa']) ?></td>
                            <td><?= rupiah($value['pokok']) ?></td>
                            <td><?= rupiah($value['bunga']) ?></td>
                            <td><?= rupiah($value['angsuran']) ?></td>
                            <td class="text-center"><?= $value['status'] ?></td>
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