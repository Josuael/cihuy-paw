<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Auth;



class LoanAuthorizationController extends Controller
{
    public function index()
    {
        $apps = LoanApplication::where('status', 'Verified')->get();
        return view('admin.authorization.index', compact('apps'));
    }

    public function authorizeLoan($id)
    {
        $app = LoanApplication::findOrFail($id);
        $old = $app->toArray();


        $app->update([
            'status'         => 'Authorized',
            'authorized_by'  => Auth::id(),
            'authorized_at'  => now(),
        ]);

        AuditLogService::log(
            'Approve',
            'loan_applications',
            $app->application_id,
            $old,
            $app->toArray()
        );

        return back()->with('success', 'Otorisasi berhasil');
    }
}
