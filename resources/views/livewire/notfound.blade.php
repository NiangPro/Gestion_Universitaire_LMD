<!DOCTYPE html>
<html lang="fr" class="h-100">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Page Non Trouvée</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
            color: #6c757d;
            animation: float 3s ease-in-out infinite;
        }
        .error-code {
            font-size: 5rem;
            font-weight: 700;
            color: #2d3436;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
        .btn-return {
            padding: 0.75rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            background: linear-gradient(45deg, #479CD5, #FF8A00);
            border: none;
            color: white;
        }
        .btn-return:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(71,156,213,0.3);
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .error-illustration {
            max-width: 300px;
            margin: 0 auto 2rem;
            opacity: 0.8;
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
                            <i class="fas fa-search error-icon"></i>
                        </div>
                        <h1 class="error-code">404</h1>
                        <h2 class="h4 font-weight-bold text-secondary mb-3">Page Non Trouvée</h2>
                        <p class="text-muted mb-4">Désolé, la page que vous recherchez n'existe pas ou a été déplacée.</p>
                        <a href="{{route('home')}}" class="btn btn-return">
                            <i class="fas fa-home mr-2"></i>Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>