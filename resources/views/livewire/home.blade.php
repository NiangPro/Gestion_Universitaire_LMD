<div>
    <!-- Hero Section améliorée -->
    <section class="hero-section position-relative overflow-hidden bg-gradient-primary text-white">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row min-vh-100 align-items-center">
                <div class="col-lg-6">
                    <div class="animate-left">
                        <span class="badge mb-3 px-3 py-2 animate-pulse" style="background-color: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);">
                            <i class="fas fa-rocket me-2"></i>Nouvelle Version Disponible
                        </span>
                        <h1 class="display-4 fw-bold mb-4 text-shadow">
                            La Meilleure Solution de<br>
                            <span class="gradient-text">Gestion Universitaire</span>
                    </h1>
                        <p class="lead mb-4 opacity-90 fw-light">
                            Simplifiez la gestion de votre établissement avec notre plateforme tout-en-un. 
                            De l'administration aux résultats, en passant par la communication.
                        </p>
                        <div class="d-flex gap-3 flex-wrap mb-5">
                            <a href="{{ route('register', ['id' => 1]) }}" class="btn btn-light btn-lg px-4 rounded-pill hover-scale">
                                <i class="fas fa-play me-2"></i>Démarrer Gratuitement
                            </a>
                            <a href="#demo" class="btn btn-outline-light btn-lg px-4 rounded-pill hover-scale">
                                <i class="fas fa-video me-2"></i>Voir la démo
                        </a>
                    </div>
                        <div class="stats-container">
                            <div class="stat-item">
                                <i class="fas fa-school text-warning"></i>
                                <span>100+ Établissements</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-users text-info"></i>
                                <span>5000+ Utilisateurs</span>
                        </div>
                            <div class="stat-item">
                                <i class="fas fa-star text-warning"></i>
                                <span>4.9/5 Satisfaction</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="position-relative animate-right">
                        <div class="dashboard-preview">
                            <video src="{{ asset('images/home.mp4') }}" autoplay muted loop 
                                   class="img-fluid rounded-lg main-video"></video>
                        </div>
                        <div class="floating-card card-1">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-graduation-cap text-primary fa-2x me-3"></i>
                                    <div>
                                    <h6 class="mb-0">Gestion Simplifiée</h6>
                                    <small>1000+ Étudiants</small>
                                </div>
                            </div>
                        </div>
                        <div class="floating-card card-2">
                                <div class="d-flex align-items-center">
                                <i class="fas fa-chart-line text-success fa-2x me-3"></i>
                                    <div>
                                    <h6 class="mb-0">Performance</h6>
                                    <small>98% de réussite</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Formes d'arrière-plan améliorées -->
        <div class="hero-shapes">
            <div class="shape-1 animate-float"></div>
            <div class="shape-2 animate-float-delay"></div>
            <div class="shape-3 animate-float-delay-2"></div>
            <div class="shape-4 animate-pulse"></div>
        </div>
    </section>

    <!-- Styles CSS améliorés -->
    <style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #FF8A00, #0288d1, #0097a7);
        position: relative;
    }

    .gradient-text {
        background: linear-gradient(45deg, #fff, #64b5f6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .text-shadow {
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .animate-left {
        animation: slideInLeft 1s ease-out;
    }

    .animate-right {
        animation: slideInRight 1s ease-out;
    }

    .animate-pulse {
        animation: pulse 2s infinite;
    }

    .dashboard-preview {
        position: relative;
        z-index: 2;
    }

    .main-video {
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
    }

    .main-video:hover {
        transform: translateY(-10px);
    }

    .floating-card {
        position: absolute;
        background: white;
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        z-index: 3;
    }

    .card-1 {
        top: 10%;
        right: -30px;
        animation: float 6s ease-in-out infinite;
    }

    .card-2 {
        bottom: 10%;
        left: -30px;
        animation: float 6s ease-in-out infinite 1s;
    }

    .stats-container {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255,255,255,0.1);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        backdrop-filter: blur(10px);
    }

    .hover-scale {
        transition: transform 0.3s ease;
    }

    .hover-scale:hover {
        transform: scale(1.05);
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-50px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(50px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    .shape-4 {
        position: absolute;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        top: 30%;
        left: 20%;
    }
    </style>

    <!-- Statistiques Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="card border-0 bg-white shadow-sm h-100 hover-translate">
                        <div class="card-body text-center">
                            <div class="icon-box mb-3">
                                <i class="fas fa-school fa-2x text-primary"></i>
                            </div>
                            <h2 class="counter mb-2">100+</h2>
                            <p class="text-muted mb-0">Établissements</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 bg-white shadow-sm h-100 hover-translate">
                        <div class="card-body text-center">
                            <div class="icon-box mb-3">
                                <i class="fas fa-users fa-2x text-success"></i>
                            </div>
                            <h2 class="counter mb-2">5000+</h2>
                            <p class="text-muted mb-0">Étudiants</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 bg-white shadow-sm h-100 hover-translate">
                        <div class="card-body text-center">
                            <div class="icon-box mb-3">
                                <i class="fas fa-chalkboard-teacher fa-2x text-info"></i>
                            </div>
                            <h2 class="counter mb-2">500+</h2>
                            <p class="text-muted mb-0">Professeurs</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 bg-white shadow-sm h-100 hover-translate">
                        <div class="card-body text-center">
                            <div class="icon-box mb-3">
                                <i class="fas fa-book fa-2x text-warning"></i>
                            </div>
                            <h2 class="counter mb-2">1000+</h2>
                            <p class="text-muted mb-0">Cours</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Système LMD -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-primary text-white px-3 py-2 mb-3">Système LMD</span>
                <h2 class="display-5 fw-bold mb-3">Une Solution Adaptée au Système LMD</h2>
                <p class="lead text-muted">Gérez efficacement votre établissement selon les normes License-Master-Doctorat</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm hover-translate">
                        <div class="card-body">
                            <div class="feature-icon bg-primary-soft rounded-circle mb-4">
                                <i class="fas fa-layer-group text-primary"></i>
                            </div>
                            <h4>Licence (BAC+3)</h4>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>6 semestres</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>180 crédits</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Unités d'enseignement</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm hover-translate">
                        <div class="card-body">
                            <div class="feature-icon bg-success-soft rounded-circle mb-4">
                                <i class="fas fa-chart-bar text-success"></i>
                            </div>
                            <h4>Master (BAC+5)</h4>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>4 semestres</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>120 crédits</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Spécialisations</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm hover-translate">
                        <div class="card-body">
                            <div class="feature-icon bg-info-soft rounded-circle mb-4">
                                <i class="fas fa-microscope text-info"></i>
                            </div>
                            <h4>Doctorat (BAC+8)</h4>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>6 semestres</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>180 crédits</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Recherche</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Modules Complets -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-primary text-white px-3 py-2 mb-3">Modules</span>
                <h2 class="display-5 fw-bold mb-3">Une Suite Complète de Gestion</h2>
                <p class="lead text-muted">Tous les outils nécessaires pour votre établissement</p>
            </div>

            <div class="row g-4">
                <!-- Gestion Académique -->
                <div class="col-lg-6 col-md-6">
                    <div class="card h-100 border-0 shadow-sm hover-translate">
                        <div class="card-body">
                            <h4 class="d-flex align-items-center mb-4">
                                <span class="icon-circle bg-primary text-white me-3">
                                    <i class="fas fa-graduation-cap"></i>
                                </span>
                                Gestion Académique
                            </h4>
                            <div class="row">
                                <div class="col-sm-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>UE et matières</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Crédits ECTS</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Semestres</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Filières</li>
                                    </ul>
                                </div>
                                <div class="col-sm-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Départements</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Cycles</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Spécialités</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Parcours</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gestion des Évaluations -->
                <div class="col-lg-6 col-md-6">
                    <div class="card h-100 border-0 shadow-sm hover-translate">
                        <div class="card-body">
                            <h4 class="d-flex align-items-center mb-4">
                                <span class="icon-circle bg-success text-white me-3">
                                    <i class="fas fa-tasks"></i>
                                </span>
                                Évaluations & Suivi
                            </h4>
                            <div class="row">
                                <div class="col-sm-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Notes & moyennes</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Contrôle continu</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Examens</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Rattrapages</li>
                                    </ul>
                                </div>
                                <div class="col-sm-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Bulletins</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Relevés de notes</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>PV de délibération</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Attestations</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gestion Administrative -->
                <div class="col-lg-6 col-md-6">
                    <div class="card h-100 border-0 shadow-sm hover-translate">
                        <div class="card-body">
                            <h4 class="d-flex align-items-center mb-4">
                                <span class="icon-circle bg-info text-white me-3">
                                    <i class="fas fa-user-cog"></i>
                                </span>
                                Administration
                            </h4>
                            <div class="row">
                                <div class="col-sm-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Inscriptions</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Réinscriptions</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Paiements</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Comptabilité</li>
                                    </ul>
                                </div>
                                <div class="col-sm-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gestion RH</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Permissions</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Historiques</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Rapports</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gestion du Personnel -->
                <div class="col-lg-6 col-md-6">
                    <div class="card h-100 border-0 shadow-sm hover-translate">
                        <div class="card-body">
                            <h4 class="d-flex align-items-center mb-4">
                                <span class="icon-circle bg-warning text-white me-3">
                                    <i class="fas fa-users"></i>
                                </span>
                                Personnel & Communication
                            </h4>
                            <div class="row">
                                <div class="col-sm-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Professeurs</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Surveillants</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Parents</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Administration</li>
                                    </ul>
                                </div>
                                <div class="col-sm-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Messagerie</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Notifications</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Emplois du temps</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Planning</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Tarifs -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Nos Offres</h2>
                <p class="lead text-muted">Choisissez le plan qui correspond à vos besoins</p>
            </div>

            <div class="row g-4 justify-content-center">
                @foreach($packs as $pack)
                <div class="col-lg-4 col-md-6">
                    <div class="card pricing-card border-0 shadow-sm hover-translate h-100">
                        <div class="card-header border-0 py-4 bg-transparent">
                            <div class="pricing-icon mb-3" style="background: {{$pack->couleur}}40;">
                                <i class="fas fa-cube" style="color: {{$pack->couleur}};"></i>
                            </div>
                            <h3 class="mb-0" style="color: {{$pack->couleur}};text-align: center;">{{$pack->nom}}</h3>
                        </div>
                        <div class="card-body">
                            <div class="price-value text-center mb-4">
                                <h2 class="display-4 mb-0 fw-bold">
                                    {{ number_format($pack->mensuel, 0, ",", " ") }}
                                </h2>
                                <p class="text-muted">FCFA/mois</p>
                                <span class="badge bg-light text-dark">
                                    ou {{ number_format($pack->annuel, 0, ",", " ") }} FCFA/an
                                </span>
                            </div>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Jusqu'à {{$pack->limite}} élèves
                                </li>
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Gestion complète
                                </li>
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Support technique
                                </li>
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Mises à jour gratuites
                                </li>
                            </ul>
                            <a href="{{ route('register', ['id' => $pack->id]) }}" 
                               class="btn btn-lg w-100 rounded-pill" 
                               style="background: {{$pack->couleur}}; color: {{$pack->text}};">
                                Choisir ce pack
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-gradient-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 text-center text-lg-start">
                    <h2 class="display-6 fw-bold mb-3">Prêt à transformer votre établissement ?</h2>
                    <p class="lead mb-0 opacity-75">Rejoignez les établissements qui nous font confiance</p>
                </div>
                <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
                    <a href="{{ route('register', ['id' => 1]) }}" class="btn btn-light btn-lg rounded-pill px-4">
                        Commencer maintenant
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

@section("css")
<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #479CD5, #FF8A00);
}

.hero-shapes div {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.shape-1 {
    width: 300px;
    height: 300px;
    top: -150px;
    right: -150px;
}

.shape-2 {
    width: 200px;
    height: 200px;
    bottom: -100px;
    left: -100px;
}

.shape-3 {
    width: 150px;
    height: 150px;
    bottom: 50%;
    right: 10%;
}

.hover-translate {
    transition: transform 0.3s ease;
}

.hover-translate:hover {
    transform: translateY(-5px);
}

.feature-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    border-radius: 50%;
}

.bg-primary-soft { background-color: rgba(41, 55, 240, 0.1); }
.bg-success-soft { background-color: rgba(40, 167, 69, 0.1); }
.bg-info-soft { background-color: rgba(23, 162, 184, 0.1); }

.floating-dashboard {
    animation: float 6s ease-in-out infinite;
}

.animation-float {
    animation: float 4s ease-in-out infinite;
}

.delay-1 {
    animation-delay: 1s;
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
    100% { transform: translateY(0px); }
}

.pricing-icon {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    border-radius: 50%;
    margin: 0 auto;
}

.counter {
    font-size: 2.5rem;
    font-weight: bold;
    background: linear-gradient(45deg, #479CD5, #FF8A00);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
</style>
@endsection