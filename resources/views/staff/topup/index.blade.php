@extends('layouts.main')

@section('content')

<h2 class="neon-text mb-4">
    <i class="fa-solid fa-coins me-2"></i>
    Permintaan Top-Up Saldo
</h2>

<div class="card-dark p-4">

    @if ($requests->isEmpty())
        <p class="text-secondary">Tidak ada top-up pending.</p>
    @else

    <table class="table table-dark table-striped align-middle">
        <thead>
            <tr>
                <th>Member</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Bukti Transfer</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($requests as $req)
            <tr>
                <td>{{ $req->member->full_name }}</td>
                <td>Rp {{ number_format($req->amount, 0, ',', '.') }}</td>
                <td>{{ $req->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ asset('storage/'.$req->transfer_proof) }}" target="_blank" class="text-info">
                        Lihat Bukti
                    </a>
                </td>
                <td class="d-flex gap-2">

                    <form method="POST" action="/staff/topup/{{ $req->id }}/approve">
                        @csrf
                        <button class="btn btn-success btn-sm">
                            <i class="fa-solid fa-check"></i>
                        </button>
                    </form>

                    <form method="POST" action="/staff/topup/{{ $req->id }}/reject">
                        @csrf
                        <button class="btn btn-danger btn-sm">
                            <i class="fa-solid fa-xmark"></i>
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
