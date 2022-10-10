<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

<div class="card mb-4 shadow">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6 font-weight-bold border-right">
                <div class="text-lg mb-2">Plafon: <span class="text-danger ml-2">Rp15.000.000</span></div>
                <div class="text-lg">Limit Pinjaman: <span class="text-danger ml-2">Rp10.000.000</span></div>
            </div>
            <div class="col-lg-6 font-weight-bold text-right">
                <div class="text-lg mb-2">Gaji Pokok: <span class="text-danger ml-2">Rp15.000.000</span></div>
                <div class="text-lg">Total Simpanan: <span class="text-danger ml-2">Rp10.000.000</span></div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Simpanan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="simpananTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="10">No</th>
                        <th class="text-center">Bulan</th>
                        <th class="text-center">Kode Transaksi</th>
                        <th class="text-center">Uraian</th>
                        <th class="text-center">Pemasukan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Januari</td>
                        <td>2022010001</td>
                        <td>Simpanan Wajib</td>
                        <td>50.000</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Januari</td>
                        <td>2022010002</td>
                        <td>Simpanan Sukarela</td>
                        <td>44.000</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Januari</td>
                        <td>2022010003</td>
                        <td>Investasi</td>
                        <td>76.000</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Januari</td>
                        <td>2022010004</td>
                        <td>Simpanan Wajib</td>
                        <td>67.000</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Januari</td>
                        <td>2022010005</td>
                        <td>Simpanan Sukarela</td>
                        <td>150.000</td>
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