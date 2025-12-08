@extends('layouts.main')

@section('content')

<h2 class="neon-text mb-4">
    <i class="fa-solid fa-house me-2"></i> Dashboard Staff Kredit
</h2>

<div class="row">

    <div class="col-md-4 mb-3">
        <div class="card-dark p-4 text-center">
            <h1 class="neon-text">{{ $pending }}</h1>
            <p class="text-secondary">Pengajuan Menunggu Verifikasi</p>
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
            <p class="text-secondary">Approve Top-Up Pending</p>
            <a href="/staff/topup" class="btn btn-purple w-100">
                Lihat Permintaan Top-Up
            </a>
        </div>
    </div>


    <div class="row mt-4">

    {{-- HISTORY PENGAJUAN --}}
        <div class="col-md-6 mb-4">
            <div class="card-dark p-4">
                <h5 class="text-white mb-3">
                    <i class="fa-solid fa-file-circle-plus me-2"></i> Pengajuan Terbaru
                </h5>
                <table class="table table-dark table-sm align-middle">
                    <thead>
                        <tr>
                            <th>No. FPP</th>
                            <th>Member</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentApplications as $app)
                            <tr>
                                <td>{{ $app->application_number }}</td>
                                <td>{{ $app->member->full_name ?? '-' }}</td>
                                <td>Rp {{ number_format($app->request_amount, 0, ',', '.') }}</td>
                                <td>{{ $app->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-secondary">
                                    Belum ada pengajuan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- HISTORY PEMBAYARAN --}}
        <div class="col-md-6 mb-4">
            <div class="card-dark p-4">
                <h5 class="text-white mb-3">
                    <i class="fa-solid fa-receipt me-2"></i> Pembayaran Terbaru
                </h5>
                <table class="table table-dark table-sm align-middle">
                    <thead>
                        <tr>
                            <th>No. BA</th>
                            <th>Member</th>
                            <th>Jumlah Bayar</th>
                            <th>Tgl Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $pay)
                            <tr>
                                <td>{{ $pay->payment_number }}</td>
                                <td>{{ $pay->loan->member->full_name ?? '-' }}</td>
                                <td>Rp {{ number_format($pay->amount_paid, 0, ',', '.') }}</td>
                                <td>{{ $pay->payment_date->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-secondary">
                                    Belum ada pembayaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>


</div>

@endsection
