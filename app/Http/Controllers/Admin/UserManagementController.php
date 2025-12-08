<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditLogService;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        // kalau ada yang nyasar ke /admin/users/{id} atau /admin/users/index
        // langsung lempar balik ke list user
        return redirect()->route('admin.users.index');
    }


    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'   => 'required|string|max:100|unique:users,username',
            'email'      => 'required|email|max:100|unique:users,email',
            'full_name'  => 'required|string|max:100',
            'password'   => 'required|string|min:6|confirmed',
            'role'       => 'required|in:member,staff,admin,ketua',

            // data khusus member
            'phone_number'    => 'nullable|string|max:100',
            'address'         => 'nullable|string|max:100',
            'member_category' => 'nullable|string|max:100',
            'max_loan_limit'  => 'nullable|numeric|min:0',
        ]);

        // 1. Buat user
        $user = User::create([
            'username'  => $validated['username'],
            'email'     => $validated['email'],
            'full_name' => $validated['full_name'],
            'password'  => Hash::make($validated['password']),
            'role'      => $validated['role'],
        ]);

        // 2. Kalau role = member → otomatis buat baris di tabel members
        if ($validated['role'] === 'member') {
            Member::create([
                'user_id'         => $user->user_id,
                'full_name'       => $validated['full_name'],
                'email'           => $validated['email'],
                'phone_number'    => $validated['phone_number'] ?? '',
                'address'         => $validated['address'] ?? '',
                'member_category' => $validated['member_category'] ?? 'regular',
                'max_loan_limit'  => $validated['max_loan_limit'] ?? 0,
                'status'          => 'active',
            ]);
        }

        AuditLogService::log(
            'Create',
            'users',
            $user->user_id,
            [],
            $user->toArray()
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $member = $user->member; // bisa null kalau bukan member

        return view('admin.users.edit', compact('user', 'member'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username'   => 'required|string|max:100|unique:users,username,'. $user->user_id .',user_id',
            'email'      => 'required|email|max:100|unique:users,email,'. $user->user_id .',user_id',
            'full_name'  => 'required|string|max:100',
            'password'   => 'nullable|string|min:6|confirmed',
            'role'       => 'required|in:member,staff,admin,ketua',

            'phone_number'    => 'nullable|string|max:100',
            'address'         => 'nullable|string|max:100',
            'member_category' => 'nullable|string|max:100',
            'max_loan_limit'  => 'nullable|numeric|min:0',
        ]);

        // 1. Update user
        $user->username  = $validated['username'];
        $user->email     = $validated['email'];
        $user->full_name = $validated['full_name'];
        $user->role      = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // 2. Handle data member
        if ($validated['role'] === 'member') {
            // kalau belum punya record member → buat
            $member = $user->member ?: new Member(['user_id' => $user->user_id]);

            $member->full_name       = $validated['full_name'];
            $member->email           = $validated['email'];
            $member->phone_number    = $validated['phone_number'] ?? '';
            $member->address         = $validated['address'] ?? '';
            $member->member_category = $validated['member_category'] ?? 'regular';
            $member->max_loan_limit  = $validated['max_loan_limit'] ?? 0;
            $member->status          = $member->status ?? 'active';

            $member->user_id = $user->user_id;

            $member->save();
        } else {
            // kalau role diubah dari member → non member, boleh hapus record member (opsional)
            if ($user->member) {
                $user->member->delete();
            }
        }

        AuditLogService::log(
            'Update',
            'users',
            $user->user_id,
            [],
            $user->toArray()
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        // kalau member, hapus juga baris di members
        if ($user->member) {
            $user->member->delete();
        }

        $user->delete();

        AuditLogService::log(
            'Delete',
            'users',
            $user->user_id,
            [],
            $user->toArray()
        );


        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
