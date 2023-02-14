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
        <!-- <div class="row">
            <div class="col-lg-6">
                <a href="#!" class="btn my-btn-primary mr-2" onclick="showForm()"><i class="fas fw fa-plus mr-1"></i> Tambah baru</a>
            </div>
            <div class="col-lg-6 row justify-content-end p-0">
                <select class="form-control col-lg-3 ml-4" id="selectTahun" name="tahun" onchange="selectSimpanan()">
                    <option value="pokok">Pokok</option>
                    <option value="wajib">Wajib</option>
                    <option value="sukarela">Sukarela</option>
                    <option value="investasi">Investasi</option>
                </select>
            </div>
        </div><hr> -->
        <div class="table-responsive">
            <table class="table table-bordered" id="simpananTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Tahun</th>
                        <th class="text-center">Bulan</th>
                        <th class="text-center">Nama Anggota</th>
                        <th class="text-center">Total Simpanan</th>
                        <th class="text-center">Nilai Ditari</th>
                        <th class="text-center">Jenis Pernarikan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
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
            { data: "name" },
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
        ordering: false,
    });

    $(document).ready(function() {
        $('.alert').alert()
        $('.selectpicker').selectpicker();
    });

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