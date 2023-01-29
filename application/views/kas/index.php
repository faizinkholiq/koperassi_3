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
                <div class="text-lg mb-2">Total kas Anggota: <span class="text-danger ml-2">Rp1000</span></div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4 mt-2">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <a href="#!" class="btn my-btn-primary mr-2" onclick="showForm()"><i class="fas fw fa-plus mr-1"></i> Tambah Kas</a>
            </div>
        </div><hr>
        <div class="table-responsive">
            <table class="table table-bordered" id="kasTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Tahun</th>
                        <th class="text-center">Debet</th>
                        <th class="text-center">Kredit</th>
                        <th class="text-center">Total</th>
                        <th width="120" class="text-center">Aksi</th>
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
                <h5 class="modal-title" id="inputModalLabel"><i class="mr-2 fas fa-hand-holding-usd"></i> Tambah kas</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="formkas" action="<?= site_url('kas/submit_ubah_kas') ?>" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="row mb-4 mt-4">
                    <div class="col-lg-12">
                        <input id="IDTextInput" type="hidden" name="id" value="">
                        <div class="row mb-3">
                            <div class="col-lg-3">Year</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-5">
                                <select class="form-control" id="yearCombo" name="year">
                                    <?php for($i = date('Y'); $i <= date('Y')+5; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($i == date('Y'))? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
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
                                    <input type="text" class="form-control" id="nominalTextInput" name="debet" placeholder="...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger mt-4 mb-4" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success mt-4 mb-4 ml-2 mr-4"> Tambah Kas <i class="ml-2 fas fa-chevron-right"></i></button>
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
                <form method="GET" action="<?=site_url('kas/delete_ubah_kas')?>">
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

    let dt = $('#kasTable').DataTable({
        dom: "Bfrtip",
        ajax: {
            url: url.site + "/kas/get_data",
            type: "POST",
            data: function(d){
                d.person = person;
            }
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: "date" },
            { data: "year" },
            { 
                data: "debet",
                class: "text-center",
                render: function (data, type, row) {
                    return (data)? data : '-' ;
                }
            },
            { 
                data: "kredit",
                class: "text-center",
                render: function (data, type, row) {
                    return (data)? data : '-' ;
                }
            },
            { data: "total" },
            { 
                class: "text-center",
                render: function (data, type, row) {
                    return `
                        <button type="button" onclick='DoEdit(`+ JSON.stringify(row) + `)' class="btn btn-sm btn-primary" style="width: 2rem;"><i class="fas fa-edit"></i></button>
                        <button type="button" onclick="DoDelete(${row.id})" class="btn btn-sm btn-danger" style="width: 2rem;"><i class="fas fa-trash"></i></button>
                    `;
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

    function showForm(kas_id){
        resetForm();
        $('#formkas').attr('action', url.site + "/kas/create")
        $('#inputModal').modal('show');
    }
    
    function resetForm(){
        $('#formkas')[0].reset();
    }

    function DoEdit(row){
        resetForm();
        $('#formkas').attr('action', url.site + "/kas/edit")
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