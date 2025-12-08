@extends('layouts.main')

@section('content')

<h2 class="neon-text mb-4">
    <i class="fa-solid fa-file-lines me-2"></i>
    Daftar Pengajuan Pinjaman Saya
</h2>

<div class="card-dark p-4">

    @if($apps->count() == 0)
        <p class="text-secondary">Belum ada pengajuan pinjaman.</p>
        <a href="/loan-applications/create" class="btn btn-purple mt-3">
            Ajukan Pinjaman Baru
        </a>
    @else

    <table class="table table-dark table-striped align-middle">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Jumlah</th>
                <th>Durasi</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach($apps as $app)
            <tr>
                <td>{{ $app->application_number }}</td>
                <td>Rp {{ number_format($app->request_amount,0,',','.') }}</td>
                <td>{{ $app->duration_months }} bulan</td>
                <td>
                    <span class="badge 
                        @if($app->status=='Approved') bg-success
                        @elseif($app->status=='Rejected') bg-danger
                        @elseif($app->status=='Verified') bg-primary
                        @elseif($app->status=='Authorized') bg-info
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
