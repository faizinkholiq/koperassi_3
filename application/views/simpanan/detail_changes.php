<div class="row mb-4">
    <div class="col-lg-2"></div>
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-body text-lg mt-4">
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">No. Anggota</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7">123</div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Nama</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7">123</div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Jabatan</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7">123</div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Depo / Stock Point</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7">123</div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">No. Rekening</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7">123</div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Alamat</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7">123</div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Tipe</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Tgl. Pembayaran</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= $before['date'] ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Kode Transaksi</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= $before['code'] ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Simpanan</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><span class="my-tag bg-danger text-white"><?= $before['balance'] ?></span><i class="fas fa-arrow-right mr-3 ml-3"></i><span class="my-tag bg-success text-white"><?= $after['balance'] ?></span></div>
                </div>
            </div>
            <div class="card-footer text-right mt-4">
                <button class="btn my-btn-secondary mt-2 mb-2 mr-4 font-weight-bold btn-lg shadow"><i class="fas fa-times mr-2"></i>Batal</button>
                <button class="btn my-btn-primary font-weight-bold btn-lg shadow"><i class="fas fa-check mr-2"></i>Setuju</button>
            </div>
        </div>
    </div>
</div>