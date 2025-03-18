<div>
    <!-- Hero Section -->
    <section class="bg-gradient-primary text-white py-5">
        <div class="container mt-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-3">Contactez-nous</h1>
                    <p class="lead opacity-75">Notre équipe est là pour répondre à toutes vos questions</p>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('images/contact.png') }}" alt="Contact" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Info Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 hover-translate">
                        <div class="card-body text-center p-4">
                            <div class="contact-icon mb-3">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h4 class="h5 mb-3">Notre Adresse</h4>
                            <p class="text-muted mb-0">
                                Parcelles Assainies Unité 17<br>
                                Face Terminus Dakar Dem Dik<br>
                                Dakar, Sénégal
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 hover-translate">
                        <div class="card-body text-center p-4">
                            <div class="contact-icon mb-3">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h4 class="h5 mb-3">Téléphone</h4>
                            <p class="text-muted mb-0">
                                +225 07 07 07 07 07<br>
                                +225 05 05 05 05 05
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 hover-translate">
                        <div class="card-body text-center p-4">
                            <div class="contact-icon mb-3">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h4 class="h5 mb-3">Email</h4>
                            <p class="text-muted mb-0">
                                contact@schoolmanager.ci<br>
                                support@schoolmanager.ci
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            @if($successMessage)
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ $successMessage }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <form wire:submit.prevent="submitForm">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Nom complet</label>
                                            <input type="text" class="form-control form-control-lg" 
                                                   wire:model="name" placeholder="Votre nom">
                                            @error('name') 
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control form-control-lg" 
                                                   wire:model="email" placeholder="Votre email">
                                            @error('email') 
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Sujet</label>
                                            <input type="text" class="form-control form-control-lg" 
                                                   wire:model="subject" placeholder="Sujet de votre message">
                                            @error('subject') 
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Message</label>
                                            <textarea class="form-control form-control-lg" rows="5" 
                                                      wire:model="message" placeholder="Votre message"></textarea>
                                            @error('message') 
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill">
                                            <span wire:loading.remove>Envoyer le message</span>
                                            <span wire:loading>
                                                <i class="fas fa-spinner fa-spin me-2"></i>
                                                Envoi en cours...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="ratio ratio-21x9">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.802441456495!2d-17.441481776000002!3d14.757499999999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xec172f9c9f6a3c1%3A0x9f1ae2!2sParcelles%20Assainies%2C%20Dakar!5e0!3m2!1sfr!2ssn!4v1647095727021!5m2!1sfr!2ssn" 
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </section>
</div>

@section('css')
<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #479CD5, #FF8A00);
}

.contact-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: linear-gradient(45deg, #479CD5, #FF8A00);
    color: white;
    font-size: 24px;
}

.hover-translate {
    transition: transform 0.3s ease;
}

.hover-translate:hover {
    transform: translateY(-5px);
}

.form-control {
    border: 2px solid #e9ecef;
    padding: 0.75rem 1.25rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #479CD5;
    box-shadow: 0 0 0 0.2rem rgba(41, 55, 240, 0.25);
}

.btn-primary {
    background: linear-gradient(45deg, #479CD5, #FF8A00);
    border: none;
    transition: transform 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .display-4 {
        font-size: 2.5rem;
    }
}
</style>
@endsection