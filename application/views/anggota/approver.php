<div class="row mb-4">
    <div class="col-lg-2"></div>
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-body text-lg mt-4">
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">NIK</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($data['nik']) && !empty($data['nik']))? $data['nik'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">TMK</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($data['tmk']) && !empty($data['tmk']))? $data['tmk'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Nama Lengkap</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($data['name']) && !empty($data['name']))? $data['name'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Alamat</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($data['acc_no']) && !empty($data['acc_no']))? $data['acc_no'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">No. Telephone</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($data['phone']) && !empty($data['phone']))? $data['phone'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Email</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($data['email']) && !empty($data['email']))? $data['email'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Depo / Stock Point</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($data['depo']) && !empty($data['depo']))? $data['depo'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">No. Rekening</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($data['acc_no']) && !empty($data['acc_no']))? $data['acc_no'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">KTP</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7">
                        <?php if (isset($data['ktp']) && !empty($data['ktp'])): ?>
                        <div id="card_ktp" class="card shadow mt-2" style="height: 30vh; width: 47vh;">
                            <div class="card-body" style="display: flex; justify-content: space-between;">
                                <img src="<?= base_url('files/').$data["ktp"] ?>" 
                                    style="
                                        max-width: 89%;
                                        max-height: 100%;
                                        width: -webkit-fill-available;
                                        height: -webkit-fill-available;
                                        object-fit: contain;
                                    "/>
                            </div>
                        </div>
                        <?php else: '-' ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-footer mt-4">
                <div class="row">
                    <div class="col-lg-6">
                        <a href="<?=site_url('user/notifications') ?>" class="btn my-btn-secondary mt-2 mb-2 mr-4 font-weight-bold btn-lg shadow">Batal</a>
                    </div>
                    <?php if($data && $role == 1): ?>
                    <div class="col-lg-6 text-right">
                        <button class="btn btn-danger font-weight-bold btn-lg mr-4 shadow"><i class="fas fa-times mr-2"></i>Tolak</button>
                        <button class="btn my-btn-primary font-weight-bold btn-lg shadow"><i class="fas fa-check mr-2"></i>Setuju</button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>