@extends('layouts.main')

@section('content')

<h2 class="neon-text mb-4">
    <i class="fa-solid fa-chart-line me-2"></i>
    Dashboard Admin — Analitik Koperasi
</h2>

<!-- STAT CARDS -->
<div class="row mb-4">

    <!-- TOTAL LOANS -->
    <div class="col-md-3 mb-3">
        <div class="card-dark p-4 text-center">
            <h2 class="neon-text">{{ $totalLoans }}</h2>
            <p class="neon-text">Total Pinjaman Aktif</p>
        </div>
    </div>

    <!-- ACTIVE BORROWERS -->
    <div class="col-md-3 mb-3">
        <div class="card-dark p-4 text-center">
            <h2 class="text-info">{{ $activeBorrowers }}</h2>
            <p class="neon-text">Anggota Sedang Meminjam</p>
        </div>
    </div>

    <!-- OVERDUE -->
    <div class="col-md-3 mb-3">
        <div class="card-dark p-4 text-center">
            <h2 class="text-danger">{{ $overdueCount }}</h2>
            <p class="neon-text">Tunggakan</p>
        </div>
    </div>

    <!-- MONTHLY REVENUE -->
    <div class="col-md-3 mb-3">
        <div class="card-dark p-4 text-center">
            <h2 class="neon-text">Rp {{ number_format($monthlyIncome,0,',','.') }}</h2>
            <p class="neon-text">Angsuran Masuk Bulan Ini</p>
        </div>
    </div>

</div>

{{-- HISTORY PENGAJUAN --}}
<div class="row mt-4">
    <div class="col-md-6 mb-4">
        <div class="card-dark p-4">
            <h5 class="text-white mb-3">
                <i class="fa-solid fa-file-circle-plus me-2"></i>
                Pengajuan Pinjaman Terbaru
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
                            <td colspan="4" class="text-center text-muted">
                                Belum ada pengajuan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- HISTORY PINJAMAN DICAIRKAN --}}
    <div class="col-md-6 mb-4">
        <div class="card-dark p-4">
            <h5 class="text-white mb-3">
                <i class="fa-solid fa-hand-holding-dollar me-2"></i>
                Pinjaman Dicairkan Terbaru
            </h5>

            <table class="table table-dark table-sm align-middle">
                <thead>
                    <tr>
                        <th>No. Pinjaman</th>
                        <th>Member</th>
                        <th>Plafon</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLoans as $loan)
                        <tr>
                            <td>{{ $loan->loan_number }}</td>
                            <td>{{ $loan->member->full_name ?? '-' }}</td>
                            <td>Rp {{ number_format($loan->principal_amount, 0, ',', '.') }}</td>
                            <td>{{ $loan->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Belum ada pinjaman aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>



<!-- ANALYTICS CHARTS -->
<div class="row">

    <!-- LOANS PER MONTH -->
    <div class="col-md-6 mb-4">
        <div class="card-dark p-4">
            <h5 class="text-white mb-3">Pinjaman Baru Per Bulan</h5>
            <canvas id="loansChart"></canvas>
        </div>
    </div>

    <!-- PAYMENTS PER MONTH -->
    <div class="col-md-6 mb-4">
        <div class="card-dark p-4">
            <h5 class="text-white mb-3">Angsuran Masuk Per Bulan</h5>
            <canvas id="paymentsChart"></canvas>
        </div>
    </div>

</div>

@endsection
