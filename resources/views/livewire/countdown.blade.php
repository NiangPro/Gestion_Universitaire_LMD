<p>
    Temps restant de votre abonnement <span class="badge badge-pill badge-light"  id="countdown"></span>
</p>
@section('js')
    <script>
    document.addEventListener('livewire:load', function () {
        // Récupérer la date de fermeture depuis Livewire
        const dateFermeture = @json($dateFermeture);

        // Convertir en millisecondes
        const fermetureDate = new Date(dateFermeture).getTime();

        // Mettre à jour le compte à rebours toutes les secondes
        const interval = setInterval(function () {
            const now = new Date().getTime();
            const distance = fermetureDate - now;

            // Calcul des jours, heures, minutes et secondes restants
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Afficher le compte à rebours
            document.getElementById('countdown').innerHTML =
                `${days}j ${hours}h ${minutes}m ${seconds}s`;

            // Si le compte à rebours est terminé
            if (distance < 0) {
                clearInterval(interval);
                document.getElementById('countdown').innerHTML = "Campus fermé.";
            }
        }, 1000);
    });
</script>
@endsection

