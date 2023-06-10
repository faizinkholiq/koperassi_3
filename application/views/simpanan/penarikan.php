<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<style>
    .bg-light-disabled{
        background: #ebebeb;
        font-weight: bold;
    }
</style>

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

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <a href="#!" class="btn my-btn-primary mr-2" onclick="showPenarikanForm()"><i class="fas fw fa-plus mr-1"></i> Tarik Simpanan</a>
                <a href="#!" class="btn btn-secondary mr-2" onclick="showNetoffForm()"><i class="fas fw fa-plus mr-1"></i> Net Off</a>
            </div>
        </div><hr>
        <div class="table-responsive">
            <table class="table table-bordered" id="penarikanTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Tahun</th>
                        <th class="text-center">Bulan</th>
                        <th class="text-center">Nama Anggota</th>
                        <th class="text-center">Total Simpanan</th>
                        <th class="text-center">Nilai Ditarik</th>
                        <th class="text-center">Jenis Pernarikan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

 <!-- Penarikan Input Modal-->
 <div class="modal fade" id="penarikanInputModal" tabindex="-1" role="dialog" aria-labelledby="penarikanInputModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="penarikanInputModalLabel"><i class="mr-2 fas fa-hand-holding-usd"></i> <span id="penarikanInputModalTitle">Penarikan Simpanan</span></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="formPenarikan" action="<?= site_url('simpanan/create_penarikan') ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="idPenarikan" name="id" /> 
            <div class="modal-body">
                <div class="row mb-4 mt-4">
                    <div class="col-lg-12">
                        <div class="row mb-3">
                            <div class="col-lg-3">Tanggal Bayar</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="date" class="form-control form-control-user" id="penarikanTglDateInput" name="date" 
                                    value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Nama Anggota</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <select id="penarikanAnggotaSelect" name="person" data-live-search="true" class="selectpicker form-control form-control-user" required>
                                    <option value="">- Please Select -</option>
                                    <?php foreach($person_list_all as $key => $item): ?>
                                    <option value="<?= $item["id"] ?>"><?= $item["name"] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small id="netoffAnggotaAlert" class="text-danger font-weight-bold mt-4">
                                    * Silahkan pilih anggota terlebih dahulu
                                </small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Tipe</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="hidden" name="type" value="sukarela">
                                <select class="form-control form-control-user" id="penarikanStatusCombo" name="type_combo" disabled>
                                    <option value="pokok">Simpanan Pokok</option>
                                    <option value="wajib">Simpanan Wajib</option>
                                    <option value="sukarela" selected>Simpanan Sukarela</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Tahun</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <select class="form-control" id="penarikanYearCombo" name="year">
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
                                <select class="form-control" id="penarikanMonthCombo" name="month">
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
                            <div class="col-lg-3">Nominal</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Rp</div>
                                    </div>
                                    <input type="text" class="form-control" id="penarikanJumlahTextInput" name="balance" placeholder="..." required>
                                </div>
                                <!-- <span>
                                    nominal yang didapat ditarik: <span class="font-weight-bold text-danger" id="penarikanSpanMaxSimpanan">-</span>
                                </span> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger mt-4 mb-4" data-dismiss="modal">Batal</button>
                <button type="button" id="penarikanBtnReset" class="btn btn-info mt-4 mb-4" onclick="resetPenarikanForm()">Reset</button>
                <button type="submit" class="btn btn-success mt-4 mb-4 ml-2 mr-4"> Submit Simpanan <i class="ml-2 fas fa-chevron-right"></i></button>
            </div>
            </form>
        </div>
    </div>
