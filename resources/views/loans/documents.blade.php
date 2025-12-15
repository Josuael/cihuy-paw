@extends('layouts.main')

@section('content')

<div class="card-dark p-4 mb-4">
    <h2 class="neon-text">
        <i class="fa-solid fa-file-circle-check me-2"></i>
        Upload Dokumen Pendukung
    </h2>
    <p class="text-muted mb-0">
        Pengajuan: {{ $app->application_number }} - {{ $app->member->full_name }}
    </p>
</div>

<div class="card-dark p-4 mb-4">
    <form action="{{ route('loan-applications.upload', $app->application_id) }}"
          method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label text-white">Jenis Dokumen</label>
            <select name="doc_type" class="form-control bg-dark text-white border-secondary" required>
                <option value="">-- pilih jenis --</option>
                <option value="KTP">KTP</option>
                <option value="KK">Kartu Keluarga</option>
                <option value="SLIP_GAJI">Slip Gaji</option>
                <option value="LAINNYA">Lainnya</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label text-white">File</label>
            <input type="file" name="file"
                   class="form-control bg-dark text-white border-secondary"
                   required>
            <small class="text-muted">Maks 5 MB.</small>
        </div>

        <button class="btn btn-purple w-100">
            <i class="fa-solid fa-upload me-2"></i> Upload Dokumen
        </button>
    </form>
</div>

<div class="card-dark p-4">
    <h5 class="text-white mb-3">Dokumen yang sudah diupload</h5>

    @if($app->documents->isEmpty())
        <p class="text-muted">Belum ada dokumen.</p>
    @else
        <table class="table table-dark table-striped align-middle">
            <thead>
                <tr>
                    <th>Jenis</th>
                    <th>Nama File</th>
                    <th>Ukuran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($app->documents as $doc)
                    <tr>
                        <td>{{ $doc->doc_type }}</td>
                        <td>{{ $doc->file_name }}</td>
                        <td>{{ number_format($doc->file_size / 1024, 2) }} KB</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
