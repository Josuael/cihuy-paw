<?php

namespace App\Http\Controllers;

use App\Models\SupportingDocument;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function create($application_id)
    {
        // pastikan pengajuan milik member yang login
        $member = Auth::user()->member;

        $app = LoanApplication::with('member', 'documents')
            ->where('application_id', $application_id)
            ->where('member_id', $member->member_id)
            ->firstOrFail();

        return view('loans.documents', compact('app'));
    }

    public function store(Request $request, $application_id)
    {
        // validasi 3 file opsional
        $request->validate([
            'ktp'       => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:5120',
            'kk'        => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:5120',
            'slip_gaji' => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:5120',
        ]);

        // pastiin ini pengajuannya memang punya member yang lagi login
        $member = Auth::user()->member;

        $application = LoanApplication::where('application_id', $application_id)
            ->where('member_id', $member->member_id)
            ->firstOrFail();

        // mapping nama field => tipe dokumen di DB
        $map = [
            'ktp'       => 'KTP',
            'kk'        => 'KK',
            'slip_gaji' => 'Slip Gaji',
        ];

        foreach ($map as $field => $docType) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

                // nama file unik
                $name = time() . '_' . $field . '_' . $file->getClientOriginalName();

                // simpan di storage/app/public/documents/FPP-....../
                $path = $file->storeAs(
                    'documents/' . $application->application_number,
                    $name,
                    'public'
                );

                SupportingDocument::updateOrCreate(
                    [
                        'application_id' => $application->application_id,
                        'doc_type'       => $docType,
                    ],
                    [
                        'file_name' => $name,
                        'file_path' => $path,
                        'file_size' => $file->getSize(),
                    ]
                );
            }
        }

        return back()->with('success', 'Dokumen berhasil diupload.');
    }
}
