@extends('layouts.main')

@section('content')

<div class="card-dark p-4 mb-4">
    <h2 class="neon-text mb-1">
        <i class="fa-solid fa-file-contract me-2"></i>
        Detail Pinjaman {{ $loan->loan_number }}
    </h2>
    <p class="text-secondary mb-0">
        Atas nama: <strong>{{ $loan->member->full_name ?? '-' }}</strong>
    </p>
</div>

<div class="row">
    {{-- RINGKASAN PINJAMAN --}}
    <div class="col-md-4 mb-4">
        <div class="card-dark p-4">
            <h5 class="text-white mb-3">Ringkasan Pinjaman</h5>

            <p class="mb-1 text-secondary">Plafon</p>
            <p class="fw-bold">
                Rp {{ number_format($loan->principal_amount, 0, ',', '.') }}
            </p>

            <p class="mb-1 text-secondary">Tenor</p>
            <p>{{ $loan->duration_months }} bulan</p>

            <p class="mb-1 text-secondary">Angsuran per bulan</p>
            <p>Rp {{ number_format($loan->monthly_installment, 0, ',', '.') }}</p>

            <p class="mb-1 text-secondary">Total Bunga</p>
            <p>Rp {{ number_format($loan->total_interest, 0, ',', '.') }}</p>

            <p class="mb-1 text-secondary">Sisa Pokok</p>
            <p>Rp {{ number_format($loan->remaining_balance, 0, ',', '.') }}</p>

            <p class="mb-1 text-secondary">Status</p>
            <span class="badge bg-info">{{ $loan->status }}</span>

            <hr>

            <a href="{{ url('/pdf/bp/'.$loan->loan_id) }}"
               class="btn btn-purple w-100" target="_blank">
                <i class="fa-solid fa-file-pdf me-2"></i>
                Download Bukti Peminjaman (BP)
            </a>
        </div>
    </div>

    {{-- JADWAL ANGSURAN + PEMBAYARAN --}}
    <div class="col-md-8 mb-4">

        {{-- JADWAL --}}
        <div class="card-dark p-4 mb-4">
            <h5 class="text-white mb-3">
                <i class="fa-solid fa-list-ol me-2"></i>
                Jadwal Angsuran
            </h5>

            <div class="card-dark p-3">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jatuh Tempo</th>
                            <th>Pokok</th>
                            <th>Bunga</th>
                            <th>Total</th>
                            <th>Status</th>
                            @if(auth()->user()->role === 'member')
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($schedules as $s)
                        <tr>
                            <td>{{ $s->installment_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($s->due_date)->format('d M Y') }}</td>
                            <td>Rp {{ number_format($s->principal_amount, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($s->interest_amount, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($s->total_amount, 0, ',', '.') }}</td>

                            <td>
                                @if($s->status === 'paid')
                                    <span class="badge bg-success">Sudah Dibayar</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Dibayar</span>
                                @endif
                            </td>

                            <td>
                                @if($s->status === 'pending')
                                    @if($walletBalance >= $s->total_amount)
                                        <form action="{{ route('member.pay', $s->schedule_id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-purple">
                                                Bayar
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-warning small">Saldo tidak cukup</span>
                                    @endif
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Jadwal angsuran belum dibuat.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>


        {{-- PEMBAYARAN --}}
        <div class="card-dark p-4">
            <h5 class="text-white mb-3">
                <i class="fa-solid fa-clock-rotate-left me-2"></i>
                Riwayat Pembayaran
            </h5>

            <table class="table table-dark table-sm align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>No. Bukti</th>
                        <th>Jumlah Dibayar</th>
                        <th>Metode</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($payments as $p)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($p->payment_date)->format('d M Y') }}</td>
                        <td>{{ $p->payment_number }}</td>
                        <td>Rp {{ number_format($p->amount_paid, 0, ',', '.') }}</td>
                        <td>{{ $p->payment_method }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            Belum ada pembayaran angsuran.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection
