<?php

namespace App\Http\Controllers\Staff;

use App\Models\Loan;
use App\Models\InstallmentSchedule;
use App\Models\Payment;
use App\Services\PaymentNumberService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\WalletTransaction;
use App\Services\AuditLogService;


class PaymentController extends Controller
{
    // ================================
    // MEMBER VIEW INSTALLMENTS
    // ================================
    public function memberInstallments()
    {
        $loans = Loan::where('member_id', Auth::user()->member->member_id)->get();

        return view('member.installments.index', compact('loans'));
    }

    // ================================
    // MEMBER PAY
    // ================================
    public function memberPay($schedule_id)
    {
        // Ambil jadwal + loan
        $schedule = InstallmentSchedule::with('loan')->findOrFail($schedule_id);

        // Pastikan jadwal ini milik member yang login
        $member = Auth::user()->member;
        if (!$member || $schedule->loan->member_id !== $member->member_id) {
            abort(403, 'Tidak boleh membayar cicilan orang lain.');
        }

        // Ambil wallet milik member (relasi: Member::wallet())
        $wallet = $member->wallet;       // <-- penting: pakai member, BUKAN auth()->id()

        if (!$wallet || $wallet->balance < $schedule->total_amount) {
            return back()->with('error', 'Saldo tidak cukup untuk membayar cicilan.');
        }

        DB::transaction(function () use ($wallet, $schedule, $member) {

            // 1. Kurangi saldo wallet
            $wallet->decrement('balance', $schedule->total_amount);

            // 2. Catat pembayaran
            $payment = Payment::create([
                'payment_number'   => 'BA-' . time(),
                'loan_id'          => $schedule->loan_id,
                'schedule_id'      => $schedule->schedule_id,
                'payment_date'     => now(),
                'amount_paid'      => $schedule->total_amount,
                'principal_paid'   => $schedule->principal_amount,
                'interest_paid'    => $schedule->interest_amount,
                'payment_method'   => 'Transfer',
                'receipt_generated'=> 0,
                'recorded_by'      => Auth::id(),   // user yang login
            ]);

            // 3. Update status jadwal
            $schedule->update([
                'status'    => 'paid',
                'paid_date' => now(),
            ]);

            // 4. Kurangi sisa pinjaman
            $loan = $schedule->loan;
            $loan->remaining_balance -= $schedule->total_amount;
            if ($loan->remaining_balance <= 0) {
                $loan->remaining_balance = 0;
                $loan->status = 'paid off';
            }
            $loan->save();

            // 5. (opsional) catat transaksi wallet
            WalletTransaction::create([
                'wallet_id'    => $wallet->wallet_id,
                'amount'       => $schedule->total_amount,
                'type'         => 'payment',
                'status'       => 'approved',
                'created_by'   => Auth::id(),   // user yang login
                'approved_by'  => Auth::id(),   // user yang login juga
            ]);

            AuditLogService::log(
                'Create',
                'payments',
                $payment->payment_id,
                [],
                $payment->toArray()
            );
        });

        return back()->with('success', 'Cicilan berhasil dibayar.');
    }

    // ================================
    // STAFF VIEW SCHEDULE FOR A LOAN
    // ================================
    public function staffInstallments($loan_id)
    {
        $loan = Loan::with('schedules', 'member')->findOrFail($loan_id);
        return view('staff.installments.index', compact('loan'));
    }

    // ================================
    // STAFF PAY (CASH)
    // ================================
    public function staffPay($schedule_id)
    {
        $schedule = InstallmentSchedule::findOrFail($schedule_id);
        $loan = $schedule->loan;

        Payment::create([
            'payment_number' => PaymentNumberService::generate(),
            'loan_id' => $loan->loan_id,
            'schedule_id' => $schedule->schedule_id,
            'amount_paid' => $schedule->total_amount,
            'principal_paid' => $schedule->principal_amount,
            'interest_paid' => $schedule->interest_amount,
            'payment_method' => 'Cash',
            'recorded_by' => Auth::id(),
        ]);

        $schedule->status = 'paid';
        $schedule->paid_date = now();
        $schedule->save();

        $loan->remaining_balance -= $schedule->total_amount;
        $loan->save();

        AuditLogService::log(
                'Create',
                'payments',
                $loan->payment_id,
                [],
                $loan->toArray()
            );

        return back()->with('success', 'Pembayaran angsuran berhasil dicatat.');
    }
}
