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
        <a href="<?= site_url('depo/create') ?>" class="btn my-btn-primary mr-2"><i class="fas fw fa-plus mr-1"></i> Tambah baru</a>
        <hr>
        <div class="table-responsive">
            <table id="depoTable" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" width="10">No</th>
                        <th class="text-center" width="300">Kode</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center" width="250">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1; 
                        foreach($data as $key => $row): 
                    ?>
                    <tr>
                        <td><?=$no++ ?></td>
                        <td><?= $row['code'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td class="text-center">
                            <a href="<?=site_url('depo/edit/'.$row["id"])?>" class="btn btn-sm btn-primary"><i class="fas fa-edit mr-2"></i>Ubah</a>
                            <button type="button" onclick="DoDelete(<?= $row['id'] ?>)" class="btn btn-sm btn-danger ml-2"><i class="fas fa-trash mr-2"></i>Hapus</button>
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
                <h5 class="modal-title" id="deleteModalLabel">Hapus Depo</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Apakah anda yakin ingin menghapus data ini?</div>
            <div class="modal-footer">
                <form method="GET" action="<?= site_url('depo/delete') ?>">
                    <input type="hidden" id="deletedID" name="id" />
                    <button class="btn btn-danger ml-2" type="submit">Hapus</button>
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
        $('#depoTable').DataTable();
    });

    function DoDelete(id){
        $('#deletedID').val(id);
        $('#deleteModal').modal('show');
    }

</script>