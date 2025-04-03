                <form wire:submit.prevent="sauvegarderNote">
                    <div class="row mb-3">
                        <!-- Classe -->
                        <div class="col-md-6 mb-3">
                            <select wire:model="classe_id" class="form-control" wire:change="loadUE" @if($isEditing) disabled @endif>
                                <option value="">Sélectionner une classe</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}">
                                        {{ $classe->nom }} - {{ strtolower($classe->filiere->nom) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- UE -->
                        <div class="col-md-6 mb-3">
                            <select wire:model="ue_id" class="form-control" wire:change="loadMatieres" @if($isEditing) disabled @endif>
                                <option value="">Sélectionner une UE</option>
                                @foreach($uniteEnseignements as $ue)
                                    <option value="{{ $ue->id }}">{{ $ue->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Matière -->
                        <div class="col-md-6 mb-3">
                            <select wire:model="matiere_id" class="form-control" @if($isEditing) disabled @endif>
                                <option value="">Sélectionner une matière</option>
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Note -->
                        <div class="col-md-6 mb-3">
                            <input type="number" class="form-control" 
                                   wire:model="note" 
                                   placeholder="Note /20"
                                                   min="0" 
                                                   max="20" 
                                                   step="0.25">
                        </div>

                        <!-- Type d'évaluation -->
                        <div class="col-md-6 mb-3">
                            <select wire:model="type_evaluation" class="form-control">
                                <option value="">Type d'évaluation</option>
                                <option value="CC">Contrôle Continu</option>
                                <option value="TP">Travaux Pratiques</option>
                                                <option value="Examen">Examen</option>
                                            </select>
                        </div>

                        <!-- Semestre -->
                        <div class="col-md-6 mb-3">
                            <select wire:model="semestre_id" class="form-control">
                                <option value="">Sélectionner le semestre</option>
                                @foreach($semestres as $semestre)
                                    <option value="{{ $semestre->id }}">{{ $semestre->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Observation -->
                        <div class="col-md-12">
                            <textarea class="form-control" 
                                      wire:model="observation" 
                                      placeholder="Observation"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Fermer</button>
                        <button type="submit" class="btn btn-primary">
                            Modifier
                        </button>
                    </div>
                </form>