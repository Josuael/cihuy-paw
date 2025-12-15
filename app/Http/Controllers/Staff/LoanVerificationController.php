<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditLogService;


class LoanVerificationController extends Controller
{
    public function index()
    {
        $apps = LoanApplication::with(['member', 'supportingDocuments'])
            ->where('status', 'Submitted')
            ->orderByDesc('created_at')
            ->get();
            
        return view('staff.verification.index', compact('apps'));
    }

    public function verify(Request $req, $id)
    {
        $app = LoanApplication::findOrFail($id);
        $old = $app->toArray();

        $app->update([
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'status' => 'Verified'
        ]);
        AuditLogService::log(
            'Approve',
            'loan_applications',
            $app->application_id,
            $old,
            $app->toArray()
        );

        return back()->with('success', 'Pengajuan diverifikasi');
    }
}
