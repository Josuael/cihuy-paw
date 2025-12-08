@extends('layouts.main')

@section('content')

<!-- HEADER -->
<div class="card-dark p-4 mb-4">
    <h2 class="neon-text">
        <i class="fa-solid fa-file-pen me-2"></i>
        Ajukan Pinjaman Baru
    </h2>
    <p class="text-muted mb-0">
        Isi formulir berikut untuk mengajukan pinjaman koperasi.
    </p>
</div>


<!-- FORM START -->
<div class="card-dark p-4">

    <form action="/loan-applications" method="POST">
        @csrf

        <div class="row">
            
            <!-- JUMLAH PINJAMAN -->
            <div class="col-md-6 mb-4">
                <label class="form-label text-white">Jumlah Pinjaman</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark text-white border-secondary">Rp</span>
                    <input type="number" 
                           name="amount" 
                           class="form-control bg-dark text-white border-secondary"
                           placeholder="contoh: 5000000"
                           min="1000000"
                           required>
                </div>
                <small class="text-muted">Minimal Rp 1.000.000</small>
            </div>

            <!-- DURASI -->
            <div class="col-md-6 mb-4">
                <label class="form-label text-white">Durasi (bulan)</label>
                <input type="number" 
                       name="months"
                       class="form-control bg-dark text-white border-secondary"
                       placeholder="contoh: 12"
                       min="1"
                       required>
            </div>

        </div>

        <!-- TUJUAN PINJAMAN -->
        <div class="mb-4">
            <label class="form-label text-white">Tujuan Pinjaman</label>
            <textarea name="purpose"
                      class="form-control bg-dark text-white border-secondary"
                      rows="4"
                      placeholder="contoh: biaya pendidikan, renovasi rumah, dll."
                      required></textarea>
        </div>

        <!-- BUTTON -->
        <button type="submit" class="btn btn-purple w-100">
            <i class="fa-solid fa-paper-plane me-2"></i>
            Kirim Pengajuan
        </button>

    </form>

</div>

@endsection
