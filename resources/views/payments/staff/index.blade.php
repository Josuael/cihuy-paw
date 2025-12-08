@extends('layouts.main')

@section('content')

<h2 class="neon-text mb-4">
    <i class="fa-solid fa-file-invoice-dollar me-2"></i>
    Input Pembayaran Angsuran (Manual)
</h2>

<div class="card-dark p-4">

    @if($pending->count() == 0)
        <p class="text-muted">Tidak ada angsuran pending.</p>

    @else

    <table class="table table-dark table-striped align-middle">
        <thead>
            <tr>
                <th>Pinjaman</th>
                <th>Angsuran</th>
                <th>Jatuh Tempo</th>
                <th>Jumlah</th>
                <th>Bayar</th>
            </tr>
        </thead>

        <tbody>

        @foreach($pending as $s)
            <tr>
                <td>{{ $s->loan->loan_number }}</td>
                <td>{{ $s->installment_number }}</td>
                <td>{{ \Carbon\Carbon::parse($s->due_date)->format('d M Y') }}</td>
                <td>Rp {{ number_format($s->total_amount,0,',','.') }}</td>

                <td>
                    <form action="/staff/pay/{{ $s->schedule_id }}" method="POST">
                        @csrf
                        <button class="btn btn-purple btn-sm">
                            Input BA
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>

    @endif

    @if(auth()->user()->role === 'staff' && $schedule->status == 'pending')
        <form method="POST" action="/staff/pay/{{ $schedule->schedule_id }}">
            @csrf
            <button class="btn btn-purple btn-sm">Bayar Cash</button>
        </form>
    @endif


</div>

@endsection
