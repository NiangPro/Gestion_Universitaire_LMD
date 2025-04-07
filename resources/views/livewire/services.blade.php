<div>
    <!-- Hero Section Amélioré -->
    <section class="bg-gradient-primary text-white py-5" style="margin-top: 70px; background: linear-gradient(135deg, #479CD5 45%, #FF8A00 0%, #479CD5 50%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0 animate__animated animate__fadeInLeft">
                    <span class="badge bg-white text-primary px-3 py-2 mb-3">Solutions LMD</span>
                    <h1 class="display-4 font-weight-bold mb-3">
                        La Gestion Universitaire Réinventée
                    </h1>
                    <p class="lead mb-4">
                        Une plateforme tout-en-un conçue pour les établissements d'enseignement supérieur
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#pricing" class="btn btn-light btn-lg px-4">
                            Voir nos offres
                        </a>
                        <a href="#services" class="btn btn-outline-light btn-lg px-4">
                            Découvrir les services
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 animate__animated animate__fadeInRight">
                    <div class="position-relative">
                        <img src="{{ asset('images/services.png') }}" class="img-fluid rounded-lg shadow-xl" 
                             style="border-radius: 20px; transform: perspective(1000px) rotateY(-10deg);">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary" 
                             style="opacity: 0.1; border-radius: 20px; transform: translateX(-10px) translateY(10px);"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Statistiques -->
    <section class="py-4">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <div class="stat-card p-4 rounded-lg bg-light">
                        <i class="fas fa-school fa-2x text-primary mb-2"></i>
                        <h3 class="counter mb-1">100+</h3>
                        <p class="text-muted mb-0">Établissements</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card p-4 rounded-lg bg-light">
                        <i class="fas fa-users-cog fa-2x text-success mb-2"></i>
                        <h3 class="counter mb-1">50k+</h3>
                        <p class="text-muted mb-0">Utilisateurs</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card p-4 rounded-lg bg-light">
                        <i class="fas fa-user-graduate fa-2x text-info mb-2"></i>
                        <h3 class="counter mb-1">10k+</h3>
                        <p class="text-muted mb-0">Diplômés</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card p-4 rounded-lg bg-light">
                        <i class="fas fa-chart-line fa-2x text-warning mb-2"></i>
                        <h3 class="counter mb-1">95%</h3>
                        <p class="text-muted mb-0">Satisfaction</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section Améliorée -->
    <section id="services" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-primary text-white px-3 py-2 mb-3">Nos Services</span>
                <h2 class="h1 mb-3">Une Solution Complète</h2>
                <p class="lead text-muted mx-auto" style="max-width: 600px;">
                    Découvrez nos services premium conçus pour optimiser la gestion de votre établissement
                </p>
            </div>

            <div class="row g-4">
                <!-- Gestion Administrative -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card h-100 bg-white rounded-lg p-4 shadow-sm hover-translate">
                        <div class="service-icon mb-4">
                            <span class="icon-circle bg-primary-soft text-primary">
                                <i class="fas fa-university fa-2x"></i>
                            </span>
                        </div>
                        <h3 class="h5 mb-3">Gestion Administrative</h3>
                        <p class="text-muted mb-4">Simplifiez vos processus administratifs avec nos outils intégrés.</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle text-success"></i> Inscriptions en ligne</li>
                            <li><i class="fas fa-check-circle text-success"></i> Gestion des dossiers</li>
                            <li><i class="fas fa-check-circle text-success"></i> Suivi financier</li>
                        </ul>
                    </div>
                </div>

                <!-- Gestion Pédagogique -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card h-100 bg-white rounded-lg p-4 shadow-sm hover-translate">
                        <div class="service-icon mb-4">
                            <span class="icon-circle bg-success-soft text-success">
                                <i class="fas fa-graduation-cap fa-2x"></i>
                            </span>
                        </div>
                        <h3 class="h5 mb-3">Gestion Pédagogique LMD</h3>
                        <p class="text-muted mb-4">Gérez efficacement votre système LMD et le suivi des étudiants.</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle text-success"></i> UE et crédits</li>
                            <li><i class="fas fa-check-circle text-success"></i> Notes et évaluations</li>
                            <li><i class="fas fa-check-circle text-success"></i> Emplois du temps</li>
                        </ul>
                    </div>
                </div>

                <!-- Communication -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card h-100 bg-white rounded-lg p-4 shadow-sm hover-translate">
                        <div class="service-icon mb-4">
                            <span class="icon-circle bg-info-soft text-info">
                                <i class="fas fa-comments fa-2x"></i>
                            </span>
                        </div>
                        <h3 class="h5 mb-3">Communication Intégrée</h3>
                        <p class="text-muted mb-4">Facilitez les échanges entre tous les acteurs de votre établissement.</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle text-success"></i> Messagerie interne</li>
                            <li><i class="fas fa-check-circle text-success"></i> Notifications</li>
                            <li><i class="fas fa-check-circle text-success"></i> Partage documentaire</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Prix Améliorée -->
    <section id="pricing" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-primary text-white px-3 py-2 mb-3">Tarification</span>
                <h2 class="h1 mb-3">Choisissez votre Pack</h2>
                <p class="lead text-muted mx-auto" style="max-width: 600px;">
                    Des solutions adaptées à tous les types d'établissements
                </p>
            </div>

            <div class="row justify-content-center g-4">
                @foreach($packs as $pack)
                <div class="col-md-4">
                    <div class="pricing-card h-100 bg-white rounded-lg shadow-sm hover-translate">
                        <div class="pricing-header p-4 text-center" 
                             style="background-color: {{ $pack->couleur }}; border-radius: 12px 12px 0 0;">
                            <h3 class="h4 mb-0" style="color: {{ $pack->text }}">{{ $pack->nom }}</h3>
                        </div>
                        <div class="pricing-body p-4">
                            <div class="text-center mb-4">
                                <div class="h1 mb-0 text-primary">
                                    {{ number_format($pack->mensuel, 0, ',', ' ') }} 
                                    <span class="h5">FCFA</span>
                                </div>
                                <span class="text-muted">par mois</span>
                            </div>
                            <div class="annual-price text-center mb-4">
                                <div class="badge bg-success">Économisez en annuel</div>
                                <div class="text-success mt-2">
                                    {{ number_format($pack->annuel, 0, ',', ' ') }} FCFA/an
                                </div>
                            </div>
                            <ul class="feature-list mb-4">
                                <li>
                                    <i class="fas fa-users text-primary"></i>
                                    Jusqu'à {{ number_format($pack->limite, 0, ',', ' ') }} utilisateurs
                                </li>
                                <li><i class="fas fa-check-circle text-success"></i> Gestion des notes</li>
                                <li><i class="fas fa-check-circle text-success"></i> Emplois du temps</li>
                                <li><i class="fas fa-check-circle text-success"></i> Support technique</li>
                            </ul>
                            <a href="{{ route('register', ['id' => $pack->id]) }}" 
                               class="btn btn-primary btn-lg w-100">
                                Choisir ce pack
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section Améliorée -->
    <section class="py-5 bg-gradient-primary text-white">
        <div class="container text-center">
            <h2 class="mb-4">Besoin d'aide pour choisir ?</h2>
            <p class="lead mb-4">Notre équipe d'experts est là pour vous guider</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#" class="btn btn-light btn-lg px-4">
                    <i class="fas fa-phone-alt me-2"></i>Nous contacter
                </a>
                <a href="#" class="btn btn-outline-light btn-lg px-4">
                    <i class="fas fa-calendar-alt me-2"></i>Démonstration
                </a>
            </div>
        </div>
    </section>
</div>

@section("css")
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #479CD5 100%, #FF8A00 0%);
}

.hover-translate {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-translate:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.icon-circle {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-primary-soft { background-color: rgba(78, 115, 223, 0.1); }
.bg-success-soft { background-color: rgba(40, 167, 69, 0.1); }
.bg-info-soft { background-color: rgba(23, 162, 184, 0.1); }

.feature-list {
    list-style: none;
    padding: 0;
}

.feature-list li {
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pricing-card {
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.stat-card {
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.counter {
    font-size: 2.5rem;
    font-weight: bold;
    color: #479CD5;
}

.shadow-xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

@media (max-width: 768px) {
    .counter {
        font-size: 2rem;
    }
    
    .service-card {
        margin-bottom: 1rem;
    }
}
</style>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"></script>
@endpush
