<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    /**
     * Catat log sederhana
     *
     * @param string      $actionType  Create|Update|Delete|Login|Logout|Approve|Reject
     * @param string      $tableName   nama tabel (loan_applications, loans, payments, dst)
     * @param int         $recordId    id record yang kena aksi
     * @param array       $oldValues   state sebelum aksi (boleh [])
     * @param array       $newValues   state sesudah aksi (boleh [])
     * @param int|null    $userId      kalau null, pakai Auth::id()
     */
    public static function log(
        string $actionType,
        string $tableName,
        int $recordId,
        array $oldValues = [],
        array $newValues = [],
        ?int $userId = null,
    ): void {
        $uid = $userId ?? Auth::id();

        // Safety: kalau nggak ada user (misal belum login) ya skip saja
        if (!$uid) {
            return;
        }

        AuditLog::create([
            'user_id'     => $uid,
            'action_type' => $actionType,
            'table_name'  => $tableName,
            'record_id'   => $recordId,
            'old_values'  => $oldValues,
            'new_values'  => $newValues,
            'ip_address'  => request()->ip() ?? 'unknown',
            'timestamp'   => now(),
        ]);
    }
}
