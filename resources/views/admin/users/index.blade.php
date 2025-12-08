@extends('layouts.main')

@section('content')

<div class="card-dark p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="neon-text mb-0">
                <i class="fa-solid fa-users-gear me-2"></i> Manajemen User
            </h2>
            <p class="text-muted mb-0">Kelola akun dan role pengguna sistem koperasi.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-purple">
            <i class="fa-solid fa-plus me-1"></i> Tambah User
        </a>
    </div>
</div>

<div class="card-dark p-4">
    <table class="table table-dark table-hover align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Role</th>
                <th>Member?</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge bg-info text-dark text-uppercase">{{ $user->role }}</span>
                    </td>
                    <td>
                        @if($user->member)
                            <span class="badge bg-success">Terdaftar</span>
                        @elseif($user->role === 'member')
                            <span class="badge bg-warning text-dark">Belum dibuat</span>
                        @else
                            <span class="badge bg-secondary">-</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.users.edit', $user->user_id) }}"
                           class="btn btn-sm btn-outline-light me-1">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        @if(auth()->user()->user_id !== $user->user_id)
                            <form action="{{ route('admin.users.destroy', $user->user_id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus user ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        Belum ada user.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
