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

<?php if($role == '2'): ?>
<div class="card mb-4 shadow">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6 font-weight-bold border-right">
                <div class="text-lg mb-2">Plafon: <span class="text-danger ml-2"><?= isset($summary['plafon']) && !empty($summary['plafon'])? rupiah($summary['plafon']) : 0 ?></span></div>
            </div>
            <div class="col-lg-6 font-weight-bold text-right">
                <div class="text-lg mb-2">Gaji Pokok: <span class="text-danger ml-2"><?= isset($summary['salary']) && !empty($summary['salary'])? rupiah($summary['salary']) : 0 ?></span></div>
                <div class="text-lg">Total Simpanan: <span class="text-danger ml-2"><?= isset($summary['simpanan']) && !empty($summary['simpanan'])? rupiah($summary['simpanan']) : 0 ?></span></div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="card shadow mb-4 mt-2">
    <div class="card-body">
        <?php if($role == '2'): ?>
        <div class="row">
            <div class="col-lg-6">
                <a href="#!" class="btn my-btn-primary mr-2" onclick="showForm()"><i class="fas fw fa-upload mr-1"></i> Ajukan Perubahan</a>
            </div>
        </div><hr>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table table-bordered" id="simpananTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">Tahun</th>
                        <th class="text-center">Bulan</th>
                        <th class="text-center">Jenis Simpanan</th>
                        <th class="text-center">Nama Anggota</th>
                        <th class="text-center">Nilai Awal</th>
                        <th class="text-center">Nilai Perubahan</th>
                        <th class="text-center">Status Perubahan</th>
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
                <h5 class="modal-title" id="inputModalLabel"><i class="mr-2 fas fa-hand-holding-usd"></i> Tambah Simpanan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="formSimpanan" action="<?= site_url('simpanan/submit_ubah_simpanan') ?>" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="row mb-4 mt-4">
                    <div class="col-lg-12">
                        <input id="IDTextInput" type="hidden" name="id" value="">
                        <input id="personTextInput" type="hidden" name="person" value="<?= $person_id; ?>">
                        <div class="row mb-3">
                            <div class="col-lg-3">Tanggal Bayar</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-5">
                                <input type="date" class="form-control form-control-user" id="tglDateInput" name="date" 
                                    value="<?= date('Y-m-d') ?>" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Month</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <select class="form-control" id="monthCombo" name="month">
                                    <?php
                                      $months = ['Januari', 'Februari', 'Maret', 'April', 'May', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                      foreach($months as $key => $item): ?>
                                      <option value="<?= $key+1 ?>"><?= $item ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Year</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <select class="form-control" id="yearCombo" name="year">
                                    <?php for($i = date('Y'); $i <= date('Y')+5; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($i == date('Y'))? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Tipe</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-5">
                                <select class="form-control" id="tipeCombo" name="type">
                                    <option value="Sukarela">Simpanan Sukarela</option>
                                    <option value="Investasi">Investasi</option>
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
                                    <input type="text" class="form-control" id="nominalTextInput" name="balance" placeholder="...">
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
                <form method="GET" action="<?=site_url('simpanan/delete_ubah_simpanan')?>">
                    <input type="hidden" id="delID" name="id" />
                    <button class="btn btn-danger mr-2" type="submit">Ya, Hapus</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                </form>
            </div>
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
                <form method="POST" action="<?=site_url('simpanan/approve_ubah_simpanan')?>">
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
            <form method="POST" action="<?=site_url('simpanan/reject_ubah_simpanan')?>">
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

<div class="modal fade" id="reasonModal" tabindex="-1" role="dialog" aria-labelledby="reasonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reasonModalLabel"><i class="fas fa-times mr-2"></i>Alasan data tersebut ditolak</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="reasonParagraph"></p>      
            </div>
            <div class="modal-footer">
                <input type="hidden" id="rejID" name="id" />
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>

<script>   
    const url = {
        "site": "<?= site_url() ?>",
        "base": "<?= base_url() ?>",
    };

    const person = <?= $person_id ?>;
    const role = <?= $role ?>;
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

    const rupiah = (number)=>{
        return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR"
        }).format(number);
    }

    let dt = $('#simpananTable').DataTable({
        dom: "Bfrtip",
        ajax: {
            url: url.site + "/simpanan/get_dt_ubah_simpanan",
            type: "POST",
            data: function(d){
                d.person = person;
            }
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: "year" },
            { 
                data: "month", 
                render: function (data, type, row) {
                    return month_list[Number(data) - 1];
                }
            },
            { data: "type" },
            { data: "name" },
            { 
                data: "balance", 
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
                    let btn = '-';
                    
                    if (role == 1) {
                        if (row.status == 'Pending') {
                            btn = `
                                <button type="button" onclick="DoApprove(${row.id})" class="btn btn-sm btn-success" style="width: 2rem;"><i class="fas fa-check"></i></button>
                                <button type="button" onclick="DoReject(${row.id})" class="btn btn-sm btn-danger" style="width: 2rem;"><i class="fas fa-times"></i></button>
                            `;
                        }
                    }else{
                        if (row.status == 'Pending' || row.status == 'Decline') {
                            btn = `
                                <button type="button" onclick='DoEdit(`+ JSON.stringify(row) + `)' class="btn btn-sm btn-primary" style="width: 2rem;"><i class="fas fa-edit"></i></button>
                                <button type="button" onclick="DoDelete(${row.id})" class="btn btn-sm btn-danger" style="width: 2rem;"><i class="fas fa-trash"></i></button>
                            `;
                        }
                    }

                    return btn;
                }
            },
        ],
        ordering: false,
        scrollX: true,
    });

    // Call the dataTables jQuery plugin
    $(document).ready(function() {
        if (role == 2) {
            dt.columns([3]).visible(false);
        }
    });

    function showForm(simpanan_id){
        resetForm();
        $('#formSimpanan').attr('action', url.site + "/simpanan/submit_ubah_simpanan")
        $('#inputModal').modal('show');
    }
    
    function resetForm(){
        $('#formSimpanan')[0].reset();
    }

    function DoEdit(row){
        resetForm();
        $('#formSimpanan').attr('action', url.site + "/simpanan/edit_ubah_simpanan")
        $('#IDTextInput').val(row.id);
        $('#tglDateInput').val(row.date);
        $('#monthCombo').val(row.month);
        $('#yearCombo').val(row.year);
        $('#tipeCombo').val(row.type);
        $('#nominalTextInput').val(row.balance);
        $('#inputModal').modal('show');
    }

    function DoDelete(id){
        $('#delID').val(id);
        $('#deleteModal').modal('show');
    }

    function DoApprove(id){
        $('#appID').val(id);
        $('#approveModal').modal('show');
    }

    function DoReject(id){
        $('#rejID').val(id);
        $('#rejectModal').modal('show');
    }

    function showReason(reason){
        $('#reasonParagraph').text(reason);
        $('#reasonModal').modal('show');
    }

</script>