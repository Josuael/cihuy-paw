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

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/loan-applications" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-4">
                <label class="form-label text-white">Jumlah Pinjaman</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark text-white border-secondary">Rp</span>
                    <input type="number"
                           name="amount"
                           class="form-control bg-dark text-white border-secondary"
                           placeholder="contoh: 5000000"
                           min="1000000"
                           value="{{ old('amount') }}"
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
                       value="{{ old('months') }}"
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
                      required>{{ old('purpose') }}</textarea>
        </div>

        <!-- DOKUMEN PENDUKUNG -->
        <hr class="my-4">

        <h5 class="text-white mb-3">
            <i class="fa-solid fa-paperclip me-2"></i>
            Dokumen Pendukung
        </h5>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label text-white">KTP (jpg, png, pdf)</label>
                <input type="file" name="ktp" class="form-control bg-dark text-white border-secondary">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label text-white">KK (jpg, png, pdf)</label>
                <input type="file" name="kk" class="form-control bg-dark text-white border-secondary">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label text-white">Slip Gaji (jpg, png, pdf)</label>
                <input type="file" name="slip_gaji" class="form-control bg-dark text-white border-secondary">
            </div>
        </div>

        <small class="text-muted">
            Maks 2MB per file. Boleh salah satu / beberapa saja sesuai kebijakan koperasi.
        </small>

        <!-- BUTTON -->
        <button type="submit" class="btn btn-purple w-100 mt-4">
            <i class="fa-solid fa-paper-plane me-2"></i>
            Kirim Pengajuan
        </button>

    </form>

</div>

@endsection
