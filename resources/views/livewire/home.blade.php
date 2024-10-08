<div>
    <!-- En-tête -->
  <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="#">Université</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          
          <li class="nav-item btn btn-primary btn-sm">
            <a href="{{route('login')}}" class="nav-link text-white">Connexion</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Section Héros -->
  <section class="bg-primary mt-5 text-white text-center py-5">
    <div class="container mt-3">
      <h1>Bienvenue sur la plateforme de gestion d'université</h1>
      <p>Gérez vos cours, étudiants et professeurs facilement avec notre solution</p>
    </div>
  </section>

  <!-- Section Fonctionnalités -->
  <section class="py-5">
    <div class="container">
      <h2 class="text-center mb-4">Fonctionnalités principales</h2>
      <div class="row">
        <div class="col-md-4">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Gestion des cours</h5>
              <p class="card-text">Créez et gérez facilement vos cours avec notre interface conviviale.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Gestion des étudiants</h5>
              <p class="card-text">Suivez les performances des étudiants et gérez les inscriptions.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Gestion des professeurs</h5>
              <p class="card-text">Gérez les emplois du temps des enseignants et suivez leurs performances.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="pricing-section text-center">
    <div class="container">
      <h2 class="mb-5">TARIFS SIMPLES ET SANS SURPRISES</h2>
      <div class="row justify-content-center">
  
        @foreach ($packs as $p)
            
        <!-- Small Plan -->
        <div class="col-lg-4 col-md-6">
          <div class="card card-pricing">
            <div class="card-header" style="background: {{$p->couleur}};color:{{$p->text}};">
              {{$p->nom}}
            </div>
            <div class="card-body">
              <h3 class="pricing-price">{{ number_format($p->annuel, 0, ",", " ") }} XOF/an</h3>
              <p class="pricing-subtext">0 à {{$p->limite}} élèves <br>  {{ number_format($p->mensuel, 0, ",", " ") }} / mois facturé annuellement <br> sur ({{floor($p->annuel/$p->mensuel)}} mois)</p>
              <ul class="pricing-list">
                <li><img src="https://img.icons8.com/color/48/000000/checkmark.png" class="icon-check"/>Utilisateur illimité</li>
                <li><img src="https://img.icons8.com/color/48/000000/checkmark.png" class="icon-check"/>Accès parents / profs / administration</li>
                <li><img src="https://img.icons8.com/color/48/000000/checkmark.png" class="icon-check"/>Disponible 24/24 - 7/7</li>
                <li><img src="https://img.icons8.com/color/48/000000/checkmark.png" class="icon-check"/>Maintenance et évolution inclus</li>
              </ul>
              <a href="{{route('register',['id'=>$p->id])}}" class="btn btn-pricing" style="background: {{$p->couleur}};color:{{$p->text}}">SOUSCRIRE</a>
            </div>
          </div>
        </div>
        @endforeach
  
      </div>
    </div>
  </section>
  
</div>
@section('css')
<style>
  /* Custom styles for pricing cards */
  .pricing-section {
    background-color: #f5f5f5;
    padding: 50px 0;
  }
  .card-pricing {
    background-color: #000;
    color: #fff;
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
  }
  .card-header {
    padding: 20px;
    text-align: center;
    font-size: 1.5rem;
    font-weight: bold;
    border-radius: 10px 10px 0 0;
    color: #fff;
  }
  .pricing-price {
    font-size: 2.0rem;
    font-weight: bold;
    margin: 20px 0;
  }
  .pricing-subtext {
    font-size: 1rem;
    margin-bottom: 20px;
    color: #bdbdbd;
  }
  .pricing-list {
    list-style: none;
    padding: 0;
    margin-bottom: 30px;
    text-align: left;
  }
  .pricing-list li {
    margin-bottom: 15px;
    font-size: 1.1rem;
  }
  .btn-pricing {
    padding: 10px 30px;
    border-radius: 30px;
    font-size: 1.1rem;
    font-weight: bold;
  }
  .icon-check {
    width: 1.5rem;
    height: 1.5rem;
    margin-right: 10px;
    vertical-align: middle;
  }
  /* Adjustments for card styling */
  .medium-card {
    top: -20px;
    border-radius: 20px;
  }
  .card-body {
    padding: 20px 30px;
  }
</style>
@endsection
