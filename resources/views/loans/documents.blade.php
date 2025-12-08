@extends('layouts.main')

@section('content')

<h2 class="neon-text mb-4">
    <i class="fa-solid fa-file-arrow-up me-2"></i>
    Upload Dokumen Pendukung
</h2>

<div class="card-dark p-4 mb-4">

    <h4 class="text-white">{{ $application->application_number }}</h4>
    <p class="text-muted mb-2">Nama: {{ $application->member->full_name }}</p>

    <p class="text-info">
        <i class="fa-solid fa-circle-info me-2"></i>
        Unggah semua dokumen yang dibutuhkan untuk proses verifikasi.
    </p>

</div>


<div class="card-dark p-4">

    @if(auth()->user()->role === 'member')
        <!-- UPLOAD FORM -->
        <form action="/loan-applications/{{ $application->application_id }}/upload" 
              method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label text-light">Jenis Dokumen</label>
                <select name="doc_type" class="form-control bg-dark text-white border-secondary" required>
                    <option value="">-- Pilih --</option>
                    <option>KTP</option>
                    <option>KTM</option>
                    <option>Slip Gaji</option>
                    <option>Surat Keterangan Kerja</option>
                    <option>Lainnya</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label text-light">Upload File</label>
                <input type="file" name="file" 
                       class="form-control bg-dark text-white border-secondary"
                       required>
                <small class="text-muted">Format: PDF/JPG/PNG | Max 5 MB</small>
            </div>

            <button type="submit" class="btn btn-purple w-100">
                <i class="fa-solid fa-upload me-2"></i> Upload Dokumen
            </button>

        </form>
    @else
        <p class="text-muted">Hanya member yang dapat mengunggah dokumen pendukung.</p>
    @endif

</div>



<!-- LIST OF DOCUMENTS -->
<div class="card-dark p-4 mt-4">

    <h5 class="text-white mb-3">
        <i class="fa-solid fa-folder-open me-2"></i>
        Dokumen Terunggah
    </h5>

    @if($documents->count() == 0)
        <p class="text-muted">Belum ada dokumen yang diupload.</p>

    @else

    <table class="table table-dark table-striped align-middle">
        <thead>
            <tr>
                <th>Jenis</th>
                <th>Nama File</th>
                <th>Ukuran</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($documents as $doc)
            <tr>
                <td>{{ $doc->doc_type }}</td>
                <td>{{ $doc->file_name }}</td>
                <td>{{ number_format($doc->file_size / 1024, 1) }} KB</td>

                <td>
                    <a href="{{ asset('storage/' . $doc->file_path) }}" 
                       class="btn btn-purple btn-sm"
                       target="_blank">
                        <i class="fa-solid fa-eye"></i> Lihat
                    </a>
                </td>

                    <!-- BTN PREVIEW (MODAL) -->
                <button class="btn btn-purple btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#preview{{ $doc->doc_id }}">
                    <i class="fa-solid fa-eye"></i> Preview
                </button>

                <!-- BTN DOWNLOAD (optional)-->
                <a href="{{ asset('storage/' . $doc->file_path) }}" 
                class="btn btn-info btn-sm" 
                download>
                    <i class="fa-solid fa-download"></i>
                </a>

            </tr>
            @endforeach
        </tbody>

        <!-- MODAL PREVIEW -->
        <div class="modal fade" id="preview{{ $doc->doc_id }}" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" 
                    style="background:#1e2030; border:1px solid rgba(255,255,255,0.1)">

                    <div class="modal-header">
                        <h5 class="modal-title text-white">
                            Preview Dokumen: {{ $doc->doc_type }}
                        </h5>
                        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <!-- PDF VIEWER -->
                        @if(Str::endsWith($doc->file_name, ['pdf','PDF']))
                            <iframe src="{{ asset('storage/'.$doc->file_path) }}"
                                    style="width:100%; height:80vh; border:none;"></iframe>

                        <!-- IMAGE PREVIEW -->
                        @else
                            <img src="{{ asset('storage/'.$doc->file_path) }}" 
                                class="img-fluid rounded border border-secondary">
                        @endif

                    </div>

                </div>
            </div>
        </div>


    </table>
    @endif

</div>

@endsection
