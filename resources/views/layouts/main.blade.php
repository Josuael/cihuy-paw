<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Koperasi UG - Dark Premium</title>

    <!-- BOOTSTRAP 5 CDN -->
    <link rel="stylesheet" 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CUSTOM DARK THEME -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-2 sidebar">
            <h4 class="text-white ps-3 mb-4">
                <i class="fa-solid fa-vault me-2"></i>
                Koperasi UG
            </h4>

            <!-- AUTO-DETECT ROLE -->
            @php $role = auth()->user()->role; @endphp

            @if ($role === 'member')
                <a href="/member/dashboard">Dashboard</a>
                <a href="/loan-applications">Ajukan Pinjaman</a>
                <a href="/wallet">Saldo Saya</a>
            @endif

            @if ($role === 'staff')
                <a href="/staff/dashboard">Dashboard</a>
                <a href="/staff/loan-applications">Verifikasi Pinjaman</a>
                <a href="/staff/topup">Approve Top-Up</a>
            @endif

            @if ($role === 'admin')
                <a href="/admin/dashboard">Dashboard</a>
                <a href="{{ route('admin.users.index') }}"
                    class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users-gear me-2"></i> Manajemen User
                    </a>
                <a href="/admin/authorization">Otorisasi Pinjaman</a>
            @endif

            @if ($role === 'ketua')
                <a href="/ketua/dashboard">Dashboard</a>
                <a href="/ketua/approval">Approval Akhir</a>
            @endif


            <hr>

            <a href="/logout">
                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
            </a>
        </div>

        <!-- CONTENT -->
        <div class="col-10 content page-fade">
            @yield('content')
        </div>


    </div>
</div>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999;">
    <div id="toastBox"></div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- CHARTS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- GLOBAL ANALYTICS JS -->
<script src="{{ asset('js/analytics.js') }}"
        data-analytics="true"
        data-months='@json($months ?? [])'
        data-loans='@json($loansData ?? [])'
        data-payments='@json($paymentsData ?? [])'>
</script>

<!-- GLOBAL TOAST FUNCTION -->
<script>
function showToast(message, type = 'success') {
    let color = (type === 'success') ? '#7f35ff' : '#ff3558';

    const toast = document.createElement('div');
    toast.classList.add('neon-toast', 'p-3', 'mb-2', 'rounded');
    toast.style.borderLeftColor = color;
    toast.innerHTML = `<strong>${type.toUpperCase()}</strong><br>${message}`;

    document.getElementById('toastBox').appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 500);
    }, 2500);
}
</script>

<!-- AUTO FIRE TOASTS (THE PART YOU ASKED) -->
@if (session('success'))
<script> showToast("{{ session('success') }}", 'success'); </script>
@endif

@if (session('error'))
<script> showToast("{{ session('error') }}", 'error'); </script>
@endif

@yield('scripts')


</body>
</html>
