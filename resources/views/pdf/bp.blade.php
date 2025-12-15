<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bukti Peminjaman - {{ $loan->loan_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .header { text-align:center; margin-bottom:20px; }
        .title  { font-size:18px; font-weight:bold; }
        .box    { border:1px solid #000; padding:10px; margin-bottom:10px; }
        table   { width:100%; border-collapse:collapse; }
        th,td   { border:1px solid #000; padding:5px; }
        th      { background:#f0f0f0; }
    </style>
</head>
<body>

<div class="header">
    <div class="title">BUKTI PEMINJAMAN (BP)</div>
    <div>Koperasi Simpan Pinjam UG</div>
</div>

<div class="box">
    <strong>Data Peminjam</strong><br>
    Nama&nbsp;&nbsp;&nbsp;&nbsp;: {{ $loan->member->full_name }}<br>
    Email&nbsp;&nbsp;&nbsp;&nbsp;: {{ $loan->member->email }}<br>
</div>

<div class="box">
    <strong>Data Pinjaman</strong><br>
    Nomor BP&nbsp;&nbsp;&nbsp;: {{ $loan->loan_number }}<br>
    Nomor FPP&nbsp;: {{ optional($loan->application)->application_number ?? '-' }}<br>
    Tanggal Cair : {{ \Carbon\Carbon::parse($summary['disbursement_date'])->format('d M Y') }}<br>
    Plafon&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Rp {{ number_format($summary['principal'],0,',','.') }}<br>
    Total Bunga : Rp {{ number_format($summary['interest_total'],0,',','.') }}<br>
    Total Hutang: Rp {{ number_format($summary['total_amount'],0,',','.') }}<br>
    Tenor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $summary['tenor'] }} bulan<br>
    Angsuran/bln: Rp {{ number_format($summary['monthly_installment'],0,',','.') }}<br>
    Periode Angsuran :
    @if($summary['start_installment'] && $summary['end_installment'])
        {{ \Carbon\Carbon::parse($summary['start_installment'])->format('d M Y') }}
        s/d
        {{ \Carbon\Carbon::parse($summary['end_installment'])->format('d M Y') }}
    @else
        -
    @endif
    <br>
    Total Terbayar: Rp {{ number_format($summary['total_paid'],0,',','.') }}<br>
    Sisa Hutang&nbsp;&nbsp;: Rp {{ number_format($summary['remaining_balance'],0,',','.') }}<br>
</div>

@if($schedules->count())
    <div class="box">
        <strong>Ringkasan Jadwal Angsuran</strong>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Jatuh Tempo</th>
                    <th>Pokok</th>
                    <th>Bunga</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach($schedules as $s)
                <tr>
                    <td align="center">{{ $s->installment_number }}</td>
                    <td>{{ \Carbon\Carbon::parse($s->due_date)->format('d M Y') }}</td>
                    <td align="right">Rp {{ number_format($s->principal_amount,0,',','.') }}</td>
                    <td align="right">Rp {{ number_format($s->interest_amount,0,',','.') }}</td>
                    <td align="right">Rp {{ number_format($s->total_amount,0,',','.') }}</td>
                    <td>{{ ucfirst($s->status) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif

<div style="margin-top:20px;">
    <table width="100%">
        <tr>
            <td width="60%">
                <small>Dokumen ini dibuat otomatis oleh sistem Koperasi UG.</small>
            </td>
            <td align="right">
                @if(!empty($qr))
                    <img src="data:image/svg+xml;base64,{{ $qr }}" width="120">
                    <br>
                    <small>QR untuk verifikasi BP</small>
                @endif
            </td>
        </tr>
    </table>
</div>

</body>
</html>
