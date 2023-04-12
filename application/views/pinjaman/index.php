<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

<?php
    $status = isset($_GET['status'])? $_GET['status'] : '';
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
            <div class="col-lg-6 font-weight-bold border-right">
                <div class="text-lg mb-2">Plafon: <span class="text-danger ml-2"><?= rupiah($summary['plafon']) ?></span></div>
                <div class="text-lg">Sisa Pinjaman: <span class="text-danger ml-2"><?= rupiah($summary['sisa']) ?></span></div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <a href="#!" class="btn my-btn-primary mr-2" onclick="showForm()"><i class="fas fw fa-plus mr-1"></i> Ajukan Pinjaman</a>
            </div>
            <div class="col-lg-6 text-right">
                <a href="<?= site_url('pinjaman') ?>" class="btn font-weight-bold <?= ($status == 'All' || $status == '')? 'btn-primary' : 'btn-secondary' ?>">All</a>
                <a href="<?= site_url('pinjaman') ?>?status=Lunas" class="btn ml-1 font-weight-bold <?= ($status == 'Lunas')? 'btn-primary' : 'btn-secondary' ?>">Lunas</a>
                <a href="<?= site_url('pinjaman') ?>?status=Belum Lunas" class="btn ml-1 font-weight-bold <?= ($status == 'Belum Lunas')? 'btn-primary' : 'btn-secondary' ?>">Belum Lunas</a>
            </div>
        </div><hr>
        <div class="table-responsive">
            <table class="table table-bordered" id="pinjamanTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Tahun</th>
                        <th class="text-center">Bulan</th>
                        <th class="text-center">Limit Pinjaman</th>
                        <th class="text-center">Nilai Pinjaman</th>
                        <th width="100" class="text-center">Jml. Angsuran</th>
                        <th width="100" class="text-center">Total Angsur</th> 
                        <th width="120" class="text-center">Status Pengajuan</th>
                        <th width="120" class="text-center">Status Angsuran</th>
                        <th width="150" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

 <!-- Input Modal-->
 <div class="modal fade" id="inputModal" tabindex="-1" role="dialog" aria-labelledby="inputModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inputModalLabel"><i class="mr-2 fas fa-hand-holding-usd"></i> <span id="inputModalTitle">Ajukan Pinjaman</span></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="formPinjaman" action="<?= site_url('pinjaman/create') ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="idPinjaman" name="id" /> 
            <input type="hidden" id="idPerson" name="person" value="<?= $person_id ?>"/> 
            <div class="modal-body">
                <div class="row mb-4 mt-4">
                    <div class="col-lg-12">
                        <div class="row mb-3">
                            <div class="col-lg-3">Tanggal Bayar</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="date" class="form-control form-control-user" id="tglDateInput" name="date" 
                                    value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Tahun</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <select class="form-control" id="yearCombo" name="year">
                                    <?php 
                                    $start = 2019;
                                    for($i = $start; $i <= date('Y'); $i++):
                                    ?>
                                    <option value="<?= $i ?>" <?= ($i == date('Y'))? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Bulan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <select class="form-control" id="monthCombo" name="month">
                                    <?php 
                                    $months = ['Januari', 'Februari', 'Maret', 'April', 'May', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                    foreach($months as $key => $item):
                                    ?>
                                    <option value="<?= $key+1 ?>"><?= $item ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Limit Pinjaman</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Rp</div>
                                    </div>
                                    <input disabled type="text" class="form-control" id="limitTextInput" name="limit" placeholder="...">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Nilai Pengajuan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Rp</div>
                                    </div>
                                    <input type="text" class="form-control" id="balanceTextInput" name="balance" placeholder="..." required>
                                </div>
                                <span id="limitValidate" class="text-danger font-weight-bold text-sm" id=""><i class="fas fa-exclamation-triangle mr-2"></i><span id="limitValidateMsg"></span></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Total Angsuran</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <select class="form-control" id="angsuranCombo" name="angsuran">                                   
                                    <option value="12">12</option>
                                    <option value="24">24</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger mt-4 mb-4" data-dismiss="modal">Batal</button>
                <button type="button" id="btnReset" class="btn btn-info mt-4 mb-4" onclick="resetForm()">Reset</button>
                <button type="button" id="btnSubmit" class="btn btn-success mt-4 mb-4 ml-2 mr-4"> Submit Pinjaman <i class="ml-2 fas fa-chevron-right"></i></button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-trash mr-2"></i>Hapus Data</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <strong>Apakah anda yakin ingin menghapus data ini?</strong>
            </div>
            <div class="modal-footer">
                <form method="GET" action="<?=site_url('pinjaman/delete/') ?>">
                    <input type="hidden" id="delID" name="id" />
                    <button class="btn btn-danger mr-2" type="submit">Ya, Hapus</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

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
    let status = '<?= $status ?>';
    const summary = <?= json_encode($summary) ?>;

    const rupiah = (number)=>{
        return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR"
        }).format(number);
    }

    let dt = $('#pinjamanTable').DataTable({
        dom: "Bfrtip",
        ajax: {
            url: url.site + "/pinjaman/get_dt",
            type: "POST",
            data: {
                status: status
            }
        },
        drawCallback: function(settings) {
        },
        scrollX: true,
        processing: true,
        serverSide: true,
        columns: [
            { data: "date" },
            { data: "year" },
            { 
                data: "month", 
                render: function (data, type, row) {
                    return month_list[Number(data) - 1];
                }
            },
            { 
                data: "plafon", 
                render: function (data, type, row) {
                    let num = parseFloat(data??0)
                    return rupiah(num)
                }
            },
            { 
                data: "balance", 
                render: function (data, type, row) {
                    let num = parseFloat(data??0)
                    return rupiah(num)
                }
            },
            { data: "angsuran", class: "text-center" },
            { data: "angsuran_paid", class: "text-center" },
            { 
                data: "status", 
                class: "text-center",
                render: function (data, type, row) {
                    let tag = '-';

                    switch(data){
                        case "Approved":
                            tag = "<span class='bg-success text-white font-weight-bold px-2 py-1 rounded'><i class='fas fa-check'></i> Disetujui</span>";
                            break;
                        case "Pending":
                            tag = "<span class='bg-warning text-white font-weight-bold px-2 py-1 rounded'><i class='fas fa-clock'></i> Pending</span>";
                            break;
                        case "Decline":
                            tag = `<span onclick='showReason("`+row.reason+`")' class='bg-danger text-white font-weight-bold px-2 py-1 rounded' style="cursor:pointer;"><i class='fas fa-times'></i> Ditolak</span>`;
                            break;
                    }

                    return tag;
                } 
            },
            { 
                data: "status_angsuran", 
                class: "text-center",
                render: function (data, type, row) {
                    let tag = '-';

                    switch(data){
                        case "Lunas":
                            tag = "<span class='bg-success text-white font-weight-bold px-2 py-1 rounded'>Lunas</span>";
                            break;
                        case "Belum Lunas":
                            tag = "<span class='font-weight-bold'>Belum Lunas</span>";
                            break;
                    }

                    return tag;
                }
            },
            { 
                class: "text-center",
                render: function (data, type, row) {
                    let btn = '-';
                    if (row.status == 'Pending' || row.status == 'Decline') {
                        btn = `
                            <a href='${url.site}/pinjaman/detail/${row.id}' class="btn btn-sm btn-info" style="width: 2rem;"><i class="fas fa-eye"></i></a>
                            <button type="button" onclick='doEdit(`+ JSON.stringify(row) + `)' class="btn btn-sm btn-primary" style="width: 2rem;"><i class="fas fa-edit"></i></button>
                            <button type="button" onclick="doDelete(${row.id})" class="btn btn-sm btn-danger" style="width: 2rem;"><i class="fas fa-trash"></i></button>
                        `;
                    }else{
                        btn = `
                            <a href='${url.site}/pinjaman/detail/${row.id}' class="btn btn-sm btn-info" style="width: 2rem;"><i class="fas fa-eye"></i></a>
                        `;
                    }

                    return btn;
                }
            },
        ],
        ordering: false,
    });

    // Call the dataTables jQuery plugin
    $(document).ready(function() {
        $("#btnSubmit").click(function() {
            if($('#formPinjaman')[0].checkValidity()){
                let limit = summary.limit;
                let balance = $('#balanceTextInput').val();
                if (limit >= balance) {
                    $('#formPinjaman').submit();
                }else{
                    $('#limitValidate').show();
                    $('#limitValidateMsg').text('Nilai pengajuan tidak boleh melebihi limit');
                }
            }else{
                $('#limitValidate').show();
                $('#limitValidateMsg').text('Silahkan masukan nominal pinjaman yang akan diajukan');
            }
        });
    });

    function showForm(){
        resetForm();
        $('#inputModalTitle').text('Ajukan Pinjaman');
        $('#formPinjaman').attr('action', url.site + "/pinjaman/create")
        $('#btnReset').show();

        $('#inputModal').modal('show');
    }

    function resetForm() {
        $('#idPinjaman').val('');
        $('#tglDateInput').val(date_now);
        $('#yearCombo').val(year_now);
        $('#monthCombo').val(month_now);
        $('#limitTextInput').val(summary.plafon);
        $('#balanceTextInput').val('');
        $('#angsuranCombo').val(12);
        $('#limitValidate').hide();
    }

    function doEdit(row){
        resetForm();
        $('#inputModalTitle').text('Ubah Pengajuan Pinjaman');
        $('#formPinjaman').attr('action', url.site + "/pinjaman/edit")
        $('#idPinjaman').val(row.id)
        $('#tglDateInput').val(row.date);
        $('#yearCombo').val(Number(row.year));
        $('#monthCombo').val(Number(row.month));
        $('#limitTextInput').val(row.limit);
        $('#balanceTextInput').val(row.balance);
        $('#angsuranCombo').val(row.angsuran);

        $('#btnReset').hide();
        $('#inputModal').modal('show');
    }

    function doDelete(id){
        $('#delID').val(id);
        $('#deleteModal').modal('show');
    }

</script>