@extends('layouts.main')

@section('content')

<div class="card-dark p-4 mb-4">
    <h2 class="neon-text mb-0">
        <i class="fa-solid fa-wallet me-2"></i> Saldo Koperasi Saya
    </h2>
    <p class="text-muted mb-0">
        Lihat saldo, riwayat transaksi, dan ajukan top up saldo.
    </p>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card-dark p-4 text-center">
            <h5 class="text-white mb-3">Saldo Saat Ini</h5>
            <h1 class="neon-text">
                Rp {{ number_format($wallet->balance, 2, ',', '.') }}
            </h1>
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <div class="card-dark p-4">
            <h5 class="text-white mb-3">
                <i class="fa-solid fa-coins me-2"></i> Ajukan Top Up Saldo
            </h5>

            <form action="{{ route('wallet.topup.request') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-white">Jumlah Top Up</label>
                    <div class="input-group">
                        <span class="input-group-text bg-dark text-white border-secondary">Rp</span>
                        <input type="number" name="amount" min="10000"
                               class="form-control bg-dark text-white border-secondary"
                               placeholder="contoh: 200000" required>
                    </div>
                    <small class="text-muted">Minimal Rp 10.000</small>
                    @error('amount') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-purple w-100">
                    <i class="fa-solid fa-paper-plane me-2"></i>
                    Kirim Permintaan Top Up
                </button>
            </form>
        </div>
    </div>
</div>

<div class="card-dark p-4">
    <h5 class="text-white mb-3">
        <i class="fa-solid fa-clock-rotate-left me-2"></i> Riwayat Transaksi
    </h5>

    <table class="table table-dark table-striped table-sm align-middle">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $tx)
                <tr>
                    <td>{{ $tx->created_at->format('d M Y H:i') }}</td>
                    <td>{{ ucfirst($tx->type) }}</td>
                    <td>
                        @if($tx->type === 'payment')
                            - Rp {{ number_format($tx->amount, 2, ',', '.') }}
                        @else
                            + Rp {{ number_format($tx->amount, 2, ',', '.') }}
                        @endif
                    </td>
                    <td>{{ ucfirst($tx->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        Belum ada transaksi.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
