<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .line { height: 2px; background: #7f35ff; margin: 10px 0; }
    </style>
</head>

<body>

<h2>Bukti Peminjaman</h2>

<div class="line"></div>

<p><strong>No Pinjaman:</strong> {{ $loan->loan_number }}</p>
<p><strong>Nama Member:</strong> {{ $loan->member->full_name }}</p>
<p><strong>Jumlah Pinjaman:</strong> Rp {{ number_format($loan->principal_amount,0,',','.') }}</p>

<br><br>

<h4>QR Code Verifikasi:</h4>
<img src="data:image/svg+xml;base64,{{ $qr }}" width="140">





</body>
</html>
