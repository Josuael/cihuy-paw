@extends('layouts.main')

@section('content')

<h2 class="neon-text mb-4">
    <i class="fa-solid fa-money-bill-wave me-2"></i>
    Pembayaran Angsuran
</h2>

<div class="card-dark p-4 mb-4">

    <h4 class="text-white mb-2">{{ $loan->loan_number }}</h4>

    <p class="text-muted mb-1">Total Pinjaman: 
        <span class="text-info">Rp {{ number_format($loan->total_amount,0,',','.') }}</span>
    </p>

    <p class="text-muted mb-0">Sisa: 
        <span class="text-warning">Rp {{ number_format($loan->remaining_balance,0,',','.') }}</span>
    </p>

</div>


<div class="card-dark p-4">

    <h5 class="mb-3 text-white">Jadwal Angsuran</h5>

    <table class="table table-dark table-striped align-middle">
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
                <td>{{ \Carbon\Carbon::parse($s->due_date)->format('d M Y') }}</td>
                <td>Rp {{ number_format($s->total_amount,0,',','.') }}</td>

                <td>
                    <span class="badge 
                        @if($s->status == 'paid') bg-success
                        @elseif($s->status == 'overdue') bg-danger
                        @else bg-warning text-dark
                        @endif
                    ">
                        {{ ucfirst($s->status) }}
                    </span>
                </td>

                <td>
                    @if($s->status == 'pending')
                        <button class="btn btn-purple btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#pay{{ $s->schedule_id }}">
                            Bayar
                        </button>
                    @else
                        <i class="fa-solid fa-check text-success"></i>
                    @endif
                </td>
            </tr>

            <!-- PAYMENT MODAL -->
            <div class="modal fade" id="pay{{ $s->schedule_id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content"
                         style="background:#1e2030; border:1px solid rgba(255,255,255,0.1)">

                        <div class="modal-header">
                            <h5 class="modal-title text-white">
                                Konfirmasi Pembayaran
                            </h5>
                            <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body text-light">
                            <p><strong>Jatuh Tempo:</strong> 
                                {{ \Carbon\Carbon::parse($s->due_date)->format('d M Y') }}
                            </p>

                            <p><strong>Total Pembayaran:</strong></p>
                            <h3 class="neon-text">
                                Rp {{ number_format($s->total_amount,0,',','.') }}
                            </h3>

                            <hr>

                            <p class="text-info">
                                <i class="fa-solid fa-wallet me-2"></i>
                                Pembayaran akan dipotong dari saldo E-Wallet Anda.
                            </p>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>

                            <form action="/wallet/pay/{{ $s->schedule_id }}" method="POST">
                                @csrf
                                <button class="btn btn-purple">
                                    Bayar Sekarang
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        @endforeach

        </tbody>
    </table>

    @if(auth()->user()->role === 'member' && $s->status == 'pending')
        <form method="POST" action="/member/pay/{{ $s->schedule_id }}">
            @csrf
            <button class="btn btn-purple btn-sm">Bayar</button>
        </form>
    @endif


</div>

@endsection
