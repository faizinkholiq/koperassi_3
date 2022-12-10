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
                        <th class="text-center">KTP</th>
                        <th class="text-center">NIK</th>
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
                                    // case "Approved":
                                    //     $tag = "<span class='bg-success text-white font-weight-bold px-2 py-1 rounded'><i class='fas fa-check'></i> Diverifikasi</span>";
                                    //     break;
                                    case "Rejected":
                                        $tag = "<span class='bg-danger text-white font-weight-bold px-2 py-1 rounded'><i class='fas fa-times'></i> Ditolak</span>";
                                        break;
                                }
                                echo $tag; 
                            }else{
                                echo "-";
                            } 
                        ?></td>
                        <td class="text-center">
                            <?php if($row["status_perubahan"] == 'Pending'):?>
                            <button type="button" onclick="DoApprove(<?= $row['id'] ?>)" class="btn btn-sm btn-success" style="width: 2rem;"><i class="fas fa-check"></i></button>
                            <button type="button" onclick="DoReject(<?= $row['id'] ?>)" class="btn btn-sm btn-danger" style="width: 2rem;"><i class="fas fa-times"></i></button>
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

<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel"><i class="fas fa-check mr-2"></i>Setujui Perubahan Data</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <strong>Apakah anda yakin ingin menyetujui perubahan data tersebut?</strong><br/>
                <div class="mt-4 mb-4">
                    <table class="table table-bordered">
                        <tr>
                            <td class="font-weight-bold" width="30%">KTP :</td>
                            <td width="70%" id="appNIK">-</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">NIK :</td>
                            <td id="appTMK">-</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Nama :</td>
                            <td id="appNama">-</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Alamat :</td>
                            <td id="appAlamat">-</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">No. Telephone :</td>
                            <td id="appPhone">-</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Email :</td>
                            <td id="appEmail">-</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Depo :</td>
                            <td id="appDepo">-</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">No. Rekening :</td>
                            <td id="appRek">-</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <form method="POST" action="<?=site_url('anggota/action_changes/approved')?>">
                    <input type="hidden" id="appPersonID" name="id" />
                    <button class="btn btn-success mr-2" type="submit">Ya, Setuju</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel"><i class="fas fa-times mr-2"></i>Tolak Perubahan Data</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" action="<?=site_url('anggota/action_changes/rejected')?>">
                <div class="modal-body">
                    <strong>Apakah anda yakin ingin menolak perubahan data tersebut?</strong><br/>
                    <div class="mt-4 mb-4">
                        <table class="table table-bordered">
                            <tr>
                                <td class="font-weight-bold" width="30%">KTP :</td>
                                <td width="70%" id="rejNIK">-</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">NIK :</td>
                                <td id="rejTMK">-</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Nama :</td>
                                <td id="rejNama">-</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Alamat :</td>
                                <td id="rejAlamat">-</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">No. Telephone :</td>
                                <td id="rejPhone">-</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Email :</td>
                                <td id="rejEmail">-</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Depo :</td>
                                <td id="rejDepo">-</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">No. Rekening :</td>
                                <td id="rejRek">-</td>
                            </tr>
                        </table>
                    </div>                
                    <strong>Alasan:</strong>
                    <textarea class="form-control form-control-user" name="reason" rows="5" placeholder="Silahkan tulis alasan mengapa data tersebut ditolak"></textarea><br/>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="rejPersonID" name="id" />
                    <button class="btn btn-danger mr-2" type="submit">Ya, Tolak</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                </div>
            </form>
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

    function DoApprove(id){
        $.get(url.site + "/anggota/get_person_temp/" + id, (data) => {
            data = $.parseJSON(data);
            if (data.success != 0) {
                $('#appNIK').text(data.nik);
                $('#appTMK').text(data.tmk);
                $('#appNama').text(data.name);
                $('#appAlamat').text(data.address);
                $('#appEmail').text(data.email);
                $('#appDepo').text(data.depo);
                $('#appRek').text(data.acc_no);

                $('#appPersonID').val(id);
                $('#approveModal').modal('show');
            }else{
                alert(data.error);
            }
        });
    }

    function DoReject(id){
        $.get(url.site + "/anggota/get_person_temp/" + id, (data) => {
            data = $.parseJSON(data);
            if (data.success != 0) {
                $('#rejNIK').text(data.nik);
                $('#rejTMK').text(data.tmk);
                $('#rejNama').text(data.name);
                $('#rejAlamat').text(data.address);
                $('#rejEmail').text(data.email);
                $('#rejDepo').text(data.depo);
                $('#rejRek').text(data.acc_no);

                $('#rejPersonID').val(id);
                $('#rejectModal').modal('show');
            }else{
                alert(data.error);
            }
        });
    }

</script>