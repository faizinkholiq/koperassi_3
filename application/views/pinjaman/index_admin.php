<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

<?php
    $arr_params = http_build_query($_GET);

    $status_anggaran = isset($_GET['status_anggaran'])? $_GET['status_anggaran'] : '';
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

<div id="alertSuccess" class="alert alert-success alert-dismissible fade show" role="alert" style="display:none">
    <strong id="msgSuccess">Proses import berhasil !</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div id="alertFailed" class="alert alert-danger alert-dismissible fade show" role="alert" style="display:none">
    <strong id="msgFailed">Proses import Gagal !</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="row mb-3">
    <div class="col-lg-9" style="display:flex; align-items:center; gap: 1rem;">
        <div style="display:flex; align-items:center;">
            <a href="<?= site_url('pinjaman') ?>" class="btn font-weight-bold <?= count($_GET) == 0? 'btn-primary' : 'btn-secondary' ?>">All</a>
            <a href="<?= site_url('pinjaman').(!empty($status)? '?status='.$status.'&' : '?') ?>status_anggaran=Lunas" class="btn ml-1 font-weight-bold <?= ($status_anggaran == 'Lunas')? 'btn-primary' : 'btn-secondary' ?>">Lunas</a>
            <a href="<?= site_url('pinjaman').(!empty($status)? '?status='.$status.'&' : '?') ?>status_anggaran=Belum Lunas" class="btn ml-1 font-weight-bold <?= ($status_anggaran == 'Belum Lunas')? 'btn-primary' : 'btn-secondary' ?>">Belum Lunas</a>
        </div>
        <div style="display:flex; align-items:center;">
            <a href="<?= site_url('pinjaman').(!empty($status_anggaran)? '?status_anggaran='.$status_anggaran.'&' : '?') ?>status=Approved" class="btn ml-1 font-weight-bold <?= ($status == 'Approved')? 'btn-primary' : 'btn-success' ?>">Disetujui</a>
            <a href="<?= site_url('pinjaman').(!empty($status_anggaran)? '?status_anggaran='.$status_anggaran.'&' : '?') ?>status=Pending" class="btn ml-1 font-weight-bold <?= ($status == 'Pending')? 'btn-primary' : 'btn-warning' ?>">Pending</a> 
        </div>
    </div>
    <div class="col-lg-3 text-right">
        <button type="button" class="btn btn-primary mr-2" onclick="showImportForm()"><i class="fas fw fa-file-import mr-1"></i> Import Data Pelunasan</button>
        <a onclick="doExport()" class="btn font-weight-bold bg-success text-white"><i class="fas fa-file-excel mr-2"></i>Template Transfer</a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered display nowrap" id="pinjamanTable" cellspacing="0">
                <thead>
                    <tr>
                        <th width="100" class="text-center">NIK</th>
                        <th width="200" class="text-center">Nama</th>
                        <th width="120" class="text-center">Depo</th>
                        <th width="120" class="text-center">Pengajuan</th>
                        <th width="120" class="text-center">Wajib</th>
                        <th width="120" class="text-center">Investasi</th>
                        <th width="120" class="text-center">Sukarela</th>
                        <th width="120" class="text-center">Gaji Pokok</th>
                        <th width="120" class="text-center">Plafon</th>
                        <th width="120" class="text-center">Realisasi</th>
                        <th width="80" class="text-center">Jml. Angsuran</th>
                        <th width="100" class="text-center">Status</th>
                        <th width="100" class="text-center">Status Angsuran</th>
                        <th width="80" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel"><i class="fas fa-check mr-2"></i>Setujui Peminjaman</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" action="<?=site_url('pinjaman/approve')?>">
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <td class="font-weight-bold" width="30%">Pengajuan :</td>
                            <td width="70%" id="appPengajuan">-</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Realisasi :</td>
                            <td id="appReal">
                                <div class="input-group mb-2 col-md-8">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Rp</div>
                                    </div>
                                    <input type="text" class="form-control" id="realTextInput" name="real" placeholder="..." required>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="appID" name="id" />
                    <button class="btn btn-success mr-2" type="submit">Ya, Setuju</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel"><i class="fas fa-times mr-2"></i>Tolak Peminjaman</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" action="<?=site_url('pinjaman/reject')?>">
                <div class="modal-body">
                    <strong>Apakah anda yakin ingin menolak peminjaman tersebut?</strong><br/>        
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

