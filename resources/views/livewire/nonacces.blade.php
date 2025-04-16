

<!DOCTYPE html>
<html lang="fr" class="h-100">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Accès Interdit</title>
    <style>
        .error-page {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .error-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 2rem;
            transition: transform 0.3s ease;
        }
        .error-card:hover {
            transform: translateY(-5px);
        }
        .error-icon {
            font-size: 4rem;
            color: #dc3545;
            animation: pulse 2s infinite;
        }
        .error-code {
            font-size: 5rem;
            font-weight: 700;
            color: #2d3436;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .btn-return {
            padding: 0.75rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-return:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,123,255,0.3);
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body class="h-100">
    <div class="error-page d-flex align-items-center justify-content-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="error-card text-center">
                        <div class="mb-4">
                            <i class="fas fa-exclamation-circle error-icon"></i>
                        </div>
                        <h1 class="error-code mb-4">403</h1>
                        <h2 class="h4 font-weight-bold text-danger mb-3">Accès Interdit!</h2>
                        <p class="text-muted mb-4">Vous n'avez pas l'autorisation de visualiser cette page.</p>
                        <a href="{{route('home')}}" class="btn btn-primary btn-return">
                            <i class="fas fa-home mr-2"></i>Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>