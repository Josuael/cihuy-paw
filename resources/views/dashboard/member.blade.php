@extends('layouts.main')

@section('content')

<h2 class="neon-text mb-4">
    <i class="fa-solid fa-house me-2"></i> Dashboard Member
</h2>

<!-- TOP SECTION -->
<div class="row mb-4">

    <!-- WALLET CARD -->
    <div class="col-md-4">
        <div class="card-dark p-4">
            <h5 class="text-white mb-3">Saldo Saya</h5>
            
            <h2 class="neon-text">
                Rp {{ number_format($wallet->balance ?? 0, 2, ',', '.') }}
            </h2>

            <a href="/wallet" class="btn btn-purple mt-3 w-100">
                <i class="fa-solid fa-wallet me-2"></i> Kelola Saldo
            </a>
        </div>
    </div>

    <!-- LOAN STATUS -->
    <div class="col-md-8">
        <div class="card-dark p-4">
            <h5 class="text-white mb-3">Status Pinjaman</h5>

            @if($loans->count() == 0)
                <p class="text-muted">Belum ada pinjaman aktif.</p>
                <a href="/loan-applications/create" class="btn btn-purple mt-2">
                    <i class="fa-solid fa-file-pen me-2"></i> Ajukan Pinjaman
                </a>
            @else
                <div class="row">
                    @foreach($loans as $loan)
                    <div class="col-md-6 mb-3">
                        <div class="card-dark p-3">
                            <h6 class="text-white">
                                <i class="fa-solid fa-hand-holding-dollar me-2"></i>
                                {{ $loan->loan_number }}
                            </h6>

                            <p class="text-muted small mb-1">
                                Total: Rp {{ number_format($loan->total_amount,0,',','.') }}
                            </p>

                            <p class="text-muted small">
                                Sisa: Rp {{ number_format($loan->remaining_balance,0,',','.') }}
                            </p>

                            <a href="/loan/{{ $loan->loan_id }}" class="btn btn-purple w-100">
                                Detail Pinjaman
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>


<!-- APPLICATION HISTORY -->
<div class="card-dark p-4">
    <h5 class="text-white mb-3">
        <i class="fa-solid fa-file-lines me-2"></i>
        Riwayat Pengajuan Pinjaman
    </h5>

    @if($applications->count() == 0)
        <p class="text-muted">Belum ada riwayat pengajuan.</p>
    @else
        <table class="table table-dark table-striped align-middle mt-3">
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Jumlah</th>
                    <th>Durasi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($applications as $app)
                <tr>
                    <td>{{ $app->application_number }}</td>
                    <td>Rp {{ number_format($app->request_amount,0,',','.') }}</td>
                    <td>{{ $app->duration_months }} bulan</td>
                    <td>
                        <span class="badge 
                            @if($app->status == 'Approved') bg-success 
                            @elseif($app->status == 'Rejected') bg-danger 
                            @elseif($app->status == 'Authorized') bg-info 
                            @elseif($app->status == 'Verified') bg-primary 
                            @else bg-secondary 
                            @endif
                        ">
                            {{ $app->status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
