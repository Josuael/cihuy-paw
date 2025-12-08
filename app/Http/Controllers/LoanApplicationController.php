<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use Illuminate\Http\Request;
use App\Services\LoanNumberService;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditLogService;


class LoanApplicationController extends Controller
{
    public function index()
    {
        // list pengajuan milik member yang login
        $member = Auth::user()->member;

        $apps = $member
            ? $member->applications()->orderByDesc('created_at')->get()
            : collect();

        return view('loans.index', compact('apps'));
    }

    public function create()
    {
        return view('loans.apply');
    }

    public function store(Request $req)
    {
        $req->validate([
            'amount'  => 'required|numeric|min:10000',
            'purpose' => 'required',
            'months'  => 'required|numeric|min:1'
        ]);

        $member = Auth::user()->member;

        $app = LoanApplication::create([
            'application_number' => LoanNumberService::generate('FPP'),
            'member_id'          => $member->member_id,
            'request_amount'     => $req->amount,
            'loan_purpose'       => $req->purpose,
            'duration_months'    => $req->months,
            'status'             => 'Submitted',
        ]);

        AuditLogService::log(
            'Create',
            'loan_applications',
            $app->application_id,
            [],
            $app->toArray()
        );

        return redirect()
            ->route('loan-applications.documents', $app->application_id)
            ->with('success', 'Pengajuan berhasil dikirim. Silakan upload dokumen pendukung.');
    }

    public function show($id)
    {
        // DETAIL PENGAJUAN, BUKAN PINJAMAN
        $app = LoanApplication::with('member', 'documents')->findOrFail($id);
        return view('loans.show-application', compact('app'));
    }

    public function documents($id)
    {
        $member = Auth::user()->member;

        $app = LoanApplication::with('documents', 'member')
            ->where('application_id', $id)
            ->where('member_id', $member->member_id)
            ->firstOrFail();

        return view('loans.documents', compact('app'));
    }
}
