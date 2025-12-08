<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Koperasi UG</title>

    <link rel="stylesheet" 
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="bg-dark d-flex justify-content-center align-items-center" style="height:100vh;">

<div class="card-dark p-5" style="width:370px;">
    
    <h3 class="text-center neon-text mb-4">
        <i class="fa-solid fa-vault me-2"></i>
        Login Koperasi
    </h3>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div class="mb-3">
            <label class="text-light">Username</label>
            <input type="text" name="username" 
                   class="form-control bg-dark text-white border-secondary" required>
        </div>

        <div class="mb-3">
            <label class="text-light">Password</label>
            <input type="password" name="password" 
                   class="form-control bg-dark text-white border-secondary" required>
        </div>

        <button class="btn btn-purple w-100">Login</button>

    </form>

</div>

</body>
</html>
