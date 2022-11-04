<div class="row">

    <div class="col-lg-12 mb-4">

        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <form action="<?=(isset($data))? site_url('anggota/edit/'.$data["id"]) : site_url('anggota/create') ?>" method="POST" enctype="multipart/form-data">
            <div class="card-body">
                <a class="my-text-primary" href="<?=site_url('anggota')?>">
                    <i class="fas fa-times float-right mr-3" style="font-size:2rem;" 
                        data-toggle="tooltip" data-placement="top" title="Kembali"></i>
                </a>
                <h4 class="font-weight-bold mt-4"><i class="mr-2 fas fa-user"></i> Data Diri</h4>
                <div class="row mb-4 mt-4">
                    <div class="col-lg-8">
                        <div class="row mb-3">
                            <div class="col-lg-3">NIK</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <input type="text" class="form-control form-control-user" id="nikTextInput" name="nik" placeholder="NIK" 
                                    value="<?=(isset($data["nik"]) && !empty($data["nik"]))? $data["nik"] : '' ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">TMK</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <input type="text" class="form-control form-control-user" id="tmkTextInput" name="tmk" placeholder="TMK"
                                    value="<?=(isset($data["tmk"]) && !empty($data["tmk"]))? $data["tmk"] : '' ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Nama Lengkap</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <input type="text" class="form-control form-control-user" id="namaTextInput" name="nama" placeholder="Nama"
                                    value="<?=(isset($data["name"]) && !empty($data["name"]))? $data["name"] : '' ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Alamat</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <textarea class="form-control form-control-user" name="alamat" id="alamatTextArea"><?=(isset($data["address"]) && !empty($data["address"]))? $data["address"] : '' ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">No. Telephone</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <input type="text" class="form-control form-control-user" id="noTelpTextInput" name="no_telp" placeholder="No. Telephone"
                                    value="<?=(isset($data["phone"]) && !empty($data["phone"]))? $data["phone"] : '' ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Email</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <input type="email" class="form-control form-control-user" id="emailTextInput" name="email" placeholder="Email"
                                    value="<?=(isset($data["email"]) && !empty($data["email"]))? $data["email"] : '' ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Tanggal Keanggotaan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <input type="date" class="form-control form-control-user" id="tglAnggotaDateInput" name="tgl_anggota" 
                                    value="<?=(isset($data["join_date"]) && !empty($data["join_date"]))? $data["join_date"] : date('Y-m-d') ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Status Keanggotaan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <select class="form-control form-control-user" id="statusCombo" name="status">
                                    <option value="Aktif" <?=(isset($data["status"]) && $data["status"] == 'Aktif')? 'selected' : '' ?>>Aktif</option>
                                    <option value="Tidak Aktif" <?=(isset($data["status"]) && $data["status"] == 'Tidak Aktif')? 'selected' : '' ?>>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">KTP</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <input type="file" class="form-control" id="ktpFile" name="ktp">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Foto Profil</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <input type="file" class="form-control" id="profileFile" name="profile_photo">
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <h4 class="font-weight-bold mt-4"><i class="mr-2 fas fa-users"></i> Hubungan Keluarga</h4>
                <div class="row mb-4 mt-4">
                    <div class="col-lg-8">
                        <div class="row mb-3">
                            <div class="col-lg-3">Nama</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <input type="text" class="form-control form-control-user" id="namaKelTextInput" name="nama_kel" placeholder="Nama Anggota Keluarga"
                                value="<?=(isset($data["name_family"]) && !empty($data["name_family"]))? $data["name_family"] : '' ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Alamat</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <textarea class="form-control form-control-user" id="alamatKelTextArea" name="alamat_kel"><?=(isset($data["address_family"]) && !empty($data["address_family"]))? $data["address_family"] : '' ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">No. Telephone</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <input type="text" class="form-control form-control-user" id="noTelpKelTextInput" name="no_telp_kel" placeholder="No. Telp"
                                value="<?=(isset($data["phone_family"]) && !empty($data["phone_family"]))? $data["phone_family"] : '' ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Status Hubungan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <select class="form-control form-control-user" id="statusHubunganCombo" name="status_kel">
                                    <option value="">- Pilih Salah Satu -</option>
                                    <option value="Istri" <?=(isset($data["status_family"]) && $data["status_family"] == 'Istri')? 'selected' : '' ?>>Istri</option>
                                    <option value="Orangtua" <?=(isset($data["status_family"]) && $data["status_family"] == 'Orangtua')? 'selected' : '' ?>>Orangtua (Ayah/Ibu)</option>
                                    <option value="Saudara" <?=(isset($data["status_family"]) && $data["status_family"] == 'Saudara')? 'selected' : '' ?>>Saudara (Kakak/Adik)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a href="<?=site_url('anggota') ?>" class="btn btn-secondary mt-4 mb-4">Kembali</a>
                <button type="submit" class="btn btn-primary mt-4 mb-4 ml-2 mr-4">Simpan Data Anggota <i class="ml-2 fas fa-chevron-right"></i></button>
            </div>
            </form>
        </div>

    </div>
</div>