@section('css')
<style>
    /* Custom styles for the pricing section */
    .pricing-section {
        background-color: #f5f5f5;
        padding: 50px 0;
      }
      .card-pricing {
        border: none;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        margin-bottom: 30px;
      }
      .card-pricing .card-header {
        font-size: 1.5rem;
        font-weight: bold;
        padding: 20px 0;
        border-radius: 10px 10px 0 0;
        text-align: center;
      }
      .bg-green {
        background-color: #6BCB77;
        color: white;
      }
      .bg-blue {
        background-color: #5bc0de;
        color: white;
      }
      .bg-pink {
        background-color: #f06292;
        color: white;
      }
      .pricing-price {
        font-size: 2rem;
        font-weight: bold;
      }
      .pricing-subtext {
        font-size: 0.9rem;
        color: #666;
      }
      .pricing-list {
        list-style: none;
        padding: 0;
        margin: 20px 0;
      }
      .pricing-list li {
        font-size: 1rem;
        margin-bottom: 10px;
      }
      .btn-pricing {
        border-radius: 30px;
        font-size: 1.1rem;
        padding: 10px 20px;
        width: 100%;
      }
  </style>
@endsection
<div>
    <!-- Section Tarifs (avec l'image) -->
  <section class="pricing-section">
    <div class="container">
      <h2 class="text-center mb-5">Tarifs Simples et Sans Surprises</h2>
      <div class="row justify-content-center">
  
        <!-- Small Plan -->
        <div class="col-lg-4 col-md-6">
          <div class="card card-pricing">
            <div class="card-header bg-green text-center">
              Small Plan
            </div>
            <div class="card-body text-center">
              <h3 class="pricing-price">450 000 XOF</h3>
              <p class="pricing-subtext">0 à 599 élèves <br> soit 50 000 / mois facturé annuellement (9 mois)</p>
              <ul class="pricing-list">
                <li>✓ Toutes les fonctionnalités My-Scool</li>
                <li>✓ Utilisateur illimité</li>
                <li>✓ Application mobile</li>
                <li>✓ Accès parents / profs / administration</li>
                <li>✓ Disponible 24/24 - 7/7</li>
                <li>✓ Maintenance et évolution inclus</li>
              </ul>
              <a href="#" class="btn btn-success btn-pricing">Souscrire</a>
            </div>
          </div>
        </div>
  
        <!-- Medium Plan -->
        <div class="col-lg-4 col-md-6">
          <div class="card card-pricing">
            <div class="card-header bg-blue text-center">
              Medium Plan
            </div>
            <div class="card-body text-center">
              <h3 class="pricing-price">720 000 XOF</h3>
              <p class="pricing-subtext">600 à 1099 élèves <br> soit 80 000 / mois facturé annuellement (9 mois)</p>
              <ul class="pricing-list">
                <li>✓ Toutes les fonctionnalités My-Scool</li>
                <li>✓ Utilisateur illimité</li>
                <li>✓ Application mobile</li>
                <li>✓ Accès parents / profs / administration</li>
                <li>✓ Disponible 24/24 - 7/7</li>
                <li>✓ Maintenance et évolution inclus</li>
              </ul>
              <a href="#" class="btn btn-info btn-pricing">Souscrire</a>
            </div>
          </div>
        </div>
  
        <!-- Large Plan -->
        <div class="col-lg-4 col-md-6">
          <div class="card card-pricing">
            <div class="card-header bg-pink text-center">
              Large Plan
            </div>
            <div class="card-body text-center">
              <h3 class="pricing-price">990 000 XOF</h3>
              <p class="pricing-subtext">1100 à 1999 élèves <br> soit 110 000 / mois facturé annuellement (9 mois)</p>
              <ul class="pricing-list">
                <li>✓ Toutes les fonctionnalités My-Scool</li>
                <li>✓ Utilisateur illimité</li>
                <li>✓ Application mobile</li>
                <li>✓ Accès parents / profs / administration</li>
                <li>✓ Disponible 24/24 - 7/7</li>
                <li>✓ Maintenance et évolution inclus</li>
              </ul>
              <a href="#" class="btn btn-danger btn-pricing">Souscrire</a>
            </div>
          </div>
        </div>
  
      </div>
    </div>
  </section>
</div>
