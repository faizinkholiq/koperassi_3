<style>
    .card-link-dashboard{
        display: flex;
        justify-content: space-between;
        align-items: center;
        border:none; 
        color:white; 
        font-size:1.3rem; 
        width:100%;
        padding-right: 2rem;
        transition: padding-right 0.5s;
    }

    .card-link-dashboard:hover{
        text-decoration: none;
        padding-right: 1.2rem;
        color:white;
    }

    .card-link-red{
        background: #c0392b;
    }

    .card-link-yellow{
        background: #f39c12;
    }

    .card-link-blue{
        background: #2980b9;
    }

    .card-link-green{
        background: #16a085;
    }

    .card-link-purple{
        background: #8e44ad;
    }
</style>
<!-- Content Row -->
<div class="row">
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
            <a href="<?= site_url('simpanan/page/pokok'); ?>" class="card-link-dashboard card-link-red card-footer font-weight-bold">
                Simpanan Pokok
                <i class="float-right fa fa-chevron-right"></i>
            </a>
        </div>
    </div>
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
            <a href="<?= site_url('simpanan/page/wajib'); ?>" class="card-link-dashboard card-link-yellow card-footer font-weight-bold">
                Simpanan Wajib
                <i class="float-right fa fa-chevron-right"></i>
            </a>
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
            <a a href="<?= site_url('simpanan/page/sukarela'); ?>" class="card-link-dashboard card-link-blue card-footer font-weight-bold">
                Simpanan Sukarela
                <i class="float-right fa fa-chevron-right"></i>
            </a>
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
            <a href="<?= site_url('simpanan/page/investasi'); ?>" class="card-link-dashboard card-link-green card-footer font-weight-bold">
                Investasi
                <i class="float-right fa fa-chevron-right"></i>
            </a>
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
            <div class="card-link-dashboard card-link-purple card-footer font-weight-bold">
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