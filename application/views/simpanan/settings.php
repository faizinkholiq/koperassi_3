<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
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
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <a href="<?= site_url('simpanan/create_settings') ?>" class="btn my-btn-primary mr-2"><i class="fas fw fa-plus mr-1"></i> Tambah baru</a>
        <hr>
        <div class="table-responsive">
            <table id="simpananTable" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" width="10">No</th>
                        <th class="text-center">Simpanan</th>
                        <th class="text-center">Nominal</th>
                        <th class="text-center" width="300">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1; 
                        foreach($data as $key => $row): 
                    ?>
                    <tr>
                        <td><?=$no++ ?></td>
                        <td><?= $row['simpanan'] ?></td>
                        <td><?= $row['nominal'] ?></td>
                        <td class="text-left">
                            <a href="<?=site_url('simpanan/edit_settings/'.$row["id"])?>" class="btn btn-sm btn-primary ml-4"><i class="fas fa-edit mr-2"></i>Ubah</a>
                            <button type="button" onclick="DoDelete(<?= $row['id'] ?>)" class="btn btn-sm btn-danger ml-2"><i class="fas fa-trash mr-2"></i>Hapus</button>
                            <?php if($row['simpanan'] == "Sukarela" || $row['simpanan'] == "Investasi"): ?>
                            <button type="button" onclick="DoGenerate(<?= $row['id'] ?>)" class="btn btn-sm my-btn-primary ml-2"><i class="fas fa-sync mr-2"></i>Generate</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Hapus Jabatan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Apakah anda yakin ingin menghapus data ini?</div>
            <div class="modal-footer">
                <form method="GET" action="<?= site_url('simpanan/delete_settings') ?>">
                    <input type="hidden" id="deletedID" name="id" />
                    <button class="btn btn-danger ml-2" type="submit">Hapus</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="generateModal" tabindex="-1" role="dialog" aria-labelledby="generateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateModalLabel">Generate Nilai Default Simpanan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah anda yakin meng-generate data ini?<br/>
                <b>* Data yang dipilih akan otomatis meng-generate nilai default yang ada di data anggota</b>
            </div>
            <div class="modal-footer">
                <form method="GET" action="<?= site_url('simpanan/generate_settings') ?>">
                    <input type="hidden" id="generatedID" name="id" />
                    <button class="btn my-btn-primary ml-2" type="submit">Generate</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script>
    $(document).ready(()=>{
        $('#simpananTable').DataTable();
    });

    function DoDelete(id){
        $('#deletedID').val(id);
        $('#deleteModal').modal('show');
    }

    function DoGenerate(id){
        $('#generatedID').val(id);
        $('#generateModal').modal('show');
    }

</script>