</div>

 <!-- NET OFF Input Modal-->
 <div class="modal fade" id="netoffInputModal" tabindex="-1" role="dialog" aria-labelledby="netoffInputModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="netoffInputModalLabel"><i class="mr-2 fas fa-hand-holding-usd"></i> Net Off Simpanan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="formNetOff" action="<?= site_url('simpanan/do_net_off') ?>" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="row mb-4 mt-4">
                    <div class="col-lg-12">
                        <div class="row mb-3">
                            <div class="col-lg-3">Nama Anggota</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <select id="netofnetoffAAnggotaSelect" name="person" data-live-search="true" class="selectpicker form-control form-control-user" required>
                                    <option value="">- Please Select -</option>
                                    <?php foreach($person_list as $key => $item): ?>
                                    <option value="<?= $item["id"] ?>"><?= $item["name"] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small id="netoffAnggotaAlert" class="text-danger font-weight-bold mt-4">
                                    * Silahkan pilih anggota terlebih dahulu
                                </small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Tanggal Bayar</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-5">
                                <input type="date" class="form-control form-control-user" id="netoffTglDateInput" name="date" 
                                    value="<?= date('Y-m-d') ?>" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Month</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <select class="form-control" id="netoffMonthCombo" name="month">
                                    <?php
                                      $months = ['Januari', 'Februari', 'Maret', 'April', 'May', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                      foreach($months as $key => $item): ?>
                                      <option value="<?= $key+1 ?>" <?= ($key+1 == date('m'))? 'selected' : '' ?>><?= $item ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Year</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <select class="form-control" id="netoffYearCombo" name="year">
                                    <?php for($i = date('Y'); $i <= date('Y')+5; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($i == date('Y'))? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="row mb-3">
                            <div class="col-lg-3">Nominal</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-5">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Rp</div>
                                    </div>
                                    <input type="text" class="form-control" id="nominalTextInput" name="balance" placeholder="...">
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger mt-4 mb-4" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success mt-4 mb-4 ml-2 mr-4"> Submit Net-Off<i class="ml-2 fas fa-chevron-right"></i></button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel"><i class="fas fa-check mr-2"></i>Setujui Perubahan Data</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <strong>Apakah anda yakin ingin menyetujui perubahan data tersebut?</strong>
            </div>
            <div class="modal-footer">
                <form method="POST" action="<?=site_url('simpanan/approve_penarikan')?>">
                    <input type="hidden" id="appID" name="id" />
                    <button class="btn btn-success mr-2" type="submit">Ya, Setuju</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel"><i class="fas fa-times mr-2"></i>Tolak Perubahan Data</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" action="<?=site_url('simpanan/reject_penarikan')?>">
                <div class="modal-body">
                    <strong>Apakah anda yakin ingin menolak perubahan data tersebut?</strong><br/>        
                    <textarea class="form-control form-control-user mt-4" name="reason" rows="5" placeholder="Silahkan tulis alasan mengapa data tersebut ditolak"></textarea><br/>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="rejID" name="id" />
                    <button class="btn btn-danger mr-2" type="submit">Ya, Tolak</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
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
                <form method="GET" action="<?=site_url('simpanan/delete_penarikan/') ?>">
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
<!-- <script src="https://cdn.datatables.net/rowgroup/1.3.0/js/dataTables.rowGroup.min.js"></script> -->

<script>
    const url = {
        base: '<?=base_url() ?>',
        site: '<?=site_url() ?>'
    };
    
    const list_anggota = <?= json_encode($person_list); ?>;
    const list_anggota_all = <?= json_encode($person_list_all); ?>;
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
    
    const date_now = '<?= date('Y-m-d') ?>';
    const year_now = '<?= date('Y') ?>';
    const month_now = '<?= (int)date('m') ?>';

    const rupiah = (number)=>{
        return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR"
        }).format(number);
    }

    let dt = $('#penarikanTable').DataTable({
        dom: "Bfrtip",
        ajax: {
            url: url.site + "/simpanan/get_dt_penarikan",
            type: "POST",
        },
        drawCallback: function(settings) {
            if(settings.json.data.length > 0){
                let total = settings.json.data.map(item => Number(item.balance)).reduce((acc, amount) => acc + amount);
                $('#totalSimpanan').text((total)? rupiah(total) : 0);
            }else{
                $('#totalSimpanan').text(0);
            }
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: "date" },
            { 
                data: "year",
                render: function (data, type, row) {
                    return data
                }
            },
            { 
                data: "month", 
                render: function (data, type, row) {
                    return month_list[Number(data) - 1];
                }
            },
            { data: "name" },
            { 
                data: "balance", 
                render: function (data, type, row) {
                    let num = parseFloat(data??0)
                    return rupiah(num)
                }
            },
            { 
                data: "withdraw", 
                render: function (data, type, row) {
                    let num = parseFloat(data??0)
                    return rupiah(num)
                }
            },
            { 
                data: "type" ,
                render: function(data, type, row) {
                    if (row.status == "Net-Off"){
                        return `<span class='bg-danger text-white font-weight-bold px-2 py-1 rounded'>Net-Off</span>`;
                    }else{
                        return data;
                    }
                }
            },
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
                class: "text-center",
                render: function (data, type, row) {
                    let btn = "-"
                    if (row.status == 'Pending') {
                        btn = `
                            <button type="button" onclick="doApprove(${row.id})" class="btn btn-sm btn-success" style="width: 2rem;"><i class="fas fa-check"></i></button>
                            <button type="button" onclick="doReject(${row.id})" class="btn btn-sm btn-danger mr-2" style="width: 2rem;"><i class="fas fa-times"></i></button>
                            <button type="button" onclick='doEdit(`+ JSON.stringify(row) + `)' class="btn btn-sm btn-primary" style="width: 2rem;"><i class="fas fa-edit"></i></button>
                            <button type="button" onclick="doDelete(${row.id})" class="btn btn-sm btn-danger" style="width: 2rem;"><i class="fas fa-trash"></i></button>
                        `;
                    }

                    return btn;
                }
            },
        ],
        ordering: false,
        createdRow: function( row, data, dataIndex ) {
            if (data.status == "Net-Off") {
                $(row).addClass( 'bg-light-disabled' );
            }
        }
    });

    $(document).ready(function() {
        $('.alert').alert()
        $('.selectpicker').selectpicker();

        $("#penarikanAnggotaSelect").change(function () {
            let person_id = this.value;
            let person = list_anggota_all.filter(r => r.id == person_id)

            if(person.length > 0){
                $('#penarikanAnggotaAlert').fadeOut();
            }else{
                $('#penarikanAnggotaAlert').fadeIn();
            }
        });

        $("#netoffAnggotaSelect").change(function () {
            let person_id = this.value;
            let person = list_anggota.filter(r => r.id == person_id)

            if(person.length > 0){
                $('#netoffAnggotaAlert').fadeOut();
                // $('#noAnggotaTextInput').val(person[0].nik)
                // $('#jabatanTextInput').val(person[0].position_name)
                // $('#depoTextInput').val(person[0].depo)
                // $('#alamatTextArea').text(person[0].address)
                // $('#noRekTextInput').val(person[0].acc_no)
            }else{
                $('#netoffAnggotaAlert').fadeIn();
                // $('#formSimpanan')[0].reset();
                // $('#alamatTextArea').text("")
            }
        });

    });

    function doApprove(id){
        $('#appID').val(id);
        $('#approveModal').modal('show');
    }

    function doReject(id){
        $('#rejID').val(id);
        $('#rejectModal').modal('show');
    }

    function showReason(reason){
        $('#reasonParagraph').text(reason);
        $('#reasonModal').modal('show');
    }

    function showNetoffForm(simpanan_id){
        $('#netoffInputModal').modal('show');
    }

    function showPenarikanForm(simpanan_id){
        resetPenarikanForm();
        $('#penarikanInputModalTitle').text('Tarik Data Simpanan');
        $('#formPenarikan').attr('action', url.site + "/simpanan/create_penarikan")
        $('#penarikanBtnReset').show();

        $('#penarikanInputModal').modal('show');
    }

    function resetPenarikanForm() {
        $('#formPenarikan')[0].reset();
        $('#penarikanTglDateInput').val(date_now);
        $('#penarikanYearCombo').val(year_now);
        $('#penarikanMonthCombo').val(month_now);
        $("#penarikanAnggotaSelect").val("");
        $('#penarikanAnggotaSelect').selectpicker('refresh');
    }

    function doEdit(row){
        resetPenarikanForm();
        console.log(row);
        $('#penarikanInputModalTitle').text('Ubah Data Penarikan');
        $('#formPenarikan').attr('action', url.site + "/simpanan/edit_penarikan")
        $('#idPenarikan').val(row.id)
        $('#penarikanTglDateInput').val(row.date);
        $('#penarikanAnggotaAlert').fadeOut();
        $('#penarikanYearCombo').val(row.year);
        $('#penarikanMonthCombo').val(Number(row.month));
        $('#penarikanJumlahTextInput').val(row.withdraw);
        $("#penarikanAnggotaSelect").val(row.person_id);
        $('#penarikanAnggotaSelect').selectpicker('refresh');
        $('#penarikanBtnReset').hide();

        $('#penarikanInputModal').modal('show');
    }

    function doDelete(id){
        $('#delID').val(id);
        $('#deleteModal').modal('show');
    }

</script>