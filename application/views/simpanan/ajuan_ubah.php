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

<div class="card mb-4 shadow">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6 font-weight-bold border-right">
                <div class="text-lg mb-2">Plafon: <span class="text-danger ml-2">Rp<?= $summary['plafon'] ?></span></div>
                <div class="text-lg">Limit Pinjaman: <span class="text-danger ml-2">Rp<?= $summary['limit'] ?></span></div>
            </div>
            <div class="col-lg-6 font-weight-bold text-right">
                <div class="text-lg mb-2">Gaji Pokok: <span class="text-danger ml-2">Rp<?= $summary['gaji'] ?></span></div>
                <div class="text-lg">Total Simpanan: <span class="text-danger ml-2">Rp<?= $summary['simpanan'] ?></span></div>
            </div>
        </div>
    </div>
</div>
<button type="button" class="btn btn-primary font-weight-bold" data-toggle="modal" data-target="#historyModal"><i class="fas fa-history mr-2"></i> History Perubahan</button>
<div class="card shadow mb-4 mt-2">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="simpananTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Kode Transaksi</th>
                        <th class="text-center">Uraian</th>
                        <th class="text-center">Pemasukan</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

 <!-- Input Modal-->
 <div class="modal fade" id="inputModal" tabindex="-1" role="dialog" aria-labelledby="inputModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inputModalLabel"><i class="mr-2 fas fa-hand-holding-usd"></i> Tambah Simpanan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="formSimpanan" action="<?= site_url('simpanan/edit_temp') ?>" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="row mb-4 mt-4">
                    <div class="col-lg-12">
                        <input id="personTextInput" type="hidden" name="person_id" value="<?= $person_id; ?>">
                        <input id="simpananTextInput" type="hidden" name="simpanan_id" value="">
                        <input id="kodeTextInput" type="hidden" name="code" value="">
                        <div class="row mb-3">
                            <div class="col-lg-3">Tanggal Bayar</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-5">
                                <input type="date" class="form-control form-control-user" id="tglDateInput" name="date" 
                                    value="<?= date('Y-m-d') ?>" disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">No. Anggota</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="noAnggotaTextInput" name="no_anggota" readonly="readonly">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Nama Anggota</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="namaAnggotaTextInput" name="nama_anggota" readonly="readonly">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Jabatan</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="jabatanTextInput" name="jabatan" readonly="readonly">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Depo/Stock Point</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="depoTextInput" name="depo" readonly="readonly">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">No. Rekening</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="noRekTextInput" name="no_rek" readonly="readonly">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Alamat</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-7">
                                <textarea readonly="readonly" class="form-control form-control-user" name="alamat" id="alamatTextArea"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Tipe</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-5">
                                <select class="form-control form-control-user" id="statusCombo" name="type" readonly="readonly">
                                    <option value="Pokok">Simpanan Pokok</option>
                                    <option value="Wajib">Simpanan Wajib</option>
                                    <option value="Sukarela" selected>Simpanan Sukarela</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">Nominal</div>
                            <div class="col-lg-1 text-right">:</div>
                            <div class="col-lg-5">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Rp</div>
                                    </div>
                                    <input type="text" class="form-control" id="jumlahTextInput" name="balance" placeholder="...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger mt-4 mb-4" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success mt-4 mb-4 ml-2 mr-4"> Ajukan Perubahan Simpanan <i class="ml-2 fas fa-chevron-right"></i></button>
            </div>
            </form>
        </div>
    </div>
</div>

 <!-- History Modal-->
 <div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel"><i class="mr-2 fas fa-history"></i> History Perubahan Simpanan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Kode Transaksi</th>
                            <th class="text-center">Uraian</th>
                            <th class="text-center">Pemasukkan</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($history as $key => $row): ?>
                        <tr>
                            <td class="text-center"><?= $row["date"] ?></td>
                            <td class="text-center"><?= $row["code"] ?></td>
                            <td class="text-center"><?= $row["type"] ?></td>
                            <td class="text-center"><?= $row["balance"] ?></td>
                            <td class="text-center"><?php 
                                switch ($row["status"]){
                                    case "Pending":
                                        echo '<i class="fas fa-clock text-warning text-lg"
                                            data-toggle="tooltip" data-placement="top" 
                                            title="Pengajuan perubahan sedang diproses"></i>';
                                        break;
                                    case "Success":
                                        echo '<i class="fas fa-check-circle text-success text-lg"
                                            data-toggle="tooltip" data-placement="top" 
                                            title="Admistrator telah menyetujui perubahan"></i>';
                                        break;
                                    case "Failed":
                                        echo '<i class="fas fa-times-circle text-danger text-lg"
                                            data-toggle="tooltip" data-placement="top" 
                                            title="Pengajuan perubahan gagal, karena Administrator tidak menyetujui perubahan"></i>';
                                        break;
                                }
                            ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
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

    const list_anggota = <?= json_encode($person_list); ?>;
    const person = <?= $person_id ?>

    let dt = $('#simpananTable').DataTable({
        dom: "Bfrtip",
        ajax: {
            url: url.site + "/simpanan/get_dt_simpanan",
            type: "POST",
            data: function(d){
                d.person = person;
            }
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: "date" },
            { data: "code" },
            { data: "type" },
            { data: "balance" },
        ],
        ordering: false,
        scrollX: true,
    });

    // Call the dataTables jQuery plugin
    $(document).ready(function() {
    });

    function showForm(simpanan_id){
        resetForm();
        let simpanan = simpanan_data.filter(r => r.id == simpanan_id)

        if(simpanan.length > 0){
            
            $('#kodeTextInput').val(simpanan[0].code);
            $('#simpananTextInput').val(simpanan[0].id);
            $('#namaAnggotaTextInput').val(simpanan[0].name);
            $('#noAnggotaTextInput').val(simpanan[0].nik);
            $('#jabatanTextInput').val(simpanan[0].position_name);
            $('#depoTextInput').val(simpanan[0].depo);
            $('#alamatTextArea').text(simpanan[0].address);
            $('#noRekTextInput').val(simpanan[0].acc_no);
            $('#jumlahTextInput').val(simpanan[0].balance);
            $('#tglDateInput').val(simpanan[0].date);

            $('#inputModal').modal('show');
        }else{
            alert("ID Simpanan tidak ditemukan")
        }
    }
    
    function resetForm(){
        $('#formSimpanan')[0].reset();
        $('#alamatTextArea').text("");
    }

</script>