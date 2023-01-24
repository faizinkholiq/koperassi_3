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
            <div class="col-lg-6 font-weight-bold border-right">
                <div class="text-lg mb-2">Plafon: <span class="text-danger ml-2">Rp<?= $summary['plafon'] ?></span></div>
                <div class="text-lg">Limit Pinjaman: <span class="text-danger ml-2">Rp<?= $summary['limit'] ?></span></div>
            </div>
            <div class="col-lg-6 font-weight-bold text-right">
                <div class="text-lg mb-2">Gaji Pokok: <span class="text-danger ml-2">Rp<?= $summary['gaji'] ?></span></div>
                <div class="text-lg">Total Simpanan: <span class="text-danger ml-2">Rp<?= $summary['simpanan'] ?></span></div>
            </div>
        </div>
    </div>
</div>
<div class="card shadow mb-4 mt-2">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12 row justify-content-end p-0">
                <select class="form-control col-lg-2 mr-4" id="selectTipe" name="tipe" onchange="selectType()">
                    <option value="all">- All Data -</option>
                    <option value="Simpanan Pokok">Simpanan Pokok</option>
                    <option value="Simpanan Wajib">Simpanan Wajib</option>
                    <option value="Simpanan Sukarela">Simpanan Sukarela</option>
                    <option value="Simpanan Investasi">Simpanan Investasi</option>
                </select>
                <select class="form-control col-lg-1" id="selectBulan" name="bulan" onchange="selectMonth()">
                    <option value="all">- All Month -</option>
                    <?php 
                    $months = ['Januari', 'Februari', 'Maret', 'April', 'May', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    foreach($months as $key => $item):
                    ?>
                    <option value="<?= $key+1 ?>"><?= $item ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="form-control col-lg-1 ml-4" id="selectTahun" name="tahun" onchange="selectYear()">
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
                        <th class="text-center">Tahun</th>
                        <th class="text-center">Bulan</th>
                        <th class="text-center">Uraian</th>
                        <th class="text-center">Jml. Simpanan</th>
                        <th class="text-center" width="80">D/K</th>
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
            <form id="formSimpanan" action="<?= site_url('simpanan/edit_temp') ?>" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="row mb-4 mt-4">
                    <div class="col-lg-12">
                        <input id="personTextInput" type="hidden" name="person_id" value="<?= $person_id; ?>">
                        <input id="simpananTextInput" type="hidden" name="simpanan_id" value="">
                        <input id="kodeTextInput" type="hidden" name="code" value="">
                        <div class="row mb-3">
                            <div class="col-lg-3">Tanggal Bayar</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-5">
                                <input type="date" class="form-control form-control-user" id="tglDateInput" name="date" 
                                    value="<?= date('Y-m-d') ?>" disabled>
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
                                <input type="text" class="form-control" id="namaAnggotaTextInput" name="nama_anggota" readonly="readonly">
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
                                <select class="form-control form-control-user" id="statusCombo" name="type" readonly="readonly">
                                    <option value="Pokok">Simpanan Pokok</option>
                                    <option value="Wajib">Simpanan Wajib</option>
                                    <option value="Sukarela" selected>Simpanan Sukarela</option>
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
                <button type="submit" class="btn btn-success mt-4 mb-4 ml-2 mr-4"> Ajukan Perubahan Simpanan <i class="ml-2 fas fa-chevron-right"></i></button>
            </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<!-- <script src="https://cdn.datatables.net/rowgroup/1.3.0/js/dataTables.rowGroup.min.js"></script> -->

<script>   
    const url = {
        "site": "<?= site_url() ?>",
        "base": "<?= base_url() ?>",
    }

    const list_anggota = <?= json_encode($person_list); ?>;
    const person = <?= $person_id ?>;
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
    let type = $('#selectTipe').val();

    let dt = $('#simpananTable').DataTable({
        dom: "Bfrtip",
        ajax: {
            url: url.site + "/simpanan/get_dt_simpanan",
            type: "POST",
            data: function(d){
                d.person = person;
                d.type = type;
                d.month = month;
                d.year = year;
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
            { data: "type" },
            { data: "balance" },
            { data: "dk", class: "text-center" },
        ],
        paging: false,
        ordering: false,
        scrollX: true,
    });

    // Call the dataTables jQuery plugin
    $(document).ready(function() {
    });

    function showForm(simpanan_id){
        resetForm();
        let simpanan = simpanan_data.filter(r => r.id == simpanan_id)

        if(simpanan.length > 0){
            
            $('#kodeTextInput').val(simpanan[0].code);
            $('#simpananTextInput').val(simpanan[0].id);
            $('#namaAnggotaTextInput').val(simpanan[0].name);
            $('#noAnggotaTextInput').val(simpanan[0].nik);
            $('#jabatanTextInput').val(simpanan[0].position_name);
            $('#depoTextInput').val(simpanan[0].depo);
            $('#alamatTextArea').text(simpanan[0].address);
            $('#noRekTextInput').val(simpanan[0].acc_no);
            $('#jumlahTextInput').val(simpanan[0].balance);
            $('#tglDateInput').val(simpanan[0].date);

            $('#inputModal').modal('show');
        }else{
            alert("ID Simpanan tidak ditemukan")
        }
    }
    
    function resetForm(){
        $('#formSimpanan')[0].reset();
        $('#alamatTextArea').text("");
    }

    function selectType(){
        type = $('#selectTipe').val();
        dt.ajax.reload();
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