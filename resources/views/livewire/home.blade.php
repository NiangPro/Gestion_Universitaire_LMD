<div>
    <!-- Hero Section -->
    <section class="hero-section position-relative overflow-hidden bg-gradient-primary text-white">
        <div class="container position-relative py-2" style="z-index: 2;">
            <div class="row min-vh-100 align-items-center">
                <div class="col-lg-6">
                    <span class="badge mb-3 px-3 py-2" style="background-color: #479CD5; color: #FF8A00;">Plateforme de Gestion Universitaire</span>
                    <h1 class="display-3 fw-bold mb-4">
                        Transformez la Gestion de Votre Établissement Université
                    </h1>
                    <p class="lead mb-4 opacity-75">
                        Une solution tout-en-un pour gérer efficacement votre université. De l'administration aux résultats, en passant par la communication.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('register', ['id' => 1]) }}" class="btn btn-light btn-lg px-4 rounded-pill">
                            Commencer gratuitement
                        </a>
                        <a href="#demo" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                            Voir la démo
                        </a>
                    </div>
                    <div class="mt-5 d-flex gap-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-white p-2 me-2">
                                <i class="fas fa-check text-primary"></i>
                            </div>
                            <span>Installation rapide</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-white p-2 me-2">
                                <i class="fas fa-check text-primary"></i>
                            </div>
                            <span>Support 24/7</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="position-relative">
                        <div class="floating-dashboard">
                            <video src="{{ asset('images/home.mp4') }}" autoplay muted loop class="img-fluid rounded-lg shadow-2xl" style="border-radius: 20px; box-shadow: 5 5 10px 0 rgba(246, 156, 46, 0.5);"></video>
                            <!-- <img src="{{ asset('images/services.png') }}" alt="Dashboard" class="img-fluid rounded-lg shadow-2xl"> -->
                        </div>
                        <!-- <div class="position-absolute top-0 end-0 animation-float delay-1">
                            <div class="card bg-white p-3 rounded-lg shadow-sm">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-graduation-cap text-primary fa-2x me-3"></i>
                                    <div>
                                        <h6 class="text-dark mb-0">1000+ Étudiants</h6>
                                        <small class="text-muted">Gestion simplifiée</small>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="position-absolute bottom-0 end-0 animation-float delay-1">
                            <div class="card bg-white p-3 rounded-lg shadow-sm">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-school text-primary fa-2x me-3"></i>
                                    <div>
                                        <h6 class="text-dark mb-0">10+ Établissements</h6>
                                        <small class="text-muted">Gestion simplifiée</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-shapes">
            <div class="shape-1"></div>
            <div class="shape-2"></div>
            <div class="shape-3"></div>
        </div>
    </section>

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

    <!-- Fonctionnalités Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Fonctionnalités Principales</h2>
                <p class="lead text-muted">Découvrez nos outils puissants pour une gestion efficace</p>
            </div>

            <div class="row g-4">
                <!-- Gestion des Étudiants -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card border-0 shadow-sm h-100 hover-translate">
                        <div class="card-body p-4">
                            <div class="feature-icon bg-primary-soft rounded-circle mb-4">
                                <i class="fas fa-user-graduate text-primary"></i>
                            </div>
                            <h4 class="mb-3">Gestion des Étudiants</h4>
                            <p class="text-muted mb-0">Gérez efficacement les dossiers étudiants, les inscriptions et suivez leurs performances académiques.</p>
                        </div>
                    </div>
                </div>

                <!-- Gestion des Notes -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card border-0 shadow-sm h-100 hover-translate">
                        <div class="card-body p-4">
                            <div class="feature-icon bg-success-soft rounded-circle mb-4">
                                <i class="fas fa-chart-line text-success"></i>
                            </div>
                            <h4 class="mb-3">Gestion des Notes</h4>
                            <p class="text-muted mb-0">Système complet de notation avec calcul automatique des moyennes et génération de bulletins.</p>
                        </div>
                    </div>
                </div>

                <!-- Emploi du Temps -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card border-0 shadow-sm h-100 hover-translate">
                        <div class="card-body p-4">
                            <div class="feature-icon bg-info-soft rounded-circle mb-4">
                                <i class="fas fa-calendar-alt text-info"></i>
                            </div>
                            <h4 class="mb-3">Emploi du Temps</h4>
                            <p class="text-muted mb-0">Planifiez et gérez facilement les emplois du temps des classes et des professeurs.</p>
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