<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

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
                <a href="#!" class="btn my-btn-primary mr-2" onclick="showForm()"><i class="fas fw fa-plus mr-1"></i> Tambah baru</a>
            </div>
        </div><hr>
        <div class="table-responsive">
            <table class="table table-bordered" id="simpananTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Tahun</th>
                        <th class="text-center">Bulan</th>
                        <th class="text-center">Total Simpanan</th>
                        <th class="text-center">Nilai Ditarik</th>
                        <th class="text-center">Jenis Pernarikan</th>
                        <th class="text-center">Status</th>
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
                <h5 class="modal-title" id="inputModalLabel"><i class="mr-2 fas fa-hand-holding-usd"></i> <span id="inputModalTitle">Tambah Simpanan</span></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="formSimpanan" action="<?= site_url('simpanan/create/'.$module) ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="idSimpanan" name="id" /> 
            <div class="modal-body">
                <div class="row mb-4 mt-4">
                    <div class="col-lg-12">
                        <div class="row mb-3">
                            <div class="col-lg-3">Tanggal Bayar</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-5">
                                <input type="date" class="form-control form-control-user" id="tglDateInput" name="date" 
                                    value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">No. Anggota</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="noAnggotaTextInput" name="no_anggota" readonly="readonly">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Nama Anggota</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <select id="anggotaSelect" name="person" data-live-search="true" class="selectpicker form-control form-control-user" required>
                                    <option value="">- Please Select -</option>
                                    <?php foreach($person_list as $key => $item): ?>
                                    <option value="<?= $item["id"] ?>"><?= $item["name"] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small id="anggotaAlert" class="text-danger font-weight-bold mt-4">
                                    * Silahkan pilih anggota terlebih dahulu
                                </small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Jabatan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="jabatanTextInput" name="jabatan" readonly="readonly">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Depo/Stock Point</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="depoTextInput" name="depo" readonly="readonly">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">No. Rekening</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="noRekTextInput" name="no_rek" readonly="readonly">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Alamat</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <textarea readonly="readonly" class="form-control form-control-user" name="alamat" id="alamatTextArea"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Tipe</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-5">
                                <select class="form-control form-control-user" id="statusCombo" name="status" disabled="disabled">
                                    <option value="pokok" <?=(isset($module) && $module == 'pokok')? 'selected' : '' ?>>Simpanan Pokok</option>
                                    <option value="wajib" <?=(isset($module) && $module == 'wajib')? 'selected' : '' ?>>Simpanan Wajib</option>
                                    <option value="sukarela" <?=(isset($module) && $module == 'sukarela')? 'selected' : '' ?>>Simpanan Sukarela</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Tahun</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-5">
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
                            <div class="col-lg-5">
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
                            <div class="col-lg-3">Nominal</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-5">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Rp</div>
                                    </div>
                                    <input type="text" class="form-control" id="jumlahTextInput" name="balance" placeholder="..." value="<?= $default_nominal ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger mt-4 mb-4" data-dismiss="modal">Batal</button>
                <button type="button" id="btnReset" class="btn btn-info mt-4 mb-4" onclick="resetForm()">Reset</button>
                <button type="submit" class="btn btn-success mt-4 mb-4 ml-2 mr-4"> Submit Simpanan <i class="ml-2 fas fa-chevron-right"></i></button>
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
                <form method="GET" action="<?=site_url('simpanan/delete/').$module ?>">
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

    let dt = $('#simpananTable').DataTable({
        dom: "Bfrtip",
        ajax: {
            url: url.site + "/simpanan/get_dt_penarikan",
            type: "POST",
            data: function(d){
                d.person = person;
            },
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
                    if(row.row_no == 1){
                        return data
                    }else{
                        return ''
                    }
                }
            },
            { 
                data: "month", 
                render: function (data, type, row) {
                    return month_list[Number(data) - 1];
                }
            },
            { 
                data: "balance", 
                render: function (data, type, row) {
                    return rupiah(data)
                }
            },
            { 
                data: "withdraw", 
                render: function (data, type, row) {
                    return rupiah(data)
                }
            },
            { data: "type" },
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
        ],
        ordering: false,
    });

    $(document).ready(function() {
        $('.alert').alert()
        $('.selectpicker').selectpicker();
    });

    function showForm(){
        resetForm();
        $('#inputModalTitle').text('Tambah Simpanan');
        $('#formSimpanan').attr('action', url.site + "/simpanan/create/" + module)
        $('#btnReset').show();

        $('#inputModal').modal('show');
    }

    function resetForm() {
        $('#formSimpanan')[0].reset();
        $('#tglDateInput').val(date_now);
        $('#yearCombo').val(year_now);
        $('#monthCombo').val(month_now);
        $('#jumlahTextInput').val(default_nominal);
        $('#alamatTextArea').text("");
        $("#anggotaSelect").val('');
        $("#anggotaSelect").selectpicker('refresh');
        $('#anggotaAlert').show();
    }

    function doEdit(row){
        resetForm();
        $('#inputModalTitle').text('Ubah Simpanan');
        $('#formSimpanan').attr('action', url.site + "/simpanan/edit/" + module)
        $('#idSimpanan').val(row.id)
        $('#tglDateInput').val(row.date);
        $('#anggotaAlert').fadeOut();
        $('#anggotaSelect').val(row.person_id);
        $("#anggotaSelect").selectpicker('refresh');
        $('#noAnggotaTextInput').val(row.nik)
        $('#jabatanTextInput').val(row.position_name)
        $('#depoTextInput').val(row.depo)
        $('#alamatTextArea').text(row.address)
        $('#noRekTextInput').val(row.acc_no)
        $('#yearCombo').val(row.year);
        $('#monthCombo').val(Number(row.month));
        $('#jumlahTextInput').val(row.balance);
        $('#btnReset').hide();

        $('#inputModal').modal('show');
    }

    function doDelete(id){
        $('#delID').val(id);
        $('#deleteModal').modal('show');
    }

</script>