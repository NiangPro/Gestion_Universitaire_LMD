<div>
    <div class="row">
        {{-- partie gauche  --}}
        <div class="col-xl-6 col-xxl-6 col-lg-6 col-md-6 col-sm-12">
            <div class="row">
               @include("livewire.configuration.departement")

                @include("livewire.configuration.classe")

                @include("livewire.configuration.salle")
                
            </div>
        </div>
        {{-- partie droite  --}}
        <div class="col-xl-6 col-xxl-6 col-lg-6 col-md-6 col-sm-12">
            <div class="row">
                @include("livewire.configuration.filiere")
                @include("livewire.configuration.ue")
                @include("livewire.configuration.semestre")
            </div>
        </div>


    </div>
</div>

@section('css')
<style>
    .list-config {
        max-height: 250px;
        overflow-y: scroll;
    }

    .list-title {
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        font-weight: bold;
        text-transform: uppercase;
    }

    .list-config li {
        cursor: pointer;
        color: inherit;  /* Modifié pour supporter le mode sombre */
        line-height: 30px;
        font-size: 14px;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    }

    .list-config li .subtitle {
        color: #9a9eb4;
        font-size: 12px;
        text-indent: 20px;
    }

    .list-config li:hover {
        background: rgba(0, 0, 0, 0.1);  /* Modifié pour supporter le mode sombre */
        border-left: 5px solid currentColor;  /* Utilise la couleur du texte actuelle */
    }

    .list-config li:hover>span+.item-actions {
        visibility: visible;
    }

    .item-actions {
        visibility: hidden;
    }

    /* Styles pour le mode sombre */
    [data-theme="dark"] .accordion__header,
    body.dark-mode .accordion__header {
        background-color: #2d2d2d !important;
        border-color: #404040 !important;
    }

    [data-theme="dark"] .accordion__body,
    body.dark-mode .accordion__body {
        background-color: #2d2d2d !important;
    }

    [data-theme="dark"] .card,
    body.dark-mode .card {
        background-color: #2d2d2d !important;
        border-color: #404040 !important;
    }

    [data-theme="dark"] .list-config li:hover,
    body.dark-mode .list-config li:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    [data-theme="dark"] .list-config li .subtitle,
    body.dark-mode .list-config li .subtitle {
        color: #808080;
    }

    /* Style pour l'indicateur */
    .accordion__header {
        position: relative;
        cursor: pointer;
    }

    .accordion__header--indicator {
        position: absolute;
        right: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
    }

    .accordion__header.collapsed .accordion__header--indicator:before {
        content: "\f067"; /* fa-plus */
    }

    .accordion__header:not(.collapsed) .accordion__header--indicator:before {
        content: "\f068"; /* fa-minus */
    }
</style>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('.accordion__header').on('click', function() {
            const target = $(this).data('bs-target');
            $(this).toggleClass('collapsed');
            
            // Change l'icône
            const indicator = $(this).find('.accordion__header--indicator');
            if ($(this).hasClass('collapsed')) {
                indicator.removeClass('fa-minus').addClass('fa-plus');
            } else {
                indicator.removeClass('fa-plus').addClass('fa-minus');
            }
            
            $(target).collapse('toggle');
        });

        // État initial
        $('.accordion__header').each(function() {
            const target = $(this).data('bs-target');
            const indicator = $(this).find('.accordion__header--indicator');
            if (!$(target).hasClass('show')) {
                $(this).addClass('collapsed');
                indicator.removeClass('fa-minus').addClass('fa-plus');
            }
        });

        // Réinitialiser après changement de thème
        document.addEventListener('livewire:init', () => {
            Livewire.on('theme-changed', () => {
                $('.accordion__header').each(function() {
                    const target = $(this).data('bs-target');
                    const indicator = $(this).find('.accordion__header--indicator');
                    if (!$(target).hasClass('show')) {
                        $(this).addClass('collapsed');
                        indicator.removeClass('fa-minus').addClass('fa-plus');
                    } else {
                        $(this).removeClass('collapsed');
                        indicator.removeClass('fa-plus').addClass('fa-minus');
                    }
                });
            });
        });

        // Gestion des notifications
    window.addEventListener('added', event => {
        iziToast.success({
            title: 'Configurations',
            message: 'Ajout avec succes',
            position: 'topRight'
        });
    });

    window.addEventListener('updated', event => {
        iziToast.success({
            title: 'Configurations',
            message: 'Mis à jour avec succes',
            position: 'topRight'
        });
    });

    window.addEventListener('deleted', event => {
        iziToast.success({
            title: 'Configurations',
            message: 'Suppression avec succes',
            position: 'topRight'
        });
        $('.modalId').modal('hide');
        });
    });
</script>
@endsection