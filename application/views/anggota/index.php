<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
<?php
    $role_params = (isset($_GET["role"]) && $_GET["role"] == 1)? "?role=1" : "";
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
        <a href="<?=site_url('anggota/create').$role_params ?>" class="btn my-btn-primary"><i class="fas fw fa-user-plus mr-1"></i> <?= (isset($_GET["role"]) && $_GET["role"] == 1)? "Administrator" : "Anggota" ?></a>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered display nowrap" id="anggotaTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" width="10">No</th>
                        <th class="text-center">NIK</th>
                        <th class="text-center">TMK</th>
                        <th class="text-center">Nama Anggota</th>
                        <th class="text-center">No. Telp</th>
                        <th class="text-center">Jabatan</th>
                        <th class="text-center">Tgl. Keanggotaan</th>
                        <th class="text-center">Status<br/>Keanggotaan</th>
                        <th class="text-center">Pengajuan<br/>Perubahan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $key => $row): ?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><?=$row["nik"]?></td>
                        <td><?=$row["tmk"]?></td>
                        <td><?=$row["name"]?></td>
                        <td><?=$row["phone"]?></td>
                        <td><?=$row["position_name"]?></td>
                        <td><?=$row["join_date"]?></td>
                        <td class="text-center"><?=$row["status"]?></td>
                        <td class="text-center"><?php 
                            if(!empty($row["status_perubahan"])){
                                $tag = "-";
                                switch($row["status_perubahan"]){
                                    case "Pending":
                                        $tag = "<span class='bg-warning text-white font-weight-bold px-2 py-1 rounded'><i class='fas fa-clock'></i> Pending</span>";
                                        break;
                                    case "Approved":
                                        $tag = "<span class='bg-success text-white font-weight-bold px-2 py-1 rounded'><i class='fas fa-check'></i> DiVerifikasi</span>";
                                        break;
                                    case "Rejected":
                                        $tag = "<span class='bg-danger text-white font-weight-bold px-2 py-1 rounded'><i class='fas fa-times'></i> DiTolak</span>";
                                        break;
                                }
                                echo $tag; 
                            }else{
                                echo "-";
                            } 
                        ?></td>
                        <td class="text-center">
                            <?php if($row["status_perubahan"] == 'Pending'):?>
                            <a href="#!" class="btn btn-sm btn-success" style="width: 2rem;"><i class="fas fa-check"></i></a>
                            <a href="#!" class="btn btn-sm btn-danger" style="width: 2rem;"><i class="fas fa-times"></i></a>
                            <?php endif; ?>
                            <a href="<?= site_url('anggota/edit/'.$row["id"]).$role_params ?>" class="btn btn-sm my-btn-primary" style="width: 2rem;"><i class="fas fa-edit"></i></a>
                            <a href="<?= site_url('anggota/detail/'.$row["id"]).$role_params ?>" class="btn btn-sm btn-primary" style="width: 2rem;"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>

<script>
    const url = {
        "site": "<?= site_url() ?>",
        "base": "<?= base_url() ?>",
    }

    // Call the dataTables jQuery plugin
    $(document).ready(function() {
        $('#anggotaTable').DataTable({
            scrollX:        true,
        });
    });

</script>