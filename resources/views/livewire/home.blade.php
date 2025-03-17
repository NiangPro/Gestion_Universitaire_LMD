<div>
<div>
    <!-- Hero Section avec Animation -->
    <section class="hero-section position-relative overflow-hidden">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row min-vh-100 align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold mb-4 text-gradient">
                        La Gestion Scolaire Réinventée
                    </h1>
                    <p class="lead mb-4">
                        Transformez votre établissement avec notre solution tout-en-un de gestion scolaire intelligente.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#pricing" class="btn btn-primary btn-lg px-4 rounded-pill">
                            Commencer maintenant
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                            Découvrir
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="floating-image">
                        <img src="{{ asset('images/dashboard-preview.png') }}" alt="Dashboard Preview" class="img-fluid rounded-lg shadow-2xl">
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

    <!-- Statistiques -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-3 col-6">
                    <div class="stat-card p-4 rounded-lg bg-white shadow-sm">
                        <h3 class="counter mb-2">1000+</h3>
                        <p class="text-muted mb-0">Établissements</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card p-4 rounded-lg bg-white shadow-sm">
                        <h3 class="counter mb-2">50k+</h3>
                        <p class="text-muted mb-0">Étudiants</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card p-4 rounded-lg bg-white shadow-sm">
                        <h3 class="counter mb-2">5k+</h3>
                        <p class="text-muted mb-0">Professeurs</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card p-4 rounded-lg bg-white shadow-sm">
                        <h3 class="counter mb-2">99.9%</h3>
                        <p class="text-muted mb-0">Disponibilité</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fonctionnalités -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 mb-3">Fonctionnalités Principales</h2>
                <p class="lead text-muted">Une solution complète pour votre établissement</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card card border-0 shadow-hover h-100">
                        <div class="card-body p-4">
                            <div class="feature-icon mb-4">
                                <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                            </div>
                            <h4 class="mb-3">Gestion des Étudiants</h4>
                            <p class="text-muted mb-0">Gérez efficacement les dossiers étudiants, les inscriptions et le suivi des performances.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card card border-0 shadow-hover h-100">
                        <div class="card-body p-4">
                            <div class="feature-icon mb-4">
                                <i class="fas fa-chalkboard-teacher fa-2x text-success"></i>
                            </div>
                            <h4 class="mb-3">Gestion des Professeurs</h4>
                            <p class="text-muted mb-0">Optimisez la gestion des emplois du temps et le suivi des cours.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card card border-0 shadow-hover h-100">
                        <div class="card-body p-4">
                            <div class="feature-icon mb-4">
                                <i class="fas fa-chart-line fa-2x text-info"></i>
                            </div>
                            <h4 class="mb-3">Suivi en Temps Réel</h4>
                            <p class="text-muted mb-0">Accédez aux statistiques et rapports détaillés en temps réel.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 mb-3">Tarifs Transparents</h2>
                <p class="lead text-muted">Choisissez le plan qui correspond à vos besoins</p>
            </div>

            <div class="row g-4 justify-content-center">
                @foreach ($packs as $p)
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card card border-0 shadow-hover h-100">
                        <div class="card-header border-0 py-4" style="background: {{$p->couleur}};">
                            <h3 class="mb-0" style="color: {{$p->text}};">{{$p->nom}}</h3>
                        </div>
                        <div class="card-body p-4">
                            <div class="price-section text-center mb-4">
                                <h2 class="display-4 mb-0">{{ number_format($p->mensuel, 0, ",", " ") }}</h2>
                                <p class="text-muted">XOF/mois</p>
                                <p class="annual-price">ou {{ number_format($p->annuel, 0, ",", " ") }} XOF/an</p>
                            </div>
                            <div class="features-list mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>Jusqu'à {{$p->limite}} élèves</span>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>Utilisateurs illimités</span>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>Support 24/7</span>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>Mises à jour gratuites</span>
                                </div>
                            </div>
                            <a href="{{route('register',['id'=>$p->id])}}" 
                               class="btn btn-lg w-100 rounded-pill" 
                               style="background: {{$p->couleur}}; color: {{$p->text}};">
                                Commencer
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 text-center text-lg-start">
                    <h2 class="mb-3">Prêt à transformer votre établissement ?</h2>
                    <p class="lead mb-0">Rejoignez les milliers d'établissements qui nous font confiance.</p>
                </div>
                <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
                    <a href="#pricing" class="btn btn-primary btn-lg rounded-pill px-4">
                        Commencer maintenant
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* Styles généraux */
.text-gradient {
    background: linear-gradient(45deg, #2937f0, #9f1ae2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #0c1027 0%, #2937f0 100%);
    color: white;
    position: relative;
    overflow: hidden;
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

.floating-image {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
    100% { transform: translateY(0px); }
}

/* Cards */
.shadow-hover {
    transition: all 0.3s ease;
}

.shadow-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
}

.feature-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(41, 55, 240, 0.1);
}

/* Pricing Cards */
.pricing-card {
    transition: all 0.3s ease;
}

.pricing-card:hover {
    transform: scale(1.02);
}

.annual-price {
    font-size: 0.9rem;
    color: #6c757d;
}

/* Stats */
.counter {
    font-size: 2.5rem;
    font-weight: bold;
    background: linear-gradient(45deg, #2937f0, #9f1ae2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* CTA Section */
.cta-section {
    background: linear-gradient(45deg, #f8f9fa, #e9ecef);
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section {
        text-align: center;
    }
}
</style>
</div>