<div id="alertSuccess" class="alert alert-success alert-dismissible fade show" role="alert" style="display:none">
    <strong id="msgSuccess">Proses posting berhasil !</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div id="alertFailed" class="alert alert-danger alert-dismissible fade show" role="alert" style="display:none">
    <strong id="msgFailed">Proses posting Gagal !</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="row">
    <div class="col-lg-12 mb-4">
        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <form action="#!" method="POST" id="postingForm" enctype="multipart/form-data">
            <div class="card-body">
                <div class="row mb-4 mt-4" style="position: relative;">
                    <div class="loading" style="background: rgba(255, 255, 255, 0.8);; position: absolute; width: 100%; height: 100%; display: none; align-items:center; justify-content: center; z-index: 9999;">
                        <div class="load-3 text-center">
                            <div class="mb-4 font-weight-bold text-lg">Mohon tunggu sebentar, proses posting sedang berjalan</div>
                            <div class="line bg-danger"></div>
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="row mb-3">
                            <div class="col-lg-3">Bulan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <select class="form-control col-lg-8" id="selectBulan" name="bulan">
                                    <?php 
                                    $months = ['Januari', 'Februari', 'Maret', 'April', 'May', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                    foreach($months as $key => $item):
                                    ?>
                                    <option value="<?= $key+1 ?>" <?= ($key+1 == (int)date('m'))? 'selected' : '' ?>><?= $item ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-3">Tahun</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-4">
                                <select class="form-control col-lg-8" id="selectTahun" name="tahun">
                                    <?php 
                                    $start = 2019;
                                    for($i = $start; $i <= date('Y'); $i++):
                                    ?>
                                    <option value="<?= $i ?>" <?= ($i == date('Y'))? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-3">Simpanan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="pokokCheckbox" name="simpanan[]" value="pokok">
                                    <label class="form-check-label" for="pokokCheckbox">Pokok</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="wajibCheckbox" name="simpanan[]" value="wajib">
                                    <label class="form-check-label" for="wajibCheckbox">Wajib</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="sukarelaCheckbox" name="simpanan[]" value="sukarela">
                                    <label class="form-check-label" for="sukarelaCheckbox">Sukarela</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="investasiCheckbox" name="simpanan[]" value="investasi">
                                    <label class="form-check-label" for="investasiCheckbox">Investasi</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" id="btnPosting" class="btn btn-primary btn-md mt-2 mb-2 ml-2 mr-4">Posting Data<i class="ml-2 fas fa-chevron-right"></i></button>
            </div>
            </form>
        </div>

    </div>
</div>

<script>
    const urls = {
        "base": "<?= base_url() ?>",
        "site": "<?= site_url() ?>",
    };

    $(document).ready(function () {
        $("#postingForm").submit(function (event) {
            let formData = $(this).serialize();
            showLoad();
            $.ajax({
                type: "POST",
                url: urls.site + "/simpanan/do_posting",
                data: formData,
                dataType: "json",
                encode: true,
            }).done(function (data) {
                setTimeout(()=>{
                    hideLoad();
                    if (data.success) {
                        alerts('success', data.message);
                    }else{
                        alerts('failed', data.error);
                    }
                }, 1000);
            }).fail(function() {
                setTimeout(()=>{
                    hideLoad();
                    alerts('failed', "Proses posting gagal !");
                }, 1000);
            });

            event.preventDefault();
        });
    });

    function showLoad(){
        $('.loading').css('display', 'flex');
        $('#btnPosting').addClass('disabled');
    }

    function hideLoad(){
        $('.loading').css('display', 'none');
        $('#btnPosting').removeClass('disabled');
    }

    function alerts(type, msg){
        switch (type) {
            case "success":
                $('#msgSuccess').text(msg);
                $('#alertSuccess').fadeIn();
                setTimeout(()=>{
                    $('#alertSuccess').fadeOut();
                }, 2000);
                break;
            case "failed":
                $('#msgFailed').text(msg);
                $('#alertFailed').fadeIn();
                setTimeout(()=>{
                    $('#alertFailed').fadeOut();
                }, 2000);
                break;
        }
    }
</script>