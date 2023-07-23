<div class="row">
    <div class="col-lg-12 mb-4">
        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <form action="<?= site_url('report/export_pinjaman_uang') ?>" id="exportForm" method="GET">
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
                        <div class="row mb-3">
                            <div class="col-lg-3">Bulan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <select class="form-control col-lg-8" id="selectMonth" name="month">
                                    <?php 
                                    $months = ['Januari', 'Februari', 'Maret', 'April', 'May', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                    foreach($months as $key => $item):
                                    ?>
                                    <option value="<?= $key+1 ?>"><?= $item ?></option>
                                    <!-- <option value="<?= str_pad($key+1, 2, '0', STR_PAD_LEFT); ?>"><?= $item ?></option> -->
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-3">Tahun</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <select class="form-control col-lg-8" id="selectYear" name="year">
                                    <?php 
                                    $start = 2019;
                                    for($i = $start; $i <= date('Y'); $i++):
                                    $y = (isset($parameter['year']) && !empty($parameter['year'])) ? : date('Y');
                                    ?>
                                    <option value="<?= $i ?>" <?= ($i == $y)? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" id="btnPosting" class="btn btn-primary btn-md mt-2 mb-2 ml-2 mr-4"><i class="fas fw fa-file-excel mr-1"></i> Export Rekapitulasi Pinjam Uang</button>
            </div>
            </form>
        </div>

    </div>
</div>