<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

<div class="card mb-4 shadow">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-10 font-weight-bold">
                <div class="mb-2 row">
                    <div class="col-lg-2">NIK</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= isset($detail['nik']) && !empty($detail['nik'])? $detail['nik'] : '-' ?>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-lg-2">Nama</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= isset($detail['name']) && !empty($detail['name'])? $detail['name'] : '-' ?>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-lg-2">Depo</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= isset($detail['depo']) && !empty($detail['depo'])? $detail['depo'] : '-' ?>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-lg-2">Total Bayar</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= isset($detail['total']) && !empty($detail['total']) && $detail['status_angsuran'] == 'Belum Lunas' ? rupiah($detail['total']) : '-' ?>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-lg-2">Sisa Pinjaman</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= isset($detail['sisa']) && !empty($detail['sisa']) && $detail['status_angsuran'] == 'Belum Lunas' ? rupiah($detail['sisa']) : '-' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table style="width:100%;" class="table table-bordered display nowrap" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">Bulan</th>
                        <th class="text-center">Tahun</th>
                        <th width="80" class="text-center">Bulan Ke-</th>
                        <th class="text-center">Sisa Hutang</th>
                        <th class="text-center">Pokok</th>
                        <th class="text-center">Bunga</th>
                        <th class="text-center">Angsuran</th>
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
                            <td class="text-center"><?= rupiah(0) ?></td>
                            <td class="text-center"><?= rupiah($value['pokok']) ?></td>
                            <td class="text-center"><?= rupiah($value['bunga']) ?></td>
                            <td class="text-center"><?= rupiah($value['angsuran']) ?></td>
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