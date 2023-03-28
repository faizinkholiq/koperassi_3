<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

<div class="card mb-4 shadow">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12 font-weight-bold">
                <div class="mb-2 row">
                    <div class="col-lg-2">NIK</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= $detail['summary']['nik'] ?>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-lg-2">Nama</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= $detail['summary']['name'] ?>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-lg-2">Depo</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= ($detail['summary']['depo'])? $detail['summary']['depo'] : '-' ?>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-lg-2">Total Bayar</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= $detail['summary']['total'] ?>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-lg-2">Sisa Pinjaman</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= $detail['summary']['sisa'] ?>
                    </div>
                </div>
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
                        <th width="100" class="text-center">Bulan</th>
                        <th width="100" class="text-center">Tahun</th>
                        <th class="text-center">Bulan Ke-</th>
                        <th width="150" class="text-center">Sisa Hutang</th>
                        <th width="150" class="text-center">Pokok</th>
                        <th width="150" class="text-center">Bunga</th>
                        <th width="150" class="text-center">Angsuran</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $data = $detail["angsuran"];
                        if(count($data)): 
                        $sum_sisa = 0;
                        $sum_pokok = 0;
                        $sum_bunga = 0;
                        $sum_angsuran = 0;

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

                        foreach ($data as $key => $value): ?>
                        <tr>
                            <td><?= ($value['month'])? $month_list[$value['month']-1] : '-' ?></td>
                            <td><?= $value['year'] ?></td>
                            <td><?= $value['month_no'] ?></td>
                            <td><?= $value['sisa'] ?></td>
                            <td><?= $value['pokok'] ?></td>
                            <td><?= $value['bunga'] ?></td>
                            <td><?= $value['angsuran'] ?></td>
                            <td class="text-center"><?= $value['status'] ?></td>
                            <td class="text-center">
                                <?php if($value['status'] != 'Lunas'): ?>
                                <button type="button" onclick='doPaid(<?= json_encode($value) ?>)' class="btn btn-sm btn-primary" style="width: 2rem;">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </button>
                                <?php else: echo '-'; endif; ?>
                            </td>
                        </tr>
                    <?php 
                        $sum_sisa += $value['sisa'];
                        $sum_pokok += $value['pokok'];
                        $sum_bunga += $value['bunga'];
                        $sum_angsuran += $value['angsuran'];
                        endforeach; 
                    ?>
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
                            <td colspan="9" class="text-center">No Rows Result Set</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="paidModal" tabindex="-1" role="dialog" aria-labelledby="paidModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paidModalLabel"><i class="fas fa-check mr-2"></i>Setujui Perubahan Data</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" action="<?=site_url('pinjaman/paid')?>">
                <div class="modal-body">
                    <div class="row mb-4 mt-4">
                        <div class="col-lg-12">
                            <input type="hidden" id="paidID" name="id" />
                            <div class="row mb-3">
                                <div class="col-lg-3">Tanggal Bayar</div>
                                <div class="col-lg-1 text-right">:</div>
                                <div class="col-lg-6">
                                    <input type="date" class="form-control form-control-user" id="tglDateInput" name="date" 
                                        value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3">Bulan Ke -</div>
                                <div class="col-lg-1 text-right">:</div>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control form-control-user" id="monthNoText" name="month_no" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3">Nominal</div>
                                <div class="col-lg-1 text-right">:</div>
                                <div class="col-lg-6">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Rp</div>
                                        </div>
                                        <input type="text" class="form-control" id="balanceTextInput" name="balance" placeholder="..." required readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-success mr-2" type="submit">Ya, Proses Pembayaran</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    const url = {
        base: '<?=base_url() ?>',
        site: '<?=site_url() ?>'
    };

    const month_list = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
    ];

    const user_id = <?= $id ?>;
    const person = <?= $nik ?>;
    const date_now = '<?= date('Y-m-d') ?>';
    const year_now = '<?= date('Y') ?>';
    const month_now = '<?= (int)date('m') ?>';

    const rupiah = (number)=>{
        return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR"
        }).format(number);
    }

    // Call the dataTables jQuery plugin
    $(document).ready(function() {
    });

    function doPaid(row){
        $('#paidID').val(row.id);
        $('#monthNoText').val(row.month_no)
        $('#balanceTextInput').val(row.angsuran)
        $('#paidModal').modal('show');
    }

</script>