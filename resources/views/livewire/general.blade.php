<div class="container py-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Paramètres du Campus</h4>
        </div>
        <div class="card-body">
            <!-- Tampon -->
            <div class="form-group mb-4">
                <h5 class="card-title">Tampon du Campus @if($campus->tampon)<small class="text-muted">(Image actuelle)</small>@endif</h5>
                <div class="row align-items-center">
                    @if($campus->tampon)
                        <div class="col-md-3 mb-3">
                            <div class="border p-2 text-center">
                                <img src="{{ Storage::url($campus->tampon) }}" alt="Tampon" class="img-fluid" style="max-height: 150px;">
                            </div>
                        </div>
                    @endif
                    <div class="col">
                        <div class="custom-file">
                            <input type="file" wire:model="tampon" accept="image/*" class="custom-file-input" id="tamponFile">
                            <label class="custom-file-label" for="tamponFile">Choisir un fichier</label>
                        </div>
                        @error('tampon') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Signature -->
            <div class="form-group mb-4">
                <h5 class="card-title">Signature du Responsable @if($campus->signature)<small class="text-muted">(Image actuelle)</small>@endif</h5>
                <div class="row align-items-center">
                    @if($campus->signature)
                        <div class="col-md-3 mb-3">
                            <div class="border p-2 text-center">
                                <img src="{{ Storage::url($campus->signature) }}" alt="Signature" class="img-fluid" style="max-height: 150px;">
                            </div>
                        </div>
                    @endif
                    <div class="col">
                        <div class="custom-file">
                            <input type="file" wire:model="signature" accept="image/*" class="custom-file-input" id="signatureFile">
                            <label class="custom-file-label" for="signatureFile">Choisir un fichier</label>
                        </div>
                        @error('signature') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Responsable -->
            <div class="form-group">
                <h5 class="card-title">Responsable du Campus <small class="text-muted">(Actuel : {{ $campus->responsable }})</small></h5>
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" wire:model="responsable" class="form-control" placeholder="Nom du responsable">
                        @error('responsable') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        <button wire:click="updateResponsable" class="btn btn-primary mt-3">
                            <i class="fas fa-save mr-1"></i> Mettre à jour le responsable
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@if($processing)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mb-0">Traitement de l'image en cours...</p>
                </div>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
    document.querySelectorAll('.custom-file-input').forEach(input => {
        input.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            const label = e.target.nextElementSibling;
            if (fileName) {
                label.textContent = fileName;
            }
        });
    });

    Livewire.on('notify', event => {
        iziToast.show({
            message: event.detail.message,
            type: event.detail.type || 'success',
            position: 'topRight'
        });
    });
</script>
@endpush
</div>
