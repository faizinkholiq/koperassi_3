<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

<?php
    if(!empty($this->session->flashdata('msg'))):
    $msg = $this->session->flashdata('msg');
?>
<div class="alert <?= ($msg['success'])? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show" role="alert">
    <strong><?= ($msg['success'])? $msg["message"] : $msg["error"] ?></strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<div class="card mb-4 shadow">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-10 font-weight-bold">
                <div class="mb-2 row">
                    <div class="col-lg-2">NIK</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= isset($detail['summary']['nik']) && !empty($detail['summary']['nik'])? $detail['summary']['nik'] : '-' ?>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-lg-2">Nama</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= isset($detail['summary']['name']) && !empty($detail['summary']['name'])? $detail['summary']['name'] : '-' ?>
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
                        <?= isset($detail['summary']['total']) && !empty($detail['summary']['total'])? rupiah($detail['summary']['total']) : '-' ?>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-lg-2">Sisa Pinjaman</div>
                    <div class="col-lg-10"><span class="mr-2">:</span> 
                        <?= isset($detail['summary']['sisa']) && !empty($detail['summary']['sisa'])? rupiah($detail['summary']['sisa']) : '-' ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 text-center">
                <?php 
                    if(
                        isset($detail['summary']['total_angsuran']) && 
                        $detail['summary']['total_angsuran'] > 0 &&
                        ($detail['summary']['total_angsuran'] == $detail['summary']['angsuran_lunas']) 
                    ): ?>
                <div style="font-size:1.2rem;" class="px-3 py-2 bg-success text-white rounded text-center font-weight-bold">
                    Lunas
                </div>
                <?php else: ?>
                <div style="font-size:1.2rem;" class="px-3 py-2 bg-danger text-white rounded text-center font-weight-bold">
                    Belum Lunas
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<div class="card shadow mb-4">
    <div class="card-body">
        <?php if($role == 1): ?>
        <div class="row mb-1">
            <div class="col-lg-12">
                <button type="button" class="btn btn-primary ml-1" onclick="showImportForm()"><i class="fas fw fa-file-import mr-1"></i> Import Data Angsuran</button><hr/>
            </div>
        </div>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table table-bordered display nowrap" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">Bulan</th>
                        <th class="text-center">Tahun</th>
                        <th width="80" class="text-center">Bulan Ke-</th>
                        <!-- <th width="150" class="text-center">Sisa Hutang</th> -->
                        <th class="text-center">Pokok</th>
                        <th class="text-center">Bunga</th>
                        <th class="text-center">Angsuran</th>
                        <th width="120" class="text-center">Status</th>
                        <?php if($role == 1): ?>
                        <th class="text-center">Aksi</th>
                        <?php endif; ?>
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
                            <td class="text-center"><?= $value['month_no'] ?></td>
                            <td><?= rupiah($value['pokok']) ?></td>
                            <td><?= rupiah($value['bunga']) ?></td>
                            <td><?= rupiah($value['angsuran']) ?></td>
                            <td class="text-center"><?= $value['status'] ?></td>
                            <?php if($role == 1): ?>
                                <td class="text-center">
                                    <?php if($value['status'] != 'Lunas'): ?>
                                    <button type="button" onclick='doPaid(<?= json_encode($value) ?>)' class="btn btn-sm btn-primary" style="width: 2rem;">
                                        <i class="fas fa-hand-holding-usd"></i>
                                    </button>
                                    <?php else: echo '-'; endif; ?>
                                </td>
                            <?php endif; ?>
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
                            <td><?= rupiah($sum_pokok) ?></td>
                            <td><?= rupiah($sum_bunga) ?></td>
                            <td><?= rupiah($sum_angsuran) ?></td>
                            <td></td>
                            <?php if($role == 1): ?>
                            <td></td>
                            <?php endif; ?>
                        </tr>
                    <?php else: ?>
                        <?php if($role == 1): ?>
                        <tr>
                            <td colspan="9" class="text-center">No Rows Result Set</td>
                        </tr>
                        <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No Rows Result Set</td>
                        </tr>
                        <?php endif; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="paidModal" tabindex="-1" role="dialog" aria-labelledby="paidModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paidModalLabel"><i class="fas fa-hand-holding-usd mr-2"></i>Bayar Angsuran</h5>
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
                                <div class="col-lg-4">Tanggal Bayar</div>
                                <div class="col-lg-1 text-right">:</div>
                                <div class="col-lg-6">
                                    <input type="date" class="form-control form-control-user" id="tglDateInput" name="date" 
                                        value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-4">Bulan Ke -</div>
                                <div class="col-lg-1 text-right">:</div>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control form-control-user" id="monthNoText" name="month_no" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">Nominal</div>
                                <div class="col-lg-1 text-right">:</div>
                                <div class="col-lg-6">
                                    <div class="input-group">
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

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel"><i class="fas fa-file-import mr-2"></i>Import Data Simpanan Pokok</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="formImport" action="#!" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="row mb-4 mt-4" style="position: relative;">
                    <div class="loading" style="background: rgba(255, 255, 255, 0.8);; position: absolute; width: 100%; height: 100%; display: none; align-items:center; justify-content: center; z-index: 9999;">
                        <div class="load-3 text-center">
                            <div class="mb-4 font-weight-bold text-lg">Mohon tunggu sebentar, proses import sedang berjalan</div>
                            <div class="line bg-danger"></div>
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-4">
                        Silahkan pilih file yang akan di import, mohon gunakan template ini: <a href="<?= site_url("pinjaman/export_template_angsuran/").$detail['summary']['id'] ?>" target="blank">template.csv</a>
                    </div>
                    <div class="col-lg-8">
                        <input style="height: 100%;" type="file" class="form-control form-control-user" id="importFile" name="file" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btnImport" class="btn btn-primary mr-2" type="submit"><i class="fas fa-upload mr-2"></i>Import Data</button>
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
        $('#balanceTextInput').val(Math.round(row.angsuran))
        $('#paidModal').modal('show');
    }

    function showImportForm(){
        $('#importModal').modal('show');
    }

</script>