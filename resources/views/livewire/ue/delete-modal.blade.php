<div class="modal fade" id="deleteModal{{$ue->id}}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title">
                    <i class="fa fa-trash mr-2"></i>Confirmation de suppression
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fa fa-exclamation-triangle fa-3x text-warning"></i>
                </div>
                <p>Êtes-vous sûr de vouloir supprimer l'unité d'enseignement :</p>
                <div class="alert alert-warning">
                    <strong>{{ $ue->nom }}</strong>
                </div>
                <p class="mb-0">Cette action entraînera également la suppression de :</p>
                <ul class="text-danger mt-2">
                    <li>Toutes les matières associées ({{ $ue->matieres->count() }} matière(s))</li>
                    <li>Tous les cours liés à ces matières</li>
                    <li>Toutes les notes associées</li>
                </ul>
                <div class="alert alert-danger mt-3">
                    <i class="fa fa-exclamation-circle mr-2"></i>
                    Cette action est irréversible !
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times mr-2"></i>Annuler
                </button>
                <button type="button" 
                    wire:click="supprimer({{$ue->id}})" 
                    class="btn btn-danger">
                    <i class="fa fa-trash mr-2"></i>Confirmer la suppression
                </button>
            </div>
        </div>
    </div>
</div>