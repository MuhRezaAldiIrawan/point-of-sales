<!DOCTYPE html>
<html class="loading" lang="id" data-textdirection="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - {{ config('app.name', 'POS System') }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('app-assets/images/ico/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/components.css') }}">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #f5f5f5;
        }

        .error-card {
            max-width: 480px;
            width: 100%;
            text-align: center;
            padding: 3rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, .08);
        }

        .error-code {
            font-size: 6rem;
            font-weight: 700;
            color: #F44336;
            line-height: 1;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: .5rem;
        }

        .error-desc {
            color: #757575;
            margin-bottom: 2rem;
        }
    </style>
</head>

<body>
    <div class="error-card">
        <div class="error-code">403</div>
        <h1 class="error-title">Akses Ditolak</h1>
        <p class="error-desc">{{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}
        </p>
        <a href="{{ route('dashboard.index') }}" class="btn btn-primary">
            <i class="ft-home mr-1"></i> Kembali ke Dashboard
        </a>
    </div>
</body>

</html>
