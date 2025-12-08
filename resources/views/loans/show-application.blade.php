@extends('layouts.main')

@section('content')

<div class="card-dark p-4 mb-4">
    <h2 class="neon-text mb-1">
        <i class="fa-solid fa-file-pen me-2"></i>
        Detail Pengajuan Pinjaman (FPP)
    </h2>
    <p class="text-secondary mb-0">
        Nomor FPP: <strong>{{ $app->application_number }}</strong><br>
        Atas nama: <strong>{{ $app->member->full_name ?? '-' }}</strong>
    </p>
</div>

<div class="card-dark p-4 mb-4">
    <div class="row">
        <div class="col-md-6 mb-3">
            <p class="text-muted mb-1">Jumlah Pengajuan</p>
            <p class="fs-5 fw-bold">
                Rp {{ number_format($app->request_amount, 0, ',', '.') }}
            </p>
        </div>

        <div class="col-md-3 mb-3">
            <p class="text-muted mb-1">Durasi</p>
            <p>{{ $app->duration_months }} bulan</p>
        </div>

        <div class="col-md-3 mb-3">
            <p class="text-muted mb-1">Status</p>
            <span class="badge bg-info">
                {{ $app->status }}
            </span>
        </div>
    </div>

    <hr>

    <p class="text-muted mb-1">Tujuan Pinjaman</p>
    <p>{{ $app->loan_purpose }}</p>

    <hr>

    <p class="text-muted mb-1">Tanggal Pengajuan</p>
    <p>{{ $app->created_at?->format('d M Y H:i') }}</p>

    <a href="{{ url('/loan-applications') }}" class="btn btn-outline-light mt-3">
        <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke daftar pengajuan
    </a>
</div>

@endsection
