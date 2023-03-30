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

<div id="alertSuccess" class="alert alert-success alert-dismissible fade show" role="alert" style="display:none">
    <strong id="msgSuccess">Proses posting berhasil !</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div id="alertFailed" class="alert alert-danger alert-dismissible fade show" role="alert" style="display:none">
    <strong id="msgFailed">Proses posting Gagal !</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="card mb-4 shadow">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6 font-weight-bold border-right">
                <div class="text-lg mb-2">Total Simpanan: <span class="text-danger ml-2" id="totalSimpanan"></span></div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <a href="#!" class="btn my-btn-primary mr-2" onclick="showForm()"><i class="fas fw fa-plus mr-1"></i> Tambah baru</a>
                <a href="#!" class="btn btn-danger" onclick="showImportForm()"><i class="fas fw fa-file-import mr-1"></i> Import Data</a>
            </div>
            <div class="col-lg-6 row justify-content-end p-0">
                <select class="form-control col-lg-3" id="selectBulan" name="bulan" onchange="selectMonth()">
                    <option value="all">- All Month -</option>
                    <?php 
                    $months = ['Januari', 'Februari', 'Maret', 'April', 'May', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    $parameter['month'] = isset($parameter['month']) && !empty($parameter['month']) ? $parameter['month'] : '';
                    foreach($months as $key => $item):
                    ?>
                    <option value="<?= $key+1 ?>" <?= ($key + 1  == $parameter['month'])? 'selected' : '' ?>><?= $item ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="form-control col-lg-3 ml-4" id="selectTahun" name="tahun" onchange="selectYear()">
                    <?php 
                    $start = 2019;
                    $parameter['year'] = isset($parameter['year']) && !empty($parameter['year']) ? $parameter['year'] : date('Y');
                    for($i = $start; $i <= date('Y'); $i++):
                    ?>
                    <option value="<?= $i ?>" <?= ($i == $parameter['year'])? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div><hr>
        <div class="table-responsive">
            <table class="table table-bordered" id="simpananTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">Tahun</th>
                        <th class="text-center">Bulan</th>
                        <th class="text-center">KTP</th>
                        <th class="text-center">NIK</th>
                        <th class="text-center">Nama Anggota</th>
                        <th class="text-center">No. Telp</th>
                        <th class="text-center">Tgl. Keanggotaan</th>
                        <th class="text-center">Jml. Simpanan</th>
                        <th class="text-center">D/K</th>
                        <th class="text-center">Aksi</th>
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
                                    <input type="text" class="form-control" id="jumlahTextInput" name="balance" placeholder="...">
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
                            <div class="mb-4 font-weight-bold text-lg">Mohon tunggu sebentar, proses posting sedang berjalan</div>
                            <div class="line bg-danger"></div>
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-4">
                        Silahkan pilih file yang akan di import, sesuai dengan template ini: <a href="<?= base_url('files/template/') ?>template_data_simpanan.csv" target="blank">template.csv</a>
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
    const module = '<?= $module ?>';
    const date_now = '<?= date('Y-m-d') ?>';
    const year_now = '<?= date('Y') ?>';
    const month_now = '<?= (int)date('m') ?>';

    let month = $('#selectBulan').val();
    let year = $('#selectTahun').val();

    const rupiah = (number)=>{
        return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR"
        }).format(number);
    }

    let dt = $('#simpananTable').DataTable({
        dom: "Bfrtip",
        ajax: {
            url: url.site + "/simpanan/get_dt_simpanan_sukarela",
            type: "POST",
            data: function(d){
                d.month = month;
                d.year = year;
            }
        },
        drawCallback: function(settings) {
            if(settings.json.data.length > 0){
                // let total = settings.json.data.map(item => Number(item.balance)).reduce((acc, amount) => acc + amount);
                let total = parseFloat(settings.json.total);
                
                $('#totalSimpanan').text((total)? rupiah(total) : 0);
            }else{
                $('#totalSimpanan').text(0);
            }
        },
        processing: true,
        serverSide: true,
        columns: [
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
            { data: "no_ktp" },
            { data: "nik" },
            { data: "name" },
            { data: "phone" },
            { data: "join_date" },
            { 
                data: "balance", 
                render: function (data, type, row) {
                    return rupiah(data)
                }
            },
            { data: "dk", class: "text-center" },
            { 
                class: "text-center",
                render: function (data, type, row) {
                    if (row.posting == 1) {
                        return '-'
                    }else{
                        return `
                            <button type="button" onclick='doEdit(`+ JSON.stringify(row) + `)' class="btn btn-sm btn-primary" style="width: 2rem;"><i class="fas fa-edit"></i></button>
                            <button type="button" onclick="doDelete(${row.id})" class="btn btn-sm btn-danger" style="width: 2rem;"><i class="fas fa-trash"></i></button>
                        `;
                    }
                }
            },
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
                $('#jumlahTextInput').val(person[0].sukarela)
            }else{
                $('#anggotaAlert').fadeIn();
                $('#formSimpanan')[0].reset();
                $('#alamatTextArea').text("")
            }
        });

        $("#formImport").submit(function (event) {
            event.preventDefault();
            showLoad();
            $.ajax({
                type: "POST",
                url: url.site + "/simpanan/import/" + module,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
            }).done(function (data) {
                setTimeout(()=>{
                    hideLoad();
                    data = JSON.parse(data);
                    if (data.success) {
                        alerts('success', data.message);
                    }else{
                        alerts('failed', data.error);
                    }
                    $('#importModal').modal('hide');
                    dt.ajax.reload();
                }, 1000);
            }).fail(function() {
                setTimeout(()=>{
                    hideLoad();
                    alerts('failed', "Proses import gagal !");
                    $('#importModal').modal('hide');
                }, 1000);
            });
        });

    });

    function showLoad(){
        $('.loading').css('display', 'flex');
        $('#btnImport').addClass('disabled');
    }

    function hideLoad(){
        $('.loading').css('display', 'none');
        $('#btnImport').removeClass('disabled');
    }

    function alerts(type, msg){
        switch (type) {
            case "success":
                $('#msgSuccess').text(msg);
                $('#alertSuccess').fadeIn();
                setTimeout(()=>{
                    $('#alertSuccess').fadeOut();
                }, 2000);
                break;
            case "failed":
                $('#msgFailed').text(msg);
                $('#alertFailed').fadeIn();
                setTimeout(()=>{
                    $('#alertFailed').fadeOut();
                }, 2000);
                break;
        }
    }

    function selectMonth(){
        month = $('#selectBulan').val();
        dt.ajax.reload();
    }

    function selectYear(){
        year = $('#selectTahun').val();
        dt.ajax.reload();
    }

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
        $('#jumlahTextInput').val("");
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

    function showImportForm(){
        $('#importModal').modal('show');
    }

</script>