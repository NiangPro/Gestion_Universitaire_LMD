<div>
    <!-- Hero Section -->
    <section class="bg-primary text-white py-5" style="margin-top: 70px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 font-weight-bold mb-3">Solutions de Gestion Universitaire</h1>
                    <p class="lead mb-4">Transformez votre établissement avec notre plateforme complète de gestion académique</p>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('storage/images/services.png') }}" style="box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.5);border-radius: 10px;"  alt="University Management" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Overview -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="h1 mb-3">Nos Services Premium</h2>
                <p class="lead text-muted">Des solutions adaptées à tous les types d'établissements</p>
            </div>

            <div class="row">
                <!-- Gestion Administrative -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-shadow">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <span class="bg-primary text-white rounded-circle p-3 mr-3">
                                    <i class="fas fa-university fa-2x"></i>
                                </span>
                                <h3 class="h5 mb-0">Gestion Administrative</h3>
                            </div>
                            <p class="text-muted">Gérez efficacement votre établissement avec nos outils administratifs avancés.</p>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Inscription en ligne</li>
                                <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Gestion des dossiers</li>
                                <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Suivi des paiements</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Gestion Pédagogique -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-shadow">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <span class="bg-success text-white rounded-circle p-3 mr-3">
                                    <i class="fas fa-graduation-cap fa-2x"></i>
                                </span>
                                <h3 class="h5 mb-0">Gestion Pédagogique</h3>
                            </div>
                            <p class="text-muted">Optimisez le suivi pédagogique de vos étudiants.</p>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Emplois du temps</li>
                                <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Notes et évaluations</li>
                                <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Suivi des absences</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Communication -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-shadow">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <span class="bg-info text-white rounded-circle p-3 mr-3">
                                    <i class="fas fa-comments fa-2x"></i>
                                </span>
                                <h3 class="h5 mb-0">Communication</h3>
                            </div>
                            <p class="text-muted">Facilitez la communication entre tous les acteurs.</p>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Messagerie interne</li>
                                <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Notifications</li>
                                <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Partage de documents</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Plans -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="h1 mb-3">Nos Packs</h2>
                <p class="lead text-muted">Choisissez le pack qui correspond à vos besoins</p>
            </div>

            <div class="row justify-content-center">
                @foreach($packs as $pack)
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm text-center hover-shadow h-100">
                        <div class="card-header py-4" style="background-color: {{ $pack->couleur }}; color: {{ $pack->text }}">
                            <h4 class="my-0 font-weight-normal">{{ $pack->nom }}</h4>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="mb-4">
                                <h2 class="card-title pricing-card-title mb-1">
                                    {{ number_format($pack->mensuel, 0, ',', ' ') }} FCFA
                                    <small class="text-muted">/ mois</small>
                                </h2>
                                <p class="text-muted">
                                    ou {{ number_format($pack->annuel, 0, ',', ' ') }} FCFA / an
                                </p>
                            </div>

                            <ul class="list-unstyled mt-3 mb-4">
                                <li class="mb-2">
                                    <i class="fas fa-users text-success mr-2"></i>
                                    Jusqu'à {{ number_format($pack->limite, 0, ',', ' ') }} utilisateurs
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success mr-2"></i>
                                    Gestion des notes
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success mr-2"></i>
                                    Emplois du temps
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success mr-2"></i>
                                    Support technique
                                </li>
                            </ul>

                            <div class="mt-auto">
                                <a href="{{ route('register', ['id' => $pack->id]) }}" 
                                   class="btn btn-lg btn-block {{ $pack->text === '#ffffff' ? 'btn-light' : 'btn-outline-primary' }}"
                                   style="border-color: {{ $pack->couleur }}; color: {{ $pack->couleur }}">
                                    Choisir ce pack
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="py-5">
        <div class="container text-center">
            <h2 class="mb-4">Besoin d'informations supplémentaires ?</h2>
            <p class="lead text-muted mb-4">Notre équipe est là pour vous accompagner dans le choix de votre solution</p>
            <a href="#" class="btn btn-primary btn-lg px-5">Contactez-nous</a>
        </div>
    </section>
</div>

@section('css')
<style>
    .bg-gradient-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
    }

    .hover-shadow {
        transition: all 0.3s ease;
    }

    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    }

    .rounded-circle {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .border-2 {
        border-width: 2px !important;
    }

    .card-header {
        border-bottom: none;
    }

    .pricing-card-title {
        font-size: 2.5rem;
        font-weight: 600;
    }

    .btn-outline-primary:hover {
        color: #fff !important;
    }

    /* Ajoutez ces styles pour assurer que les cartes ont la même hauteur */
    .card {
        height: 100%;
    }

    .card-body {
        display: flex;
        flex-direction: column;
    }

    .mt-auto {
        margin-top: auto;
    }
</style>
@endsection
