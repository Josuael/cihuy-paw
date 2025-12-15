@extends('layouts.main')

@section('content')

<h2 class="neon-text mb-4">
    <i class="fa-solid fa-gavel me-2"></i>
    Approval Final Pengajuan Pinjaman
</h2>

<div class="card-dark p-4">

    @if ($apps->count() == 0)
        <p class="text-danger">Tidak ada pengajuan yang menunggu persetujuan akhir.</p>

    @else

    <table class="table table-dark table-striped align-middle">
        <thead>
            <tr>
                <th>Nomor FPP</th>
                <th>Member</th>
                <th>Jumlah</th>
                <th>Durasi</th>
                <th>Otorisasi Admin</th>
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
                <td>{{ $app->updated_at->format('d M Y H:i') }}</td>

                <td>
                    <div class="d-flex gap-2">

                        <!-- Approve Button -->
                        <button class="btn btn-success btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#approve{{ $app->application_id }}">
                            <i class="fa-solid fa-check"></i>
                        </button>

                        <!-- Reject Button -->
                        <button class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#reject{{ $app->application_id }}">
                            <i class="fa-solid fa-xmark"></i>
                        </button>

                    </div>
                </td>
            </tr>


            <!-- APPROVAL MODAL -->
            <div class="modal fade" id="approve{{ $app->application_id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content"
                         style="background:#1e2030; border:1px solid rgba(255,255,255,0.1)">

                        <div class="modal-header">
                            <h5 class="modal-title text-white">
                                Setujui Pinjaman: {{ $app->application_number }}
                            </h5>
                            <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body text-light">
                            <p><strong>Nama Member:</strong> {{ $app->member->full_name }}</p>
                            <p><strong>Jumlah:</strong> Rp {{ number_format($app->request_amount,0,',','.') }}</p>
                            <p><strong>Durasi:</strong> {{ $app->duration_months }} bulan</p>

                            <hr>

                            <h6 class="text-white mb-3">
                                <i class="fa-solid fa-paperclip me-2"></i>
                                Dokumen Pendukung
                            </h6>

                            @if ($app->supportingDocuments->isEmpty())
                                <p class="text-danger">Pengajuan ini belum memiliki dokumen pendukung.</p>
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


                            <p class="text-success">
                                <i class="fa-solid fa-check-circle me-2"></i>
                                Menyetujui pengajuan ini akan menghasilkan nomor pinjaman baru,
                                menghitung bunga otomatis, dan membuat jadwal angsuran.
                            </p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>

                            <form action="/ketua/approval/{{ $app->application_id }}/approve" method="POST">
                                @csrf
                                <button class="btn btn-purple">
                                    Setujui Pinjaman
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <!-- END APPROVE MODAL -->


            <!-- REJECT MODAL -->
            <div class="modal fade" id="reject{{ $app->application_id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content"
                         style="background:#1e2030; border:1px solid rgba(255,255,255,0.1)">

                        <div class="modal-header">
                            <h5 class="modal-title text-white">
                                Tolak Pengajuan: {{ $app->application_number }}
                            </h5>
                            <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <form method="POST" action="/ketua/approval/{{ $app->application_id }}/reject">
                            @csrf

                            <div class="modal-body text-light">
                                <label class="text-light mb-2">Alasan Penolakan</label>
                                <textarea name="reason"
                                          class="form-control bg-dark text-white border-secondary"
                                          rows="3"
                                          required
                                          placeholder="Contoh: Penghasilan tidak mencukupi, data kurang lengkap..."></textarea>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button class="btn btn-danger">
                                    Tolak Pengajuan
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
            <!-- END REJECT MODAL -->


        @endforeach

        </tbody>
    </table>

    @endif

</div>

@endsection
