<!-- Content Row -->
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card shadow" style="width:100%; background:#e74c3c;">
            <div class="card-body font-weight-bold" style="height:7rem; color:white; font-size:2rem;">
                Simpanan Wajib
            </div>
            <div class="card-footer" style="background:#c0392b; border:none; color:white;">
                Lihat Detail
                <i class="float-right fa fa-chevron-right"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4" >
        <div class="card shadow" style="width:100%; background:#f1c40f;">
            <div class="card-body font-weight-bold" style="height:7rem; color:white; font-size:2rem;">
                Simpanan Pokok
            </div>
            <div class="card-footer" style="background:#f39c12; border:none; color:white;">
                Lihat Detail
                <i class="float-right fa fa-chevron-right"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card shadow" style="width:100%; background:#1abc9c;">
            <div class="card-body font-weight-bold" style="height:7rem; color:white; font-size:2rem;">
                Investasi
            </div>
            <div class="card-footer" style="background:#16a085; border:none; color:white;">
                Lihat Detail
                <i class="float-right fa fa-chevron-right"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4 offset-2">
        <div class="card shadow" style="width:100%; background:#3498db;">
            <div class="card-body font-weight-bold" style="height:7rem; color:white; font-size:2rem;">
                Simpanan Sukarela
            </div>
            <div class="card-footer" style="background:#2980b9; border:none; color:white;">
                Lihat Detail
                <i class="float-right fa fa-chevron-right"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card shadow" style="width:100%; background:#9b59b6;">
            <div class="card-body font-weight-bold" style="height:7rem; color:white; font-size:2rem;">
                Sisa Pinjaman
            </div>
            <div class="card-footer" style="background:#8e44ad; border:none; color:white;">
                Lihat Detail
                <i class="float-right fa fa-chevron-right"></i>
            </div>
        </div>
    </div>

</div>

<!-- Content Row -->
<div class="row">

    <div class="col-lg-12 mb-4">

        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Diri Anggota</h6>
            </div>
            <div class="card-body">
                <div class="row mb-4 mt-2">
                    <div class="col-lg-7">
                        <div class="row mb-3">
                            <div class="col-lg-3">NIK</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?= isset($nik) && !empty($nik)? $nik : "-" ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">TMK</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?= isset($tmk) && !empty($tmk)? $tmk : "-" ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Nama Lengkap</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?= isset($name) && !empty($name)? $name : "-" ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Jabatan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?= isset($position) && !empty($position)? $position_name : "-" ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Depo/Stock Point</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?= isset($depo) && !empty($depo)? $depo_name : "-" ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">No. Rekening</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?= isset($acc_no) && !empty($acc_no)? $acc_no : "-" ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Alamat</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?= isset($address) && !empty($address)? $address : "-" ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">No. Telephone</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?= isset($phone) && !empty($phone)? $phone : "-" ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Email</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?= isset($email) && !empty($email)? $email : "-" ?></div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <img class="img-ktp" src="<?= isset($ktp) && !empty($ktp)? base_url('files/').$ktp : "-" ?>" />
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>