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
                <h5 class="font-weight-bold mt-2"><i class="mr-2 fas fa-user"></i> Data Diri</h5>
                <div class="row mb-4 mt-4">
                    <div class="col-lg-8">
                        <div class="row mb-3">
                            <div class="col-lg-3">NIK</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control form-control-user" id="nikTextInput" name="nik" placeholder="NIK" 
                                    value="<?=(isset($data["nik"]) && !empty($data["nik"]))? $data["nik"] : '' ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">TMK</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control form-control-user" id="tmkTextInput" name="tmk" placeholder="TMK"
                                    value="<?=(isset($data["tmk"]) && !empty($data["tmk"]))? $data["tmk"] : '' ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Nama Lengkap</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control form-control-user" id="namaTextInput" name="nama" placeholder="Nama"
                                    value="<?=(isset($data["name"]) && !empty($data["name"]))? $data["name"] : '' ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Alamat</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8">
                                <textarea class="form-control form-control-user" name="alamat" id="alamatTextArea" rows="5"><?=(isset($data["address"]) && !empty($data["address"]))? $data["address"] : '' ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">No. Telephone</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control form-control-user" id="noTelpTextInput" name="no_telp" placeholder="No. Telephone"
                                    value="<?=(isset($data["phone"]) && !empty($data["phone"]))? $data["phone"] : '' ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Email</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="email" class="form-control form-control-user" id="emailTextInput" name="email" placeholder="Email"
                                    value="<?=(isset($data["email"]) && !empty($data["email"]))? $data["email"] : '' ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Tanggal Keanggotaan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="date" class="form-control form-control-user" id="tglAnggotaDateInput" name="tgl_anggota" 
                                    value="<?=(isset($data["join_date"]) && !empty($data["join_date"]))? $data["join_date"] : date('Y-m-d') ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Status Keanggotaan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <select class="form-control form-control-user" id="statusCombo" name="status">
                                    <option value="Aktif" <?=(isset($data["status"]) && $data["status"] == 'Aktif')? 'selected' : '' ?>>Aktif</option>
                                    <option value="Tidak Aktif" <?=(isset($data["status"]) && $data["status"] == 'Tidak Aktif')? 'selected' : '' ?>>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Jabatan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <select class="form-control form-control-user" id="positionCombo" name="position">
                                    <?php foreach($list_position as $key => $item): ?>
                                    <option value="<?= $item["id"] ?>" <?=(isset($data["position"]) && $data["position"] == $item["id"])? 'selected' : '' ?>><?= $item["name"] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Depo/Stock Point</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control form-control-user" id="depoTextInput" name="depo" placeholder="Depo/Stock Point"
                                    value="<?=(isset($data["depo"]) && !empty($data["depo"]))? $data["depo"] : '' ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">No. Rekening</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control form-control-user" id="accNoTextInput" name="acc_no" placeholder="No. Rekening"
                                    value="<?=(isset($data["acc_no"]) && !empty($data["acc_no"]))? $data["acc_no"] : '' ?>">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-3">Gaji</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Rp</div>
                                    </div>
                                    <input type="text" class="form-control form-control-user" id="gajiTextInput" name="salary" placeholder="..."
                                        value="<?=(isset($data["salary"]) && !empty($data["salary"]))? $data["salary"] : '' ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-3">KTP</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="file" class="form-control" id="ktpFile" name="ktp" style="height:auto;">
                                <div class="card shadow mt-2" style="height: 30vh; width: 100%;">
                                    <div class="card-body">
                                        <img src="<?= base_url('files/').$data["ktp"] ?>" 
                                            style="
                                                max-width: 100%;
                                                max-height: 100%;
                                                width: -webkit-fill-available;
                                                height: -webkit-fill-available;
                                                object-fit: contain;
                                            "/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Foto Profil</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="file" class="form-control form-control-user" id="profileFile" name="profile_photo" style="height:auto">
                                <div class="card shadow mt-2" style="height: 45vh; width: 100%;">
                                    <div class="card-body">
                                        <img src="<?= base_url('files/').$data["profile_photo"] ?>" 
                                            style="
                                                max-width: 100%;
                                                max-height: 100%;
                                                width: -webkit-fill-available;
                                                height: -webkit-fill-available;
                                                object-fit: contain;
                                            "/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <h5 class="font-weight-bold mt-4"><i class="mr-2 fas fa-users"></i> Hubungan Keluarga</h5>
                <div class="row mb-4 mt-4">
                    <div class="col-lg-8">
                        <div class="row mb-3">
                            <div class="col-lg-3">Nama</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control form-control-user" id="namaKelTextInput" name="nama_kel" placeholder="Nama Anggota Keluarga"
                                value="<?=(isset($data["name_family"]) && !empty($data["name_family"]))? $data["name_family"] : '' ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Alamat</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8">
                                <textarea class="form-control form-control-user" id="alamatKelTextArea" name="alamat_kel" rows="5"><?=(isset($data["address_family"]) && !empty($data["address_family"]))? $data["address_family"] : '' ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">No. Telephone</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control form-control-user" id="noTelpKelTextInput" name="no_telp_kel" placeholder="No. Telp"
                                value="<?=(isset($data["phone_family"]) && !empty($data["phone_family"]))? $data["phone_family"] : '' ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Status Hubungan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
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
                <a href="<?=site_url('anggota') ?>" class="btn btn-secondary mt-2 mb-2">Kembali</a>
                <button type="submit" class="btn btn-primary mt-2 mb-2 ml-2 mr-4">Simpan Data Anggota <i class="ml-2 fas fa-chevron-right"></i></button>
            </div>
            </form>
        </div>

    </div>
</div>