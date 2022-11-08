<div class="row">

    <div class="col-lg-12 mb-4">

        <?php
            if(!empty($this->session->flashdata('msg'))):
                $msg = $this->session->flashdata('msg');
        ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Pengajuan perubahan data berhasil, Harap tunggu hingga adminsitrator menyetujui perubahan data tersebut !</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>
        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <form action="<?=(isset($data))? site_url('anggota/edit/'.$data["id"]) : site_url('anggota/create') ?>" method="POST" enctype="multipart/form-data">
            <div class="card-body">
                <a class="my-text-primary" href="<?=site_url('anggota')?>">
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
                            <div class="col-lg-3">KTP</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="file" class="form-control" id="ktpFile" name="ktp" style="height:auto;">
                                <?php if(isset($data["ktp"]) && !empty($data['ktp'])): ?>
                                <input type="hidden" id="remove_ktp" name="remove_ktp">
                                <div id="card_ktp" class="card shadow mt-2" style="height: 30vh; width: 100%;">
                                    <div class="card-body" style="display: flex; justify-content: space-between;">
                                        <img src="<?= base_url('files/').$data["ktp"] ?>" 
                                            style="
                                                max-width: 89%;
                                                max-height: 100%;
                                                width: -webkit-fill-available;
                                                height: -webkit-fill-available;
                                                object-fit: contain;
                                            "/>
                                        <button type="button" class="btn btn-danger" 
                                            onclick="removeFile('ktp')"
                                            style="
                                                height: fit-content;
                                                width: fit-content;
                                            ">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Foto Profil</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="file" class="form-control form-control-user" id="profileFile" name="profile_photo" style="height:auto">
                                <?php if(isset($data["profile_photo"]) && !empty($data['profile_photo'])): ?>
                                <input type="hidden" id="remove_profile_photo" name="remove_profile_photo">
                                <div id="card_profile_photo" class="card shadow mt-2" style="height: 30vh; width: 60%;">
                                    <div class="card-body" style="display: flex; justify-content: space-between;">
                                        <img src="<?= base_url('files/').$data["profile_photo"] ?>" 
                                            style="
                                                max-width: 100%;
                                                max-height: 100%;
                                                width: 80%;
                                                height: -webkit-fill-available;
                                                object-fit: contain;
                                            "/>
                                        <button type="button" class="btn btn-danger"
                                            onclick="removeFile('profile_photo')" 
                                            style="
                                                height: fit-content;
                                                width: fit-content;
                                            ">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="alert alert-dismissible fade show text-left text-danger" role="alert">
                            <strong>* Note: Data yang berhasil diubah harus menunggu persetujuan admin terlebih dahulu.</strong>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <button type="submit" class="btn btn-primary mt-2 mb-2 ml-2 mr-4 btn-lg">Ajukan Perubahan Data<i class="ml-2 fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
            </form>
        </div>

    </div>
</div>

<script>
    function removeFile(name){
        $('#remove_'+name).val(true);
        $('#card_'+name).slideUp();
    }
</script>