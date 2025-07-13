<div class="row">
    <div class="col-lg-3 col-sm-6">
        <div class="card border-success">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3">
                        <div class="bg-success text-white p-3 rounded text-center">
                            <i class="bi bi-person-fill-check" style="font-size: 22px"></i>
                        </div>
                    </div>
                    <div class="col-9">
                        <h6 class="text-muted">Total Pengguna</h6>
                        <h2 class="mb-0">{{ $jmlUser }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card border-primary">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3">
                        <div class="bg-primary text-white p-3 rounded text-center">
                            <i class="bi bi-person-vcard" style="font-size: 22px"></i>
                        </div>
                    </div>
                    <div class="col-9">
                        <h6 class="text-muted">Total Mahasiswa</h6>
                        <h2 class="mb-0">{{ $jmlMhs }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card border-secondary">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3">
                        <div class="bg-secondary text-white p-3 rounded text-center">
                            <i class="bi bi-person" style="font-size: 22px"></i>
                        </div>
                    </div>
                    <div class="col-9">
                        <h6 class="text-muted">Total Laki-Laki</h6>
                        <h2 class="mb-0">{{ $jmlLaki }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card border-warning">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3">
                        <div class="bg-warning text-white p-3 rounded text-center">
                            <i class="bi bi-person" style="font-size: 22px"></i>
                        </div>
                    </div>
                    <div class="col-9">
                        <h6 class="text-muted">Total Perempuan</h6>
                        <h2 class="mb-0">{{ $jmlP }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
</div>