@extends('layouts.main')

@section('content')

<div class="card-dark p-4 mb-4">
    <h2 class="neon-text mb-0">
        <i class="fa-solid fa-user-plus me-2"></i> Tambah User Baru
    </h2>
    <p class="text-muted mb-0">Admin dapat membuat akun baru dan menentukan rolenya.</p>
</div>

<div class="card-dark p-4">

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label text-white">Username</label>
                <input type="text" name="username" class="form-control bg-dark text-white border-secondary"
                       value="{{ old('username') }}" required>
                @error('username') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label text-white">Nama Lengkap</label>
                <input type="text" name="full_name" class="form-control bg-dark text-white border-secondary"
                       value="{{ old('full_name') }}" required>
                @error('full_name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label text-white">Email</label>
                <input type="email" name="email" class="form-control bg-dark text-white border-secondary"
                       value="{{ old('email') }}" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label text-white">Role</label>
                <select name="role" class="form-control bg-dark text-white border-secondary" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Member</option>
                    <option value="staff"  {{ old('role') == 'staff' ? 'selected' : '' }}>Staff Kredit</option>
                    <option value="admin"  {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="ketua"  {{ old('role') == 'ketua' ? 'selected' : '' }}>Ketua</option>
                </select>
                @error('role') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label text-white">Password</label>
                <input type="password" name="password" class="form-control bg-dark text-white border-secondary" required>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label text-white">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control bg-dark text-white border-secondary" required>
            </div>
        </div>

        <hr class="border-secondary">

        <h5 class="text-white mb-3">Data Member (opsional, dipakai bila role = member)</h5>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label text-white">No. HP</label>
                <input type="text" name="phone_number"
                       class="form-control bg-dark text-white border-secondary"
                       value="{{ old('phone_number') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label text-white">Kategori Member</label>
                <input type="text" name="member_category"
                       class="form-control bg-dark text-white border-secondary"
                       value="{{ old('member_category', 'regular') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label text-white">Limit Maksimal Pinjaman</label>
                <input type="number" name="max_loan_limit"
                       class="form-control bg-dark text-white border-secondary"
                       value="{{ old('max_loan_limit', 0) }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label text-white">Alamat</label>
            <textarea name="address" rows="2"
                      class="form-control bg-dark text-white border-secondary">{{ old('address') }}</textarea>
        </div>

        <button type="submit" class="btn btn-purple w-100">
            <i class="fa-solid fa-save me-2"></i> Simpan User
        </button>

    </form>
</div>

@endsection
