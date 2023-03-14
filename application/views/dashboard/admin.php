<!-- Content Row -->
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4" >
        <div class="card shadow" style="width:100%; background:#f1c40f;">
            <div class="card-body" style="height:7rem; color:white; font-size:1rem; font-weight:bold;">
                <div class="row mb-1">
                    <div class="col-lg-8"><?= $summary['wajib']['now'] ?></div>
                    <div class="col-lg-4">Simpanan Bulan Ini</div>
                </div>
                <div class="row mb-1">
                    <div class="col-lg-8"><?= $summary['wajib']['all'] ?></div>
                    <div class="col-lg-4">Total Simpanan</div>
                </div>
            </div>
            <div class="card-footer font-weight-bold" style="background:#f39c12; border:none; color:white; font-size:1.3rem;">
                Simpanan Wajib
                <i class="float-right fa fa-chevron-right"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card shadow" style="width:100%; background:#e74c3c;">
            <div class="card-body" style="height:7rem; color:white; font-size:1rem; font-weight:bold;">
                <div class="row mb-1">
                    <div class="col-lg-8"><?= $summary['pokok']['now'] ?></div>
                    <div class="col-lg-4">Simpanan Bulan Ini</div>
                </div>
                <div class="row">
                    <div class="col-lg-8"><?= $summary['pokok']['all'] ?></div>
                    <div class="col-lg-4">Total Simpanan</div>
                </div>
            </div>
            <div class="card-footer font-weight-bold" style="background:#c0392b; border:none; color:white; font-size:1.3rem;">
                Simpanan Pokok
                <i class="float-right fa fa-chevron-right"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card shadow" style="width:100%; background:#3498db;">
            <div class="card-body" style="height:7rem; color:white; font-size:1rem; font-weight:bold;">
                <div class="row mb-1">
                    <div class="col-lg-8"><?= $summary['sukarela']['now'] ?></div>
                    <div class="col-lg-4">Simpanan Bulan Ini</div>
                </div>
                <div class="row">
                    <div class="col-lg-8"><?= $summary['sukarela']['all'] ?></div>
                    <div class="col-lg-4">Total Simpanan</div>
                </div>
            </div>
            <div class="card-footer font-weight-bold" style="background:#2980b9; border:none; color:white; font-size:1.3rem;">
                Simpanan Sukarela
                <i class="float-right fa fa-chevron-right"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4 offset-2">
        <div class="card shadow" style="width:100%; background:#1abc9c;">
            <div class="card-body" style="height:7rem; color:white; font-size:1rem; font-weight:bold;">
                <div class="row mb-1">
                    <div class="col-lg-8"><?= $summary['investasi']['now'] ?></div>
                    <div class="col-lg-4">Simpanan Bulan Ini</div>
                </div>
                <div class="row">
                    <div class="col-lg-8"><?= $summary['investasi']['all'] ?></div>
                    <div class="col-lg-4">Total Simpanan</div>
                </div>
            </div>
            <div class="card-footer font-weight-bold" style="background:#16a085; border:none; color:white; font-size:1.3rem;">
                Investasi
                <i class="float-right fa fa-chevron-right"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card shadow" style="width:100%; background:#9b59b6;">
            <div class="card-body font-weight-bold" style="height:7rem; color:white;">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="font-weight-bold"  style="font-size:2.5rem"><?= $summary['total'] ?></div>
                    </div>
                </div>
            </div>
            <div class="card-footer font-weight-bold" style="background:#8e44ad; border:none; color:white; font-size:1.3rem;">
                Total Simpanan
            </div>
        </div>
    </div>

</div>

<script>
    
    const rupiah = (number)=>{
        return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR"
        }).format(number);
    }

</script>