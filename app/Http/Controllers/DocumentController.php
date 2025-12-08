<?php

namespace App\Http\Controllers;

use App\Models\SupportingDocument;
use Illuminate\Http\Request;
use App\Models\LoanApplication;
use App\Services\DocumentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'ktp'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kk'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'slip_gaji'=> 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $member = Auth::user()->member;

        $application = LoanApplication::where('application_id', $id)
            ->where('member_id', $member->member_id)
            ->firstOrFail();

        DocumentService::upload($request, $application);

        return back()->with('success', 'Dokumen berhasil diupload.');
    }
}