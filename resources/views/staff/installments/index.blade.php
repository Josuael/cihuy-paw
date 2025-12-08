@extends('layouts.main')

@section('content')

<h2 class="neon-text mb-4">
    <i class="fa-solid fa-cash-register me-2"></i>
    Pembayaran Angsuran (Cash)
</h2>

<div class="card-dark p-4 mb-3">
    <h4 class="text-white">{{ $loan->loan_number }}</h4>
    <p class="text-secondary">Member: {{ $loan->member->full_name }}</p>
</div>

<div class="card-dark p-4">

<table class="table table-dark table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Jatuh Tempo</th>
            <th>Total</th>
            <th>Status</th>
            <th>Bayar Cash</th>
        </tr>
    </thead>

    <tbody>
        @foreach($loan->schedules as $s)
        <tr>
            <td>{{ $s->installment_number }}</td>
            <td>{{ $s->due_date }}</td>
            <td>Rp {{ number_format($s->total_amount,0,',','.') }}</td>
            <td>
                <span class="badge 
                    @if($s->status=='paid') bg-success
                    @elseif($s->status=='overdue') bg-danger
                    @else bg-warning text-dark
                    @endif
                ">
                    {{ ucfirst($s->status) }}
                </span>
            </td>
            <td>
                @if($s->status == 'pending')
                    <form method="POST" action="/staff/pay/{{ $s->schedule_id }}">
                        @csrf
                        <button class="btn btn-purple btn-sm">
                            <i class="fa-solid fa-money-bill"></i> Bayar Cash
                        </button>
                    </form>
                @else
                    <i class="fa-solid fa-check text-success"></i>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>

</table>

</div>

@endsection
