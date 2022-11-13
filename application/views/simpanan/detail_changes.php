<div class="row mb-4">
    <div class="col-lg-2"></div>
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-body text-lg mt-4">
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">No. Anggota</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($before['tmk']) && !empty($before['tmk']))? $before['tmk'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Nama</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($before['name']) && !empty($before['name']))? $before['name'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Jabatan</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($before['position']) && !empty($before['position']))? $before['position'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Depo / Stock Point</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($before['depo']) && !empty($before['depo']))? $before['depo'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">No. Rekening</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($before['acc_no']) && !empty($before['acc_no']))? $before['acc_no'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Alamat</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= (isset($before['acc_no']) && !empty($before['acc_no']))? $before['acc_no'] : '-' ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Tipe</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= $type ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Kode Transaksi</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= $before['code'] ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Tgl. Pembayaran</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7"><?= $before['date'] ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3 offset-lg-1 font-weight-bold">Simpanan</div>
                    <div class="col-lg-1 text-right">:</div>
                    <div class="col-lg-7">
                        <?php if(isset($after['balance'])): ?>
                        <span class="my-tag bg-danger text-white">
                            <?= $before['balance'] ?>
                        </span>
                        <i class="fas fa-arrow-right mr-3 ml-3"></i>
                        <span class="my-tag bg-success text-white">
                            <?= $after['balance'] ?>
                        </span>
                        <?php else: ?>
                        <span class="my-tag bg-success text-white">
                            <?= $before['balance'] ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-footer mt-4">
                <div class="row">
                    <div class="col-lg-6">
                        <a href="<?=site_url('user/notifications') ?>" class="btn my-btn-secondary mt-2 mb-2 mr-4 font-weight-bold btn-lg shadow">Batal</a>
                    </div>
                    <?php if($after && $role == 1): ?>
                    <div class="col-lg-6 text-right">
                        <button type="button" class="btn btn-danger font-weight-bold btn-lg mr-4 shadow"
                            onclick="doApprove(
                                <?= $detail['id'] ?>, 
                                <?= $detail['user_id'] ?>, 
                                '<?= $detail['module'] ?>',
                                <?= $detail['changes_id'] ?>,
                            )">
                            <i class="fas fa-times mr-2"></i>Tolak
                        </button>
                        <button type="button" class="btn my-btn-primary font-weight-bold btn-lg shadow"
                            onclick="doDecline(
                                <?= $detail['id'] ?>, 
                                <?= $detail['user_id'] ?>, 
                                '<?= $detail['module'] ?>',
                                <?= $detail['changes_id'] ?>,
                            )">
                            <i class="fas fa-check mr-2"></i>Setuju
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


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
                    <input id="idApprove" type="hidden" name="id" value="" />
                    <input id="userApprove" type="hidden" name="user_id" value="" />
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
                    <input id="idDecline" type="hidden" name="id" value="" />
                    <input id="userDecline" type="hidden" name="user_id" value="" />
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