@extends('layouts.main')

@section('content')

<h2 class="neon-text mb-4">
    <i class="fa-solid fa-check-to-slot me-2"></i>
    Verifikasi Pengajuan Pinjaman
</h2>

<div class="card-dark p-4">

    @if ($apps->isEmpty())
        <p class="text-secondary">Tidak ada pengajuan menunggu verifikasi.</p>
    @else

    <table class="table table-dark table-striped align-middle">
        <thead>
            <tr>
                <th>No. Aplikasi</th>
                <th>Member</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Durasi</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($apps as $app)
            <tr>
                <td>{{ $app->application_number }}</td>
                <td>{{ $app->member->full_name }}</td>
                <td>{{ $app->application_date->format('d M Y') }}</td>
                <td>Rp {{ number_format($app->request_amount, 0, ',', '.') }}</td>
                <td>{{ $app->duration_months }} bulan</td>
                <td>
                    <form method="POST" action="/staff/loan-applications/{{ $app->application_id }}/verify">
                        @csrf
                        <button class="btn btn-purple btn-sm">
                            <i class="fa-solid fa-check"></i> Verifikasi
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @endif
</div>

@endsection
