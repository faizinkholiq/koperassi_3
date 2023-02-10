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
        <div class="row mb-4">
            <div class="col-lg-12 text-right">
                <a href="<?= site_url('report/export_simpanan') ?>" target="_blank" class="btn my-btn-primary mr-2"><i class="fas fw fa-file-excel mr-1"></i> Export</a>
            </div>
        </div><hr>
        <div class="table-responsive">
            <table class="table table-bordered" id="simpananTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center">NIK</th>
                        <th rowspan="2" class="text-center">Nama</th>
                        <th rowspan="2" class="text-center">Depo</th>
                        <th rowspan="2" class="text-center">Jabatan</th>
                        <th colspan="5" class="text-center">Data Simpanan</th>
                    </tr>
                    <tr>
                        <th class="text-center">Pokok</th>
                        <th class="text-center">Wajib</th>
                        <th class="text-center">Investasi</th>
                        <th class="text-center">Sukarela</th>
                        <th class="text-center">Total</th>
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

<script>
    const url = {
        base: '<?=base_url() ?>',
        site: '<?=site_url() ?>'
    };

    let month = $('#selectBulan').val();
    let year = $('#selectTahun').val();

    let dt = $('#simpananTable').DataTable({
        dom: "Bfrtip",
        ajax: {
            url: url.site + "/report/get_dt_simpanan",
            type: "POST",
            data: function(d){
                d.month = month;
                d.year = year;
            }
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: "nik" },
            { data: "name" },
            { data: "depo" },
            { data: "position" },
            { data: "pokok" },
            { data: "wajib" },
            { data: "investasi" },
            { data: "sukarela" },
            { data: "total" },
        ],
        ordering: false
    });

    $(document).ready(function() {
        $('.alert').alert()
        $('.selectpicker').selectpicker();
    });

    function selectMonth(){
        month = $('#selectBulan').val();
        dt.ajax.reload();
    }

    function selectYear(){
        year = $('#selectTahun').val();
        dt.ajax.reload();
    }

</script>