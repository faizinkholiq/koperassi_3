<div class="row">

    <div class="col-lg-12 mb-4">

        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <form action="<?=(isset($data))? site_url('depo/edit/'.$data["id"]) : site_url('depo/create') ?>" method="POST" enctype="multipart/form-data">
            <div class="card-body">
                <div class="row mb-4 mt-4">
                    <div class="col-lg-8">
                        <div class="row mb-3">
                            <div class="col-lg-3">Kode</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <input type="text" class="form-control form-control-user" id="codeTextInput" name="code" placeholder="Kode" 
                                    value="<?=(isset($data["code"]) && !empty($data["code"]))? $data["code"] : '' ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Nama</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <input type="text" class="form-control form-control-user" id="nameTextInput" name="name" placeholder="Nama Depo" 
                                    value="<?=(isset($data["name"]) && !empty($data["name"]))? $data["name"] : '' ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a href="<?=site_url('depo') ?>" class="btn btn-secondary btn-lg mt-4 mb-4">Kembali</a>
                <button type="submit" class="btn btn-primary btn-lg mt-4 mb-4 ml-2 mr-4">Simpan Data <i class="ml-2 fas fa-chevron-right"></i></button>
            </div>
            </form>
        </div>

    </div>
</div>