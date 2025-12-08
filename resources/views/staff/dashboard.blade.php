<!-- @extends('layouts.main')

@section('content')

<h2 class="neon-text mb-4">
    <i class="fa-solid fa-house me-2"></i> Dashboard Staff Kredit
</h2>

<div class="row">

    <div class="col-md-4 mb-3">
        <div class="card-dark p-4 text-center">
            <h1 class="neon-text">{{ $pending }}</h1>
            <p class="text-muted">Pengajuan Menunggu Verifikasi</p>
            <a href="/staff/loan-applications" class="btn btn-purple mt-2 w-100">
                Verifikasi Sekarang
            </a>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card-dark p-4 text-center">
            <h1 class="text-info">{{ $verified }}</h1>
            <p class="text-secondary">Pengajuan Sudah Diverifikasi</p>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card-dark p-4 text-center">
            <h1 class="neon-text"><i class="fa-solid fa-coins"></i></h1>
            <p class="text-muted">Approve Top-Up Pending</p>
            <a href="/staff/topup" class="btn btn-purple w-100">
                Lihat Permintaan Top-Up
            </a>
        </div>
    </div>

</div>

@endsection -->
