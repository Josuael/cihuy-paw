<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bukti Angsuran - {{ $payment->payment_number ?? $payment->payment_id }}</title>
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
    <div class="title">BUKTI PEMBAYARAN ANGSURAN (BA)</div>
    <div>Koperasi Simpan Pinjam UG</div>
</div>

<div class="box">
    <strong>Data Peminjam</strong><br>
    Nama&nbsp;&nbsp;&nbsp;&nbsp;: {{ $member->full_name }}<br>
    Email&nbsp;&nbsp;&nbsp;&nbsp;: {{ $member->email }}<br>
</div>

<div class="box">
    <strong>Data Pinjaman</strong><br>
    Nomor BP&nbsp;&nbsp;: {{ $loan->loan_number }}<br>
    Plafon&nbsp;&nbsp;&nbsp;&nbsp;: Rp {{ number_format($loan->principal_amount,0,',','.') }}<br>
    Tenor&nbsp;&nbsp;&nbsp;&nbsp;: {{ $loan->duration_months }} bulan<br>
    Angsuran/bln : Rp {{ number_format($loan->monthly_installment,0,',','.') }}<br>
</div>

<div class="box">
    <strong>Data Pembayaran</strong><br>
    Nomor BA&nbsp;&nbsp;&nbsp;&nbsp;: {{ $payment->payment_number ?? ('PAY-'.$payment->payment_id) }}<br>
    Tanggal Bayar : {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y H:i') }}<br>
    Metode&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ ucfirst($payment->payment_method ?? 'wallet') }}<br>

    @if($schedule)
        Angsuran ke&nbsp;&nbsp;: {{ $schedule->installment_number }}<br>
        Jatuh Tempo&nbsp;: {{ \Carbon\Carbon::parse($schedule->due_date)->format('d M Y') }}<br>
    @endif

    Jumlah Dibayar : Rp {{ number_format($payment->amount_paid,0,',','.') }}<br>
    Sisa Hutang&nbsp;&nbsp;: Rp {{ number_format($remainingAfter,0,',','.') }}<br>
</div>

<div style="margin-top:20px;">
    <table width="100%">
        <tr>
            <td width="60%">
                <small>Dokumen ini merupakan bukti sah pembayaran angsuran.</small>
            </td>
            <td align="right">
                @if(!empty($qr))
                    <img src="data:image/svg+xml;base64,{{ $qr }}" width="120">
                    <br>
                    <small>QR untuk verifikasi BA</small>
                @endif
            </td>
        </tr>
    </table>
</div>

</body>
</html>