<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel"><i class="fas fa-file-excel mr-2"></i> Cetak Template Transfer</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="GET" action="<?=site_url('pinjaman/export_template')?>">
                <div class="modal-body">
                    <div class="row mb-4 mt-4">
                        <div class="col-lg-12">
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
                                    <option value="">- All -</option>
                                    <?php 
                                    $months = ['Januari', 'Februari', 'Maret', 'April', 'May', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                    foreach($months as $key => $item):
                                    ?>
                                    <option value="<?= $key+1 ?>" <?= ($key+1 == date('m'))? 'selected' : '' ?>><?= $item ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary mr-2" type="submit"><i class="fas fa-print mr-2"></i> Cetak</button>
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
                <h5 class="modal-title" id="importModalLabel"><i class="fas fa-file-import mr-2"></i>Import Data Angsuran</h5>
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
                        Silahkan pilih file yang akan di import, mohon gunakan template ini: <a href="<?= site_url("pinjaman/export_template_pelunasan") ?>" target="blank">template.csv</a>
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
    let status_anggaran = '<?= $status_anggaran ?>';

    const rupiah = (number)=>{
        return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR"
        }).format(number);
    }

    $(document).ready(function() {

        $("#formImport").submit(function (event) {
            event.preventDefault();
            showLoad();
            $.ajax({
                type: "POST",
                url: url.site + "/pinjaman/import_pelunasan",
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
                    
                    setTimeout(()=>{
                        location.reload()                        
                    }, 500)

                }, 1000);
            }).fail(function() {
                setTimeout(()=>{
                    hideLoad();
                    alerts('failed', "Proses import gagal !");
                    $('#importModal').modal('hide');
                    $("#formImport")[0].reset(); 
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

    let dt = $('#pinjamanTable').DataTable({
        dom: "Bfrtip",
        ajax: {
            url: url.site + "/pinjaman/get_dt_all",
            type: "POST",
            data: {
                status_anggaran: status_anggaran,
                status: status,
            }
        },
        drawCallback: function(settings) {
        },
        scrollX: true,
        processing: true,
        serverSide: true,
        columns: [
            { data: "nik" },
            { data: "name" },
            { data: "depo" },
            { 
                data: "pengajuan",
                class: "text-center",
                render: function (data, type, row) {
                    let num = parseFloat(data??0)
                    return (data > 0)? `<span class='bg-danger text-white font-weight-bold px-2 py-1 rounded'>${rupiah(num)}</span>` : '-';
                }
            },
            { 
                data: "wajib",
                class: "text-center",
                render: function (data, type, row) {
                    let num = parseFloat(data??0)
                    return (data > 0)? rupiah(num) : '-';
                }
            },
            { 
                data: "investasi",
                class: "text-center",
                render: function (data, type, row) {
                    let num = parseFloat(data??0)
                    return (data > 0)? rupiah(num) : '-';
                }
            },
            { 
                data: "sukarela", 
                class: "text-center",
                render: function (data, type, row) {
                    let num = parseFloat(data??0)
                    return (data > 0)? rupiah(num) : '-';
                }
            },
            { 
                data: "gaji",
                class: "text-center",
                render: function (data, type, row) {
                    let num = parseFloat(data??0)
                    return (data > 0)? rupiah(num) : '-';
                }
            },
            { 
                data: "plafon",
                class: "text-center",
                render: function (data, type, row) {
                    let num = parseFloat(data??0)
                    return (data > 0)? rupiah(num) : '-';
                }
            },
            { 
                data: "realisasi",
                class: "text-center",
                render: function (data, type, row) {
                    let num = parseFloat(data??0)
                    return (data > 0)? `<span class='bg-success text-white font-weight-bold px-2 py-1 rounded'>${rupiah(num)}</span>` : '-';
                }
            },
            { data: "angsuran", class: "text-center" },
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
                    if (row.status == 'Pending') {
                        btn = `
                            <button type="button" onclick='doApprove(`+ JSON.stringify(row) + `)' class="btn btn-sm btn-success" style="width: 2rem;"><i class="fas fa-check"></i></button>
                            <button type="button" onclick="doReject(${row.id})" class="btn btn-sm btn-danger" style="width: 2rem;"><i class="fas fa-times"></i></button>
                        `;
                    }else if (row.status == 'Approved'){
                        btn = `<a href="${url.site}/pinjaman/detail/${row.id}" class="btn btn-sm btn-primary" style="width: 2rem;"><i class="fas fa-edit"></i></a>`
                    }

                    return btn;
                }
            },
        ],
        ordering: false,
    });

    // Call the dataTables jQuery plugin
    $(document).ready(function() {
    });

    function doApprove(row){
        let num = parseFloat(row.pengajuan??0)
        let span_pengajuan = (row.pengajuan > 0)? `<span class='ml-3 bg-success text-white font-weight-bold px-2 py-1 rounded'>${rupiah(num)}</span>` : '-';
        $('#appID').val(row.id);
        $('#appPengajuan').html(span_pengajuan);
        $('#approveModal').modal('show');
    }

    function doReject(id){
        $('#rejID').val(id);
        $('#rejectModal').modal('show');
    }

    function doExport(){
        $('#exportModal').modal('show');
    }

    function showImportForm(){
        $('#importModal').modal('show');
    }
</script>