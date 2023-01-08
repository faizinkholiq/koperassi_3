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
                <a href="#!" class="btn btn-danger"><i class="fas fw fa-file-import mr-1"></i> Import Data</a>
            </div>
            <div class="col-lg-6 row justify-content-end p-0">
                <select class="form-control col-lg-3" id="selectBulan" name="bulan" onchange="selectMonth()">
                    <option value="all">- All Data -</option>
                    <?php 
                    $months = ['Januari', 'Februari', 'Maret', 'April', 'May', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    foreach($months as $key => $item):
                    ?>
                    <option value="<?= $key+1 ?>"><?= $item ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="form-control col-lg-3 ml-4" id="selectTahun" name="tahun" onchange="selectYear()">
                    <?php 
                    $start = 2019;
                    for($i = $start; $i <= date('Y'); $i++):
                    ?>
                    <option value="<?= $i ?>" <?= ($i == date('Y'))? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div><hr>
        <div class="table-responsive">
            <table class="table table-bordered" id="simpananTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="10">No</th>
                        <th class="text-center">Tahun</th>
                        <th class="text-center">Bulan</th>
                        <th class="text-center">KTP</th>
                        <th class="text-center">NIK</th>
                        <th class="text-center">Nama Anggota</th>
                        <th class="text-center">No. Telp</th>
                        <th class="text-center">Tgl. Keanggotaan</th>
                        <th class="text-center">Jml. Simpanan</th>
                        <th class="text-center">DK</th>
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
                <h5 class="modal-title" id="inputModalLabel"><i class="mr-2 fas fa-hand-holding-usd"></i> Tambah Simpanan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="formSimpanan" action="<?= site_url('simpanan/create/'.$module) ?>" method="POST" enctype="multipart/form-data">
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
                <button type="button" class="btn btn-info mt-4 mb-4" onclick="resetForm()">Reset</button>
                <button type="submit" class="btn btn-success mt-4 mb-4 ml-2 mr-4"> Submit Simpanan <i class="ml-2 fas fa-chevron-right"></i></button>
            </div>
            </form>
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

    let month = $('#selectBulan').val();
    let year = $('#selectTahun').val();

    let dt = $('#simpananTable').DataTable({
        dom: "Bfrtip",
        ajax: {
            url: url.site + "/simpanan/get_dt_simpanan_pokok",
            type: "POST",
            data: function(d){
                d.month = month;
                d.year = year;
            }
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: "row_no" },
            { data: "year" },
            { 
                data: "month", 
                render: function (data, type, row) {
                    return month_list[Number(data) - 1];
                }
            },
            { data: "no_ktp" },
            { data: "nik" },
            { data: "name" },
            { data: "phone" },
            { data: "join_date" },
            { data: "balance" },
            { data: "dk" },
        ],
        ordering: false
    });

    $(document).ready(function() {
        $('.alert').alert()
        $('.selectpicker').selectpicker();

        $("#anggotaSelect").change(function () {
            let person_id = this.value;
            let person = list_anggota.filter(r => r.id == person_id)

            if(person.length > 0){
                $('#anggotaAlert').fadeOut();
                $('#noAnggotaTextInput').val(person[0].nik)
                $('#jabatanTextInput').val(person[0].position_name)
                $('#depoTextInput').val(person[0].depo)
                $('#alamatTextArea').text(person[0].address)
                $('#noRekTextInput').val(person[0].acc_no)
            }else{
                $('#anggotaAlert').fadeIn();
                $('#formSimpanan')[0].reset();
                $('#alamatTextArea').text("")
            }
        });

    });

    function showForm(){
        $('#inputModal').modal('show');
        $('#formSimpanan')[0].reset();
        $('#alamatTextArea').text("");
        $("#anggotaSelect").val('');
        $("#anggotaSelect").selectpicker('refresh');
        $('#anggotaAlert').show();
    }

    function resetForm() {
        $('#formSimpanan')[0].reset();
        $('#alamatTextArea').text("");
        $("#anggotaSelect").val('');
        $("#anggotaSelect").selectpicker('refresh');
        $('#anggotaAlert').show();
    }

    function selectMonth(){
        month = $('#selectBulan').val();
        dt.ajax.reload();
    }

    function selectYear(){
        year = $('#selectTahun').val();
        dt.ajax.reload();
    }

</script>