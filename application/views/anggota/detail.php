<div class="row">

    <div class="col-lg-12 mb-4">

        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <a class="my-text-primary" href="<?=site_url('anggota')?>">
                    <i class="fas fa-times float-right mr-3" style="font-size:2rem;" 
                        data-toggle="tooltip" data-placement="top" title="Kembali"></i>
                </a>
                <h3 class="font-weight-bold mt-3">
                    <i class="mr-2 fas fa-user"></i> 
                    Data Diri
                </h3>
                <div class="row mb-4 mt-4">
                    <div class="col-lg-8">
                        <div class="row mb-3">
                            <div class="col-lg-3">NIK</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?=!empty($data["nik"])? $data["nik"] : '-'?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">TMK</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?=!empty($data["tmk"])? $data["tmk"] : '-'?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Nama Lengkap</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?=!empty($data["name"])? $data["name"] : '-'?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Jabatan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?=!empty($data["position_name"])? $data["position_name"] : '-'?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Depo/Stock Point</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?=!empty($data["depo"])? $data["depo"] : '-'?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">No. Rekening</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?=!empty($data["acc_no"])? $data["acc_no"] : '-'?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Alamat</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?=!empty($data["address"])? $data["address"] : '-'?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">No. Telephone</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?=!empty($data["phone"])? $data["phone"] : '-'?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Email</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?=!empty($data["email"])? $data["email"] : '-'?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Tanggal Keanggotaan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?=!empty($data["join_date"])? $data["join_date"] : '-'?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Status Keanggotaan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?=!empty($data["status"])? $data["status"] : '-'?></div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <img class="detail-profile-photo" src="<?=base_url('files/').$data['profile_photo'] ?>" /><br/>
                        <a href="<?=base_url('files/').$data['ktp'] ?>" target="_blank" download class="btn btn-info btn-lg mt-4"><i class="mr-2 fas fa-download"></i> Download File KTP</a>
                    </div>
                </div>
                <hr/>
                <h3 class="font-weight-bold mt-4"><i class="mr-2 fas fa-users"></i> Hubungan Keluarga</h3>
                <div class="row mb-4 mt-4">
                    <div class="col-lg-8">
                        <div class="row mb-3">
                            <div class="col-lg-3">Nama</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?=!empty($data["name_family"])? $data["name_family"] : '-'?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Alamat</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?=!empty($data["address_family"])? $data["address_family"] : '-' ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">No. Telephone</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?=!empty($data["phone_family"])? $data["phone_family"] : '-' ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Status Hubungan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8"><?=!empty($data["status_family"])? $data["status_family"] : '-' ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>