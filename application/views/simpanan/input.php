<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<div class="row">

    <div class="col-lg-12 mb-4">

        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <form action="<?= site_url('simpanan/create/'.$module) ?>" method="POST" enctype="multipart/form-data">
            <div class="card-body">
                <a class="my-text-primary" href="<?=site_url('anggota')?>">
                    <i class="fas fa-times float-right mr-3" style="font-size:2rem;" 
                        data-toggle="tooltip" data-placement="top" title="Kembali"></i>
                </a>
                <h3 class="font-weight-bold mt-4"><i class="mr-2 fas fa-hand-holding-usd"></i> Tambah Simpanan</h3>
                <div class="row mb-4 mt-4">
                    <div class="col-lg-8">
                        <div class="row mb-3">
                            <div class="col-lg-3">Tipe</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <select class="form-control form-control-user" id="statusCombo" name="status" disabled="disabled">
                                    <option value="pokok" <?=(isset($module) && $module == 'pokok')? 'selected' : '' ?>>Pokok</option>
                                    <option value="wajib" <?=(isset($module) && $module == 'wajib')? 'selected' : '' ?>>Wajib</option>
                                    <option value="sukarela" <?=(isset($module) && $module == 'sukarela')? 'selected' : '' ?>>Sukarela</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Anggota</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <select data-live-search="true" class="selectpicker form-control form-control-user">
                                    <?php foreach($person_list as $key => $item): ?>
                                    <option value="<?= $item["id"] ?>"><?= $item["name"] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Tanggal</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <input type="date" class="form-control form-control-user" id="tglAnggotaDateInput" name="tgl_anggota" 
                                    value="<?=(isset($data["join_date"]) && !empty($data["join_date"]))? $data["join_date"] : date('Y-m-d') ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Jumlah</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Rp</div>
                                    </div>
                                    <input type="text" class="form-control" id="jumlahTextInput" placeholder="...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a href="<?=site_url('anggota') ?>" class="btn btn-secondary btn-lg mt-4 mb-4">Kembali</a>
                <button type="submit" class="btn btn-primary btn-lg mt-4 mb-4 ml-2 mr-4"> Submit Simpanan <i class="ml-2 fas fa-chevron-right"></i></button>
            </div>
            </form>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script>
    $(function () {
        $('.selectpicker').selectpicker();
    });
</script>