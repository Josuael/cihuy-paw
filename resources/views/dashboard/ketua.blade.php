@extends('layouts.main')

@section('content')

<h2 class="neon-text mb-4">
    <i class="fa-solid fa-house me-2"></i> Dashboard Ketua
</h2>

<div class="row mt-4">

    <div class="col-md-6 mb-4">
        <div class="card-dark p-4 text-center">
            <h1 class="neon-text">{{ $activeLoans }}</h1>
            <p class="text-light">Total Pinjaman Aktif</p>
        </div>
    </div>

        {{-- MENUNGGU PERSETUJUAN KETUA --}}
    <div class="col-md-6 mb-4">
        <div class="card-dark p-4">
            <h5 class="text-white mb-3">
                <i class="fa-solid fa-gavel me-2"></i>
                Pengajuan Menunggu Persetujuan
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
                    @forelse($waitingApprovals as $app)
                        <tr>
                            <td>{{ $app->application_number }}</td>
                            <td>{{ $app->member->full_name ?? '-' }}</td>
                            <td>Rp {{ number_format($app->request_amount, 0, ',', '.') }}</td>
                            <td>{{ $app->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-danger">
                                Tidak ada pengajuan menunggu persetujuan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PINJAMAN YANG SUDAH DISETUJUI / AKTIF --}}
    <div class="col-md-6 mb-4">
        <div class="card-dark p-4">
            <h5 class="text-white mb-3">
                <i class="fa-solid fa-list-check me-2"></i>
                Pinjaman Disetujui Terbaru
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
                    @forelse($recentApprovedLoans as $loan)
                        <tr>
                            <td>{{ $loan->loan_number }}</td>
                            <td>{{ $loan->member->full_name ?? '-' }}</td>
                            <td>Rp {{ number_format($loan->principal_amount, 0, ',', '.') }}</td>
                            <td>{{ $loan->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Belum ada pinjaman disetujui.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
