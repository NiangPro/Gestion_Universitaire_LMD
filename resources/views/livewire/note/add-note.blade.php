<div class="modal show d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ajouter une note</h5>
                            <button type="button" class="close" wire:click="$set('showModal', false)">
                                <span>&times;</span>
                            </button>
                        </div>
                        <form wire:submit.prevent="sauvegarderNote">
                            <div class="modal-body">
                                <div class="row">
                                    <!-- Sélection de la classe -->
                                    <div class="form-group col-md-6">
                                        <label>Classe</label>
                                        <select wire:model="classe_id" class="form-control" wire:change="loadEtudiants">
                                            <option value="">Sélectionner une classe</option>
                                            @foreach($classes as $classe)
                                                <option value="{{ $classe->id }}">
                                                    {{ $classe->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('classe_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Sélection de l'étudiant (apparaît après sélection de la classe) -->
                                    @if($classe_id)
                                    <div class="form-group col-md-6">
                                        <label>Étudiant</label>
                                        <select wire:model="etudiant_id" class="form-control">
                                            <option value="">Sélectionner un étudiant</option>
                                            @foreach($etudiants as $etudiant)
                                                <option value="{{ $etudiant->id }}">
                                                    {{ $etudiant->nom }} {{ $etudiant->prenom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('etudiant_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    @endif

                                    <div class="form-group col-md-6">
                                        <label>Note (/20)</label>
                                        <input type="number" class="form-control" wire:model="note" min="0" max="20" step="0.25">
                                        @error('note') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Coefficient</label>
                                        <select  wire:model="coefficient" class="form-control">
                                            <option value="">Sélectionner un coefficient</option>
                                            @foreach($coefficients as $c)
                                                <option value="{{ $c->id }}">
                                                    {{ $c->valeur }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('coefficient') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Type d'évaluation</label>
                                        <select wire:model="type_evaluation" class="form-control">
                                            <option value="">Sélectionner le type</option>
                                            <option value="CC">Contrôle Continu</option>
                                            <option value="TP">Travaux Pratiques</option>
                                            <option value="Examen">Examen</option>
                                        </select>
                                        @error('type_evaluation') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Date d'évaluation</label>
                                        <input type="date" class="form-control" wire:model="date_evaluation">
                                        @error('date_evaluation') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Semestre</label>
                                        <select wire:model="semestre_id" class="form-control">
                                            <option value="">Sélectionner le semestre</option>
                                            @foreach($semestres as $semestre)
                                                <option value="{{ $semestre->id }}">
                                                    {{ $semestre->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('semestre_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Observation</label>
                                        <textarea class="form-control" wire:model="observation"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Fermer</button>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>