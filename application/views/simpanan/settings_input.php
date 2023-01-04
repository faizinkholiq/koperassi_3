<div class="row">

    <div class="col-lg-12 mb-4">

        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <form action="<?=(isset($data))? site_url('simpanan/edit_settings/'.$data["id"]) : site_url('simpanan/create_settings') ?>" method="POST" enctype="multipart/form-data">
            <div class="card-body">
                <div class="row mb-4 mt-4">
                    <div class="col-lg-8">
                        <div class="row mb-3">
                            <div class="col-lg-3">Simpanan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                            <select class="form-control form-control-user" id="simpananCombo" name="simpanan" required>
                                <option value="">- Pilih Salah Satu -</option>
                                <option value="Pokok" <?=(isset($data["simpanan"]) && $data["simpanan"] == 'Pokok')? 'selected' : '' ?>>Pokok</option>
                                <option value="Wajib" <?=(isset($data["simpanan"]) && $data["simpanan"] == 'Wajib')? 'selected' : '' ?>>Wajib</option>
                            </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Nominal</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <input type="text" class="form-control form-control-user" id="nominalTextInput" name="nominal" placeholder="Rp." 
                                    value="<?=(isset($data["nominal"]) && !empty($data["nominal"]))? $data["nominal"] : '' ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a href="<?=site_url('simpanan/settings') ?>" class="btn btn-secondary btn-md mt-2 mb-2">Kembali</a>
                <button type="submit" class="btn btn-primary btn-md mt-2 mb-2 ml-2 mr-4">Simpan Data <i class="ml-2 fas fa-chevron-right"></i></button>
            </div>
            </form>
        </div>

    </div>
</div>