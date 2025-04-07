<div>
    <!-- Hero Section avec animation et design moderne -->
    <section class="bg-gradient-primary text-white py-5" style="margin-top: 70px; background: linear-gradient(135deg, #FF8A00 0%, #479CD5 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="display-4 font-weight-bold mb-4 animate__animated animate__fadeInLeft">
                        Gérez votre établissement efficacement
                    </h1>
                    <p class="lead mb-4 animate__animated animate__fadeInLeft animate__delay-1s">
                        Une solution complète et intuitive conçue pour le système LMD
                    </p>
                    <div class="d-flex gap-3 animate__animated animate__fadeInUp animate__delay-2s">
                        <a href="{{ route('register', ['id' => 1]) }}" class="btn btn-light btn-lg px-4">
                            Commencer gratuitement
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-lg px-4">
                            Découvrir les fonctionnalités
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 animate__animated animate__fadeInRight">
                    <div class="position-relative">
                        <img src="{{ asset('images/fonctionnalite.png') }}" class="img-fluid rounded-lg shadow-lg" 
                             style="border-radius: 20px; transform: perspective(1000px) rotateY(-10deg);">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary" 
                             style="opacity: 0.1; border-radius: 20px; transform: translateX(-10px) translateY(10px);"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section des statistiques -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="card border-0 shadow-sm h-100 py-4">
                        <div class="card-body">
                            <i class="fas fa-university fa-3x text-primary mb-3"></i>
                            <h2 class="counter mb-2">100+</h2>
                            <p class="text-muted mb-0">Établissements</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="card border-0 shadow-sm h-100 py-4">
                        <div class="card-body">
                            <i class="fas fa-users fa-3x text-success mb-3"></i>
                            <h2 class="counter mb-2">10k+</h2>
                            <p class="text-muted mb-0">Utilisateurs actifs</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="card border-0 shadow-sm h-100 py-4">
                        <div class="card-body">
                            <i class="fas fa-graduation-cap fa-3x text-info mb-3"></i>
                            <h2 class="counter mb-2">50k+</h2>
                            <p class="text-muted mb-0">Étudiants gérés</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100 py-4">
                        <div class="card-body">
                            <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                            <h2 class="counter mb-2">24/7</h2>
                            <p class="text-muted mb-0">Support disponible</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section des fonctionnalités principales -->
    <section id="features" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Nos fonctionnalités principales</h2>
            
            <!-- Gestion académique -->
            <div class="feature-card mb-5">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="card border-0 shadow-lg hover-scale">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="feature-icon bg-primary text-white rounded-circle p-3 me-3">
                                        <i class="fas fa-graduation-cap fa-2x"></i>
                                    </div>
                                    <h3 class="h4 mb-0">Gestion Académique LMD</h3>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <ul class="list-unstyled feature-list">
                                            <li class="mb-3">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Unités d'enseignement
                                            </li>
                                            <li class="mb-3">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Gestion des crédits
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <ul class="list-unstyled feature-list">
                                            <li class="mb-3">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Semestres
                                            </li>
                                            <li class="mb-3">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Parcours
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="text-primary mb-3">Organisation académique optimisée</h4>
                        <p class="text-muted">Gérez efficacement votre structure LMD avec nos outils spécialisés pour les universités et grandes écoles.</p>
                        <a href="#" class="btn btn-outline-primary">En savoir plus</a>
                    </div>
                </div>
            </div>

            <!-- Gestion des utilisateurs -->
            <div class="feature-card mb-5">
                <div class="row align-items-center flex-lg-row-reverse">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="card border-0 shadow-lg hover-scale">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="feature-icon bg-success text-white rounded-circle p-3 me-3">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                    <h3 class="h4 mb-0">Gestion des Acteurs</h3>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <ul class="list-unstyled feature-list">
                                            <li class="mb-3">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Étudiants
                                            </li>
                                            <li class="mb-3">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Professeurs
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <ul class="list-unstyled feature-list">
                                            <li class="mb-3">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Parents
                                            </li>
                                            <li class="mb-3">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Administration
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="text-success mb-3">Gestion complète des utilisateurs</h4>
                        <p class="text-muted">Une interface intuitive pour gérer tous les acteurs de votre établissement avec des droits d'accès personnalisés.</p>
                        <a href="#" class="btn btn-outline-success">En savoir plus</a>
                    </div>
                </div>
            </div>

            <!-- Suivi et évaluation -->
            <div class="feature-card">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="card border-0 shadow-lg hover-scale">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="feature-icon bg-info text-white rounded-circle p-3 me-3">
                                        <i class="fas fa-chart-line fa-2x"></i>
                                    </div>
                                    <h3 class="h4 mb-0">Suivi et Évaluation</h3>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <ul class="list-unstyled feature-list">
                                            <li class="mb-3">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Notes et moyennes
                                            </li>
                                            <li class="mb-3">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Bulletins
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <ul class="list-unstyled feature-list">
                                            <li class="mb-3">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Absences
                                            </li>
                                            <li class="mb-3">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Statistiques
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="text-info mb-3">Suivi personnalisé</h4>
                        <p class="text-muted">Suivez la progression de vos étudiants et générez des rapports détaillés en quelques clics.</p>
                        <a href="#" class="btn btn-outline-info">En savoir plus</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action amélioré -->
    <!-- <section class="py-5 bg-gradient-primary text-white" style="background: linear-gradient(135deg, #FF8A00 0%, #479CD5 100%);">
        <div class="container text-center">
            <h2 class="mb-4">Prêt à transformer votre gestion académique ?</h2>
            <p class="lead mb-4">Rejoignez les établissements qui nous font confiance</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('register', ['id' => 1]) }}" class="btn btn-light btn-lg px-5">
                    Essai gratuit
                </a>
                <a href="#" class="btn btn-outline-light btn-lg px-5">
                    Nous contacter
                </a>
            </div>
        </div>
    </section> -->
</div>

@section("css")
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #479CD5 0%, #FF8A00 100%);
}

.hover-scale {
    transition: transform 0.3s ease;
}

.hover-scale:hover {
    transform: translateY(-5px);
}

.feature-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.feature-list li {
    display: flex;
    align-items: center;
}

.feature-card {
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
}

.counter {
    font-size: 2.5rem;
    font-weight: bold;
    color: #479CD5;
}

@media (max-width: 768px) {
    .counter {
        font-size: 2rem;
    }
}
</style>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"></script>
@endpush
