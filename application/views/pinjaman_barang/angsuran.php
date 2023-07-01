<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

<div class="card mb-4 shadow">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-10 font-weight-bold">
                <div class="mb-2 row">
                    <div class="col-lg-2">NIK</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= !empty($nik)? $nik : '-' ?>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-lg-2">Nama</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= !empty($name)? $name : '-' ?>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-lg-2">Depo</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= !empty($depo_name)? $depo_name : '-' ?>
                    </div>
                </div><hr/>
                <div class="mb-2 row">
                    <div class="col-lg-2">Nama Barang</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= ($summary['name'])? $summary['name'] : '-' ?>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-lg-2">Tanggal Pinjam</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= ($summary['date'])? $summary['date'] : '-' ?>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-lg-2">Harga Beli</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= isset($summary['buy']) && !empty($summary['buy'])? rupiah($summary['buy']) : '-' ?>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-lg-2">Total Bayar</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= isset($summary['total_bayar']) && !empty($summary['total_bayar'])? rupiah($summary['total_bayar']) : '-' ?>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-lg-2">Sisa Pinjaman</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= isset($summary['sisa_pinjaman']) && !empty($summary['sisa_pinjaman'])? rupiah($summary['sisa_pinjaman']) : '-' ?>
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

                        if(count($data)): 
                        $total_pinjaman = floatval($summary['total_pinjaman']);
                        foreach ($data as $key => $value): ?>
                        <tr>
                            <td><?= $month_list[$value['month']-1] ?></td>
                            <td><?= $value['year'] ?></td>
                            <td class="text-center"><?= $value['month_no'] ?></td>
                            <td class="text-center"><?= rupiah($total_pinjaman) ?></td>
                            <td class="text-center"><?= rupiah($value['angsuran']) ?></td>
                            <td class="text-center"><?= $value['status'] ?></td>
                        </tr>
                    <?php 
                        $total_pinjaman = $total_pinjaman - floatval($value['angsuran']);
                        endforeach; 
                        else: 
                    ?>
                        <tr>
                            <td colspan="8" class="text-center">No Rows Result Set</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>