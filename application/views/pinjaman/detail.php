<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

<div class="card mb-4 shadow">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6 font-weight-bold border-right">
                <div class="text-lg mb-2">Plafon: <span class="text-danger ml-2">Rp<?= $summary['plafon'] ?></span></div>
                <div class="text-lg">Limit Pinjaman: <span class="text-danger ml-2">Rp<?= $summary['limit'] ?></span></div>
            </div>
            <div class="col-lg-6 font-weight-bold text-right">
                <div class="text-lg">Sisa Pinjaman: <span class="text-danger ml-2">Rp<?= $summary['sisa'] ?></span></div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table style="width:100%" class="table table-bordered display nowrap" width="100%" cellspacing="0">
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
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if(count($data)): 
                        $sum_sisa = 0;
                        $sum_pokok = 0;
                        $sum_bunga = 0;
                        $sum_angsuran = 0;

                        foreach ($data as $key => $value): ?>
                        <tr>
                            <td><?= $value['month'] ?></td>
                            <td><?= $value['year'] ?></td>
                            <td><?= $value['month_no'] ?></td>
                            <td><?= $value['sisa'] ?></td>
                            <td><?= $value['pokok'] ?></td>
                            <td><?= $value['bunga'] ?></td>
                            <td><?= $value['angsuran'] ?></td>
                            <td><?= $value['status'] ?></td>
                            <td class="text-center">
                                <?php if($value['status'] != 'Lunas'): ?>
                                <button type="button" onclick="doPaid()" class="btn btn-sm btn-primary" style="width: 2rem;"><i class="fas fa-money-check-edit-alt"></i></button>
                                <?php else: echo '-'; endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                        <tr class="font-weight-bold">
                            <td colspan="3">Total</td>
                            <td><?= $sum_sisa ?></td>
                            <td><?= $sum_pokok ?></td>
                            <td><?= $sum_bunga ?></td>
                            <td><?= $sum_angsuran ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No Rows Result Set</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>