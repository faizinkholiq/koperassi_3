<!-- DataTales Example -->
<div class="alert alert-info fade show" role="alert">
        <strong>Sebelum melakukan persetujuan perubahan data, disarankan untuk melihat detail terlebih dahulu agar data terverifikasi dengan benar.</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php foreach ($notification as $key => $row): ?>
<div class="card shadow mb-2">
    <div class="card shadow">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-10">
                    <div class="small text-gray-500 mb-2 font-weight-bold"><?= $row["person_name"] ?> - <?= $row["time"] ?></div>
                    <?= $row["message"] ?>
                </div>
                <div class="col-lg-1 text-right mt-2 border-right" style="font-size: 2rem!important;">
                    <a href="<?= site_url("user/notifications_detail/").$row["id"] ?>" class="text-info mr-2"><i class="fas fa-info-circle"></i></a>
                </div>
                <div class="col-lg-1 mt-2" style="font-size: 2rem!important;">
                    <a href="#!" onclick="doApprove(<?= $row['person_id'] ?>)" class="text-success ml-2"><i class="fas fa-check-circle"></i></a>
                    <a href="#!" onclick="doDecline(<?= $row['person_id'] ?>)" class="text-danger"><i class="fas fa-times-circle"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel"
        aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Setujui Perubahan Data?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Apakah anda yakin ingin menyetujui perubahan data ini ?</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <a class="btn btn-success" href="<?= site_url('user/do_changes/approve') ?>">Setuju</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="declineModal" tabindex="-1" role="dialog" aria-labelledby="declineModalLabel"
        aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declineModalLabel">Tolak Perubahan Data?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Apakah anda yakin ingin menolak perubahan data ini ?</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <a class="btn btn-danger" href="<?= site_url('user/do_changes/decline') ?>">Ya, Tolak</a>
            </div>
        </div>
    </div>
</div>

<script>
    function doApprove(){
        $('#approveModal').modal('show');
    }

    function doDecline(){
        $('#declineModal').modal('show');
    }
</script>