<div class="row">

    <div class="col-lg-12 mb-4">

        <?php
            if(!empty($this->session->flashdata('msg'))):
                $msg = $this->session->flashdata('msg');
        ?>
        <div class="alert <?= ($msg['success'])? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show" role="alert">
            <strong><?= ($msg['success'])? $msg["message"] : $msg["error"] ?></strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>
        <?php
            if($data["temporary"]): 
        ?>
            <?php 
            switch($data["status"]):  
                case "Pending": ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-clock mr-2"></i> Perubahan data sedang diproses, mohon tunggu hingga administrator menyetujui !</strong>
                    </div>
            <?php 
                break;
                case "Rejected": 
            ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-times mr-2"></i> Perubahan data anda ditolak, <strong>Alasan:</strong> <?= $data["reason"] ?> !
                    </div>
            <?php 
                break;
            endswitch; 
            ?>
        <?php
            endif;
        ?>
        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <form action="<?= site_url('anggota/edit_temp/'.$data["id"]) ?>" method="POST" enctype="multipart/form-data">
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
                                    value="<?=(isset($data["nik"]) && !empty($data["nik"]))? $data["nik"] : '' ?>" 
                                    <?= ($data["status"] == "Pending")? "disabled" : "" ?> required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">TMK</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control form-control-user" id="tmkTextInput" name="tmk" placeholder="TMK"
                                    value="<?=(isset($data["tmk"]) && !empty($data["tmk"]))? $data["tmk"] : '' ?>" 
                                    <?= ($data["status"] == "Pending")? "disabled" : "" ?> required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Nama Lengkap</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control form-control-user" id="namaTextInput" name="nama" placeholder="Nama"
                                    value="<?=(isset($data["name"]) && !empty($data["name"]))? $data["name"] : '' ?>" 
                                    <?= ($data["status"] == "Pending")? "disabled" : "" ?> required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Alamat</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-8">
                                <textarea class="form-control form-control-user" name="alamat" id="alamatTextArea" rows="5"
                                    <?= ($data["status"] == "Pending")? "disabled" : "" ?>
                                ><?=(isset($data["address"]) && !empty($data["address"]))? $data["address"] : '' ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">No. Telephone</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control form-control-user" id="noTelpTextInput" name="no_telp" placeholder="No. Telephone"
                                    value="<?=(isset($data["phone"]) && !empty($data["phone"]))? $data["phone"] : '' ?>"
                                    <?= ($data["status"] == "Pending")? "disabled" : "" ?>>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Email</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="email" class="form-control form-control-user" id="emailTextInput" name="email" placeholder="Email"
                                    value="<?=(isset($data["email"]) && !empty($data["email"]))? $data["email"] : '' ?>"
                                    <?= ($data["status"] == "Pending")? "disabled" : "" ?>>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Depo/Stock Point</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <select class="form-control form-control-user" id="depoSelectInput" name="depo" <?= ($data["status"] == "Pending")? "disabled" : "" ?>>
                                    <option value="">- Pilih salah satu -</option>
                                    <?php foreach($list_depo as $key => $value): ?>
                                    <option value="<?= $value["code"] ?>" <?=(isset($data["depo"]) && $data["depo"] == $value["code"])? 'selected' : '' ?>><?= $value["name"] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">No. Rekening</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control form-control-user" id="accNoTextInput" name="acc_no" placeholder="No. Rekening"
                                    value="<?=(isset($data["acc_no"]) && !empty($data["acc_no"]))? $data["acc_no"] : '' ?>"
                                    <?= ($data["status"] == "Pending")? "disabled" : "" ?>>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-3">KTP</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <?php if(!$data["temporary"]): ?>
                                <input type="file" class="form-control" id="ktpFile" name="ktp" style="height:auto;">
                                <?php endif; ?>
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
                                        <?php if(!$data["temporary"]): ?>
                                            <button type="button" class="btn btn-danger" 
                                                onclick="removeFile('ktp')"
                                                style="
                                                    height: fit-content;
                                                    width: fit-content;
                                                ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
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
                        <button type="submit" class="btn btn-primary mt-2 mb-2 ml-2 mr-4 btn-lg"
                        <?= ($data["status"] == "Pending")? "disabled" : "" ?>>
                            Ajukan Perubahan Data<i class="ml-2 fas fa-chevron-right"></i>
                        </button>
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