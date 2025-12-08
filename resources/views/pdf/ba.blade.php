<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .line {
            height: 2px;
            background: #7f35ff;
            margin: 12px 0;
        }
        table { width: 100%; margin-top: 10px; }
        td { padding: 4px 0; }
    </style>
</head>

<body>

<h2>Bukti Pembayaran Angsuran</h2>

<div class="line"></div>

<table>
    <tr>
        <td><strong>No Pembayaran:</strong></td>
        <td>{{ $payment->payment_number }}</td>
    </tr>
    <tr>
        <td><strong>No Pinjaman:</strong></td>
        <td>{{ $loan->loan_number }}</td>
    </tr>
    <tr>
        <td><strong>Nama Member:</strong></td>
        <td>{{ $loan->member->full_name }}</td>
    </tr>
    <tr>
        <td><strong>Angsuran Ke:</strong></td>
        <td>{{ $schedule->installment_number }}</td>
    </tr>
    <tr>
        <td><strong>Tanggal Bayar:</strong></td>
        <td>{{ $payment->payment_date }}</td>
    </tr>
</table>

<div class="line"></div>

<h4>Rincian Pembayaran</h4>

<table>
    <tr>
        <td>Pokok Dibayar:</td>
        <td>Rp {{ number_format($payment->principal_paid, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td>Bunga Dibayar:</td>
        <td>Rp {{ number_format($payment->interest_paid, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td><strong>Total Dibayar:</strong></td>
        <td><strong>Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</strong></td>
    </tr>
</table>

<br><br>

<h4>QR Code Verifikasi:</h4>
<img src="data:image/svg+xml;base64,{{ $qr }}" width="140">

<br><br>

<small>Dokumen ini dihasilkan secara otomatis oleh sistem Koperasi UG.</small>

</body>
</html>
