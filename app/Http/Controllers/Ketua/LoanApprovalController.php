<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\Loan;
use App\Services\InterestService;
use App\Services\LoanNumberService; 
use Illuminate\Support\Facades\Auth;
use App\Services\ScheduleService;
use App\Services\AuditLogService;


class LoanApprovalController extends Controller
{
    public function index()
    {
        $apps = LoanApplication::with(['member', 'supportingDocuments'])
            ->where('status', 'Authorized')
            ->orderByDesc('created_at')
            ->get();
        
        return view('ketua.approval.index', compact('apps'));
    }

    public function approve($id)
    {
        $app = LoanApplication::findOrFail($id);
        $old = $app->toArray();

        // Hitung detail pinjaman (flat)
        $calc = InterestService::calculateFlat(
            $app->request_amount,
            2, // bunga 2% default
            $app->duration_months
        );

        // Buat record LOAN
        $loan = Loan::create([
            'loan_number'        => LoanNumberService::generate('BP'),
            'application_id'     => $app->application_id,
            'member_id'          => $app->member_id,
            'disbursement_date'  => now(),
            'principal_amount'   => $app->request_amount,
            'interest_rate'      => 2,
            'interest_type'      => 'flat',
            'duration_months'    => $app->duration_months,
            'monthly_installment'=> $calc['monthly'],
            'total_interest'     => $calc['total_interest'],
            'total_amount'       => $calc['total_amount'],
            'remaining_balance'  => $calc['total_amount'],
            'status'             => 'active',
        ]);

        
        ScheduleService::generate(
            $loan,
            $loan->duration_months,
            $loan->monthly_installment,
            $loan->principal_amount / $loan->duration_months,
            $loan->total_interest   / $loan->duration_months
        );

        // Update status pengajuan
        $app->update([
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'status'      => 'Approved',
        ]);

        AuditLogService::log(
            'Approve',
            'loan_applications',
            $app->application_id,
            $old,
            $app->toArray()
        );

        AuditLogService::log(
            'Create',
            'loans',
            $loan->loan_id,
            [],
            $loan->toArray()
        );

        return back()->with('success', 'Pinjaman berhasil disetujui dan jadwal angsuran dibuat otomatis.');
    }

    public function reject($id)
    {
        $app = LoanApplication::findOrFail($id);

        $app->update([
            'status'            => 'Rejected',
            'rejection_reason'  => request()->reason,
        ]);

        return back()->with('success', 'Pengajuan ditolak');
    }
}
