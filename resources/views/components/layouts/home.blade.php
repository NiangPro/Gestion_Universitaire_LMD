<!DOCTYPE html>
<html lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>Système LMD</title>


    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">


    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="{{asset('assets/css/theme.css')}}" rel="stylesheet" />
    <link href="{{asset('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&amp;display=swap')}}" rel="stylesheet">
@yield("css")
    @livewireStyles


  </head>


  <body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
      <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" data-navbar-on-scroll="data-navbar-on-scroll">
        <div class="container"><a class="navbar-brand d-flex align-items-center fw-semi-bold fs-3" href="{{route('home')}}">
           {{-- <img class="me-3" src="assets/img/gallery/logo.png" alt="" /> --}}
           Edu<span class="" style="color:rgb(230, 149, 9);">Link</span>
          </a>
          <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
          <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto pt-2 pt-lg-0 font-base">
              <li class="nav-item px-2" ><a class="nav-link fw-medium active" href="{{route('home')}}">Accueil</a></li>
              <li class="nav-item px-2" ><a class="nav-link" href="{{route('fonctionnalite')}}">Fonctionnalités</a></li>
               <li class="nav-item px-2"><a class="nav-link" href="{{ route('service')}}">Services </a></li>
              <!--<li class="nav-item px-2" data-anchor="data-anchor"><a class="nav-link" href="#testimonial">Testimonial </a></li> -->
            </ul>
            <form class="ps-lg-5">
              <a href="{{route('login')}}" class="btn btn-outline-primary order-0" >Connexion</a>
            </form>
          </div>
        </div>
      </nav>
     {{ $slot }}

      <!-- ============================================-->
      <!-- <section> begin ============================-->
      <section class="footer bg-primary py-5">
        <div class="container">
          <div class="row justify-content-between">
            <!-- Logo et Informations de Contact -->
            <div class="col-12 col-lg-4 mb-5 mb-lg-0">
              <div class="footer-brand mb-4">
                <h3 class="text-white fw-bold">SchoolManager</h3>
                <p class="text-light mb-3">La solution complète pour la gestion de votre établissement</p>
              </div>
              <div class="contact-info">
                <div class="d-flex align-items-center mb-3">
                  <i class="fas fa-map-marker-alt text-light me-3"></i>
                  <p class="text-light mb-0">
                    Cocody Riviera, Abidjan<br>
                    Côte d'Ivoire
                  </p>
                </div>
                <div class="d-flex align-items-center mb-3">
                  <i class="fas fa-phone text-light me-3"></i>
                  <p class="text-light mb-0">+225 07 07 07 07 07</p>
                </div>
                <div class="d-flex align-items-center">
                  <i class="fas fa-envelope text-light me-3"></i>
                  <p class="text-light mb-0">contact@schoolmanager.ci</p>
                </div>
              </div>
            </div>

            <!-- Liens Rapides -->
            <div class="col-6 col-lg-2 mb-4 mb-lg-0">
              <h5 class="text-white mb-4">Liens Rapides</h5>
              <ul class="list-unstyled footer-links">
                <li class="mb-3">
                  <a href="{{ route('home') }}" class="text-light text-decoration-none hover-opacity">Accueil</a>
                </li>
                <li class="mb-3">
                  <a href="{{ route('service') }}" class="text-light text-decoration-none hover-opacity">Services</a>
                </li>
                <li class="mb-3">
                  <a href="{{ route('fonctionnalite') }}" class="text-light text-decoration-none hover-opacity">Fonctionnalités</a>
                </li>
                <li class="mb-3">
                  <a href="{{ route('login') }}" class="text-light text-decoration-none hover-opacity">Connexion</a>
                </li>
              </ul>
            </div>

            <!-- Services -->
            <div class="col-6 col-lg-2 mb-4 mb-lg-0">
              <h5 class="text-white mb-4">Nos Services</h5>
              <ul class="list-unstyled footer-links">
                <li class="mb-3">
                  <a href="#" class="text-light text-decoration-none hover-opacity">Gestion Académique</a>
                </li>
                <li class="mb-3">
                  <a href="#" class="text-light text-decoration-none hover-opacity">Gestion des Notes</a>
                </li>
                <li class="mb-3">
                  <a href="#" class="text-light text-decoration-none hover-opacity">Emplois du Temps</a>
                </li>
                <li class="mb-3">
                  <a href="#" class="text-light text-decoration-none hover-opacity">Communication</a>
                </li>
              </ul>
            </div>

            <!-- Newsletter et Réseaux Sociaux -->
            <div class="col-12 col-lg-3">
              <h5 class="text-white mb-4">Restez Informé</h5>
              <p class="text-light mb-4">Abonnez-vous à notre newsletter pour recevoir nos actualités</p>
              <form class="mb-4">
                <div class="input-group">
                  <input type="email" class="form-control" placeholder="Votre email">
                  <button class="btn btn-light" type="submit">
                    <i class="fas fa-paper-plane"></i>
                  </button>
                </div>
              </form>
              <div class="social-links">
                <h6 class="text-white mb-3">Suivez-nous</h6>
                <div class="d-flex gap-3">
                  <a href="#" class="text-light social-link">
                    <i class="fab fa-facebook-f"></i>
                  </a>
                  <a href="#" class="text-light social-link">
                    <i class="fab fa-twitter"></i>
                  </a>
                  <a href="#" class="text-light social-link">
                    <i class="fab fa-linkedin-in"></i>
                  </a>
                  <a href="#" class="text-light social-link">
                    <i class="fab fa-instagram"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Séparateur -->
          <hr class="border-light my-4">

          <!-- Copyright et Liens Légaux -->
          <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
              <p class="text-light mb-0">
                &copy; {{ date('Y') }} SchoolManager. Tous droits réservés.
              </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
              <div class="legal-links">
                <a href="#" class="text-light text-decoration-none me-3 hover-opacity">Mentions légales</a>
                <a href="#" class="text-light text-decoration-none me-3 hover-opacity">Confidentialité</a>
                <a href="#" class="text-light text-decoration-none hover-opacity">CGU</a>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- <section> close ============================-->
      <!-- ============================================-->


    </main>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->




    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="{{asset('assets/vendors/@popperjs/popper.min.js')}}"></script>
    <script src="{{asset('assets/vendors/bootstrap/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/vendors/is/is.min.js')}}"></script>
    <script src="{{asset('https://polyfill.io/v3/polyfill.min.js?features=window.scroll')}}"></script>
    <script src="{{asset('assets/js/theme.js')}}"></script>

    @livewireScripts
  
  </body>

</html>

<style>
.footer {
    background: linear-gradient(45deg, #479CD5, #FF8A00);
}

.hover-opacity {
    transition: opacity 0.3s ease;
}

.hover-opacity:hover {
    opacity: 0.8;
}

.social-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.social-link:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-3px);
    text-decoration: none;
}

.footer-links a {
    transition: all 0.3s ease;
}

.footer-links a:hover {
    padding-left: 5px;
    opacity: 0.8;
}

.input-group {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 5px;
    overflow: hidden;
}

.input-group .form-control {
    background: transparent;
    border: none;
    color: white;
}

.input-group .form-control::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.input-group .btn {
    padding: 10px 20px;
}

.contact-info i {
    width: 20px;
}

@media (max-width: 768px) {
    .footer-brand {
        text-align: center;
    }
    
    .social-links {
        justify-content: center;
    }
}
</style>