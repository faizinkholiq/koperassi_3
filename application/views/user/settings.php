<div class="row">

    <div class="col-lg-12 mb-4">

        <?php
            if(!empty($this->session->flashdata('msg'))):
                $msg = $this->session->flashdata('msg');
        ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Perubahan berhasil disimpan !</strong>
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
                <div class="row mb-4 mt-4">
                    <div class="col-lg-8">
                        <div class="row mb-3">
                            <div class="col-lg-3">Username</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control form-control-user" id="usernameTextInput" name="username" placeholder="Username" 
                                    value="<?=(isset($data["username"]) && !empty($data["username"]))? $data["username"] : '' ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Password</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="password" class="form-control form-control-user" id="passwordTextInput" name="password" placeholder="Password" 
                                    value="" required>
                                <a href="#!">Ubah password? </a>
                            </div>
                        </div>
                        <!-- <div class="row mb-3">
                            <div class="col-lg-3">Konfirmasi Password</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <input type="password" class="form-control form-control-user" id="passwordTextInput" name="password" placeholder="Konfirmasi Password" 
                                    value="" required>
                            </div>
                        </div> -->
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
                <button type="submit" class="btn btn-primary mt-2 mb-2 ml-2 mr-4 btn-lg">Simpan perubahan<i class="ml-2 fas fa-chevron-right"></i></button>
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