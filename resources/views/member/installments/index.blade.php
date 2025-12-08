@extends('layouts.main')

@section('content')

<h2 class="neon-text mb-4">
    <i class="fa-solid fa-receipt me-2"></i>
    Pembayaran Angsuran
</h2>

@foreach($loans as $loan)
<div class="card-dark p-4 mb-4">

    <h4 class="text-white">{{ $loan->loan_number }}</h4>
    <p class="text-muted">Sisa Pinjaman:
        <span class="text-warning">
            Rp {{ number_format($loan->remaining_balance,0,',','.') }}
        </span>
    </p>

    <table class="table table-dark table-striped mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Jatuh Tempo</th>
                <th>Total</th>
                <th>Status</th>
                <th>Bayar</th>
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
                    @if(auth()->user()->role === 'member')
                        @if($s->status == 'pending')
                            <form method="POST" action="/member/pay/{{ $s->schedule_id }}">
                                @csrf
                                <button class="btn btn-purple btn-sm">Bayar</button>
                            </form>
                        @else
                            <i class="fa-solid fa-check text-success"></i>
                        @endif
                    @endif

                </td>
            </tr>
            @endforeach

        </tbody>
    </table>

</div>
@endforeach

@endsection
