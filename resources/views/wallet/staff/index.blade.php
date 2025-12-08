@extends('layouts.main')

@section('content')

<h2 class="neon-text mb-4">
    <i class="fa-solid fa-coins me-2"></i> Persetujuan Top-Up Member
</h2>

<div class="card-dark p-4">

    @if($pending->count() == 0)
        <p class="text-secondary">Tidak ada permintaan top-up.</p>
    @else

    <table class="table table-dark table-striped align-middle">
        <thead>
            <tr>
                <th>Member</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($pending as $trx)
            <tr>
                <td>{{ $trx->wallet->member->full_name }}</td>
                <td>Rp {{ number_format($trx->amount,0,',','.') }}</td>
                <td>{{ $trx->created_at->format('d M Y H:i') }}</td>

                <td>
                    <div class="d-flex gap-2">
                        <form action="/staff/topup/{{ $trx->transaction_id }}/approve" method="POST">
                            @csrf
                            <button class="btn btn-success btn-sm">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </form>

                        <form action="/staff/topup/{{ $trx->transaction_id }}/reject" method="POST">
                            @csrf
                            <button class="btn btn-danger btn-sm">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </form>
                    </div>
                </td>

            </tr>
            @endforeach
        </tbody>

    </table>
    @endif

</div>

@endsection
