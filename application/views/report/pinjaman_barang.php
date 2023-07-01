<div class="row">
    <div class="col-lg-12 mb-4">
        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <form action="<?= site_url('report/export_pinjaman_barang') ?>" id="exportForm" method="GET">
            <div class="card-body">
                <div class="row mb-4 mt-4" style="position: relative;">
                    <div class="col-lg-8">
                        <div class="row mb-3">
                            <div class="col-lg-3">Tgl. Cetak</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <input type="date" class="form-control col-lg-8" name="date" value="<?= date('Y-m-d') ?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" id="btnPosting" class="btn btn-primary btn-md mt-2 mb-2 ml-2 mr-4"><i class="fas fw fa-file-excel mr-1"></i> Export Rekapitulasi Pinjam Barang</button>
            </div>
            </form>
        </div>

    </div>
</div>