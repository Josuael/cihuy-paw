@extends('layouts.main')

@section('content')

<h2 class="neon-text mb-4">
    <i class="fa-solid fa-shield-check me-2"></i>
    Otorisasi Pengajuan Pinjaman
</h2>

<div class="card-dark p-4">

    @if ($apps->count() == 0)
        <p class="text-danger">Tidak ada pengajuan yang menunggu otorisasi admin!</p>
    @else
        <table class="table table-dark table-striped align-middle">
            <thead>
            <tr>
                <th>Nomor FPP</th>
                <th>Member</th>
                <th>Jumlah</th>
                <th>Durasi</th>
                <th>Verifikasi</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($apps as $app)
                <tr>
                    <td>{{ $app->application_number }}</td>
                    <td>{{ $app->member->full_name }}</td>
                    <td>Rp {{ number_format($app->request_amount,0,',','.') }}</td>
                    <td>{{ $app->duration_months }} bulan</td>
                    <td>{{ $app->verified_at->format('d M Y H:i') }}</td>
                    <td>
                        <button type="button"
                                class="btn btn-purple btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#authorize{{ $app->application_id }}">
                            <i class="fa-solid fa-check"></i> Otorisasi
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{-- Semua modal didefinisikan di luar table --}}
        @foreach ($apps as $app)
            <div class="modal fade"
                 id="authorize{{ $app->application_id }}"
                 tabindex="1"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content"
                         style="background:#1e2030; border:1px solid rgba(255,255,255,0.1)">

                        <div class="modal-header">
                            <h5 class="modal-title text-white">
                                Otorisasi Pengajuan: {{ $app->application_number }}
                            </h5>
                            <button type="button"
                                    class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>

                        <div class="modal-body text-light">
                            <p><strong>Nama Member:</strong> {{ $app->member->full_name }}</p>
                            <p><strong>Jumlah:</strong> Rp {{ number_format($app->request_amount,0,',','.') }}</p>
                            <p><strong>Durasi:</strong> {{ $app->duration_months }} bulan</p>

                            <p><strong>Tujuan:</strong></p>
                            <p class="text-light">{{ $app->loan_purpose }}</p>

                            <hr>

                            <h6 class="text-white mb-3">
                                <i class="fa-solid fa-paperclip me-2"></i>
                                Dokumen Pendukung
                            </h6>

                            @if ($app->supportingDocuments->isEmpty())
                                <p class="text-danger">Belum ada dokumen yang diupload.</p>
                            @else
                                <ul class="list-group list-group-flush">
                                    @foreach ($app->supportingDocuments as $doc)
                                        <li class="list-group-item bg-transparent text-light d-flex justify-content-between align-items-center border-secondary">
                                            <div>
                                                <strong>{{ $doc->doc_type }}</strong><br>
                                                <small class="text-muted">
                                                    {{ $doc->file_name }} 
                                                    ({{ number_format($doc->file_size / 1024, 0) }} KB)
                                                </small>
                                            </div>
                                            <a href="{{ asset('storage/'.$doc->file_path) }}" 
                                            target="_blank" 
                                            class="btn btn-sm btn-outline-info">
                                                <i class="fa-solid fa-download me-1"></i> Lihat
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif


                            <p class="text-info">
                                <i class="fa-solid fa-circle-info me-2"></i>
                                Admin hanya mengotorisasi. Persetujuan final dilakukan oleh Ketua.
                            </p>
                        </div>

                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">
                                Batal
                            </button>

                            <form action="/admin/authorization/{{ $app->application_id }}/authorize"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-purple">
                                    Otorisasi Sekarang
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    @endif

</div>

@endsection
