<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

<div class="card mb-4 shadow">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6 font-weight-bold border-right">
                <div class="text-lg mb-2">Plafon: <span class="text-danger ml-2">Rp15.000.000</span></div>
                <div class="text-lg">Limit Pinjaman: <span class="text-danger ml-2">Rp10.000.000</span></div>
            </div>
            <div class="col-lg-6 font-weight-bold text-right">
                <div class="text-lg">Sisa Pinjaman: <span class="text-danger ml-2">Rp1.000.000</span></div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pinjaman</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="pinjamanTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="10">No</th>
                        <th class="text-center">Tahun</th>
                        <th class="text-center">Bulan</th>
                        <th class="text-center">Cicilan</th>
                        <th class="text-center">Bunga</th>
                        <th class="text-center">Bayar</th>
                        <th class="text-center">Sisa</th>
                        <th class="text-center">Gaji</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>2022</td>
                        <td>Januari</td>
                        <td>1.305.106</td>
                        <td>395.752</td>
                        <td>1.305.106</td>
                        <td>0</td>
                        <td>1.700.859</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>2022</td>
                        <td>Januari</td>
                        <td>1.305.106</td>
                        <td>395.752</td>
                        <td>1.305.106</td>
                        <td>0</td>
                        <td>1.700.859</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>2022</td>
                        <td>Januari</td>
                        <td>1.305.106</td>
                        <td>395.752</td>
                        <td>1.305.106</td>
                        <td>0</td>
                        <td>1.700.859</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>2022</td>
                        <td>Januari</td>
                        <td>1.305.106</td>
                        <td>395.752</td>
                        <td>1.305.106</td>
                        <td>0</td>
                        <td>1.700.859</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>2022</td>
                        <td>Januari</td>
                        <td>1.305.106</td>
                        <td>395.752</td>
                        <td>1.305.106</td>
                        <td>0</td>
                        <td>1.700.859</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>

<script>

    // Call the dataTables jQuery plugin
    $(document).ready(function() {
        $('#simpananTable').DataTable();
    });

</script>