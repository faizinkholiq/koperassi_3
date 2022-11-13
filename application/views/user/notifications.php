<!-- DataTales Example -->
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
                <div class="col-lg-9" style="display:flex">
                    <div class="bg-success text-center" style="width:4rem; height:100%; border-radius: 0.9rem;">
                        <i class="fas fa-check mt-3 text-lg text-white"></i>
                    </div>
                    <div class="ml-4">
                        <div class="small text-gray-500 mb-2 font-weight-bold"><?= $row["person_name"] ?> - <?= $row["time"] ?></div>
                        <?= $row["message"] ?>
                    </div>
                </div>
                <div class="col-lg-1 text-right mt-2" style="font-size: 2rem!important;">
                    <a href="<?= site_url("user/notifications_detail/").$row["id"] ?>" class="text-info mr-2"><i class="fas fa-info-circle"></i></a>
                </div>
                <?php if($row["status"] == 'Pending'): ?>
                <div class="col-lg-1 mt-2 border-left" style="font-size: 2rem!important;">
                    <a href="#!" 
                        onclick="doApprove(
                            <?= $row['id'] ?>, 
                            <?= $row['user_id'] ?>, 
                            '<?= $row['module'] ?>',
                            <?= $row['changes_id'] ?>,
                        )" 
                        class="text-success ml-2"><i class="fas fa-check-circle"></i></a>
                    <a href="#!" 
                        onclick="doDecline(
                            <?= $row['id'] ?>, 
                            <?= $row['user_id'] ?>, 
                            '<?= $row['module'] ?>',
                            <?= $row['changes_id'] ?>,
                        )" 
                        class="text-danger"><i class="fas fa-times-circle"></i></a>
                </div>
                <?php endif; ?>
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
                <form action="<?= site_url('user/do_changes') ?>" method="post">
                    <input type="hidden" name="action" value="approve" />
                    <input id="idApprove" type="text" name="id" value="" />
                    <input id="userApprove" type="text" name="user_id" value="" />
                    <input id="moduleApprove" type="hidden" name="module" value="" />
                    <input id="changesIDApprove" type="hidden" name="changes_id" value="" />
                    <button type="button" class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Setuju</button>
                </form>
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
                <form action="<?= site_url('user/do_changes') ?>" method="post">
                    <input type="hidden" name="action" value="decline" />
                    <input id="idDecline" type="text" name="id" value="" />
                    <input id="userDecline" type="text" name="user_id" value="" />
                    <input id="moduleDecline" type="hidden" name="module" value="" />
                    <input id="changesIDDecline" type="hidden" name="changes_id" value="" />
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Tolak</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function doApprove(id, user, modules, changes_id){
        $('#idApprove').val(id);
        $('#userApprove').val(user);
        $('#moduleApprove').val(modules);
        $('#changesIDApprove').val(changes_id);
        $('#approveModal').modal('show');
    }

    function doDecline(id, user, modules, changes_id){
        $('#idDecline').val(id);
        $('#userDecline').val(user);
        $('#moduleDecline').val(modules);
        $('#changesIDDecline').val(changes_id);
        $('#declineModal').modal('show');
    }
</script>