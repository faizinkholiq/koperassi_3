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
        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <form action="<?= site_url('parameter/update/').$person_id ?>" method="POST" enctype="multipart/form-data">
            <div class="card-body">
                <div class="row mb-4 mt-4">
                    <div class="col-lg-8">
                        <div class="row mb-3">
                            <div class="col-lg-2">Bulan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <select class="form-control" name="month">
                                    <?php 
                                    $months = ['Januari', 'Februari', 'Maret', 'April', 'May', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                    $data['month'] = isset($data['month']) && !empty($data['month']) ? $data['month'] : (int)date('m');
                                    foreach($months as $key => $item):
                                    ?>
                                    <option value="<?= $key+1 ?>"  <?= ($key + 1  == $data['month'])? 'selected' : '' ?>><?= $item ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4 mt-4">
                    <div class="col-lg-8">
                        <div class="row mb-3">
                            <div class="col-lg-2">Tahun</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <select class="form-control" name="year">
                                    <?php 
                                    $start = 2019;
                                    $data['year'] = isset($data['year']) && !empty($data['year']) ? $data['year'] : date('Y');
                                    for($i = $start; $i <= date('Y'); $i++):
                                    ?>
                                    <option value="<?= $i ?>" <?= ($i == $data['year'])? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
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
    let pass = "<?=(isset($data["password"]) && !empty($data["password"]))? $data["password"] : '' ?>";
    function removeFile(name){
        $('#remove_'+name).val(true);
        $('#card_'+name).slideUp();
    }

    function showKonfPass(){
        $('#konfPass').fadeIn();
        $('#passwordTextInput').removeAttr('disabled');
        $('#passwordTextInput').val('');
    }

    function closeKonfPass(){
        $('#konfPass').fadeOut();
        $('#passwordTextInput').attr('disabled', 'disabled');
        $('#passwordTextInput').val(pass);
    }
</script>