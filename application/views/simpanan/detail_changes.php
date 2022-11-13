<div class="row mb-4">
    <div class="col-lg-2"></div>
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-body text-lg mt-4">
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">No. Anggota</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($before['tmk']) && !empty($before['tmk']))? $before['tmk'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Nama</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($before['name']) && !empty($before['name']))? $before['name'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Jabatan</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($before['position']) && !empty($before['position']))? $before['position'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Depo / Stock Point</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($before['depo']) && !empty($before['depo']))? $before['depo'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">No. Rekening</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($before['acc_no']) && !empty($before['acc_no']))? $before['acc_no'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Alamat</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($before['acc_no']) && !empty($before['acc_no']))? $before['acc_no'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Tipe</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= $type ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Kode Transaksi</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= $before['code'] ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Tgl. Pembayaran</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= $before['date'] ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Simpanan</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><span class="my-tag bg-danger text-white"><?= $before['balance'] ?></span><i class="fas fa-arrow-right mr-3 ml-3"></i><span class="my-tag bg-success text-white"><?= $after['balance'] ?></span></div>
                </div>
            </div>
            <div class="card-footer mt-4">
                <div class="row">
                    <div class="col-lg-6">
                        <a href="<?=site_url('user/notifications') ?>" class="btn my-btn-secondary mt-2 mb-2 mr-4 font-weight-bold btn-lg shadow">Batal</a>
                    </div>
                    <div class="col-lg-6 text-right">
                        <button class="btn btn-danger font-weight-bold btn-lg mr-4 shadow"><i class="fas fa-times mr-2"></i>Tolak</button>
                        <button class="btn my-btn-primary font-weight-bold btn-lg shadow"><i class="fas fa-check mr-2"></i>Setuju</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>