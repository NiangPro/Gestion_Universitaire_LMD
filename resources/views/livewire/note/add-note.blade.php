<form wire:submit.prevent="sauvegarderNote">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Note (/20)</th>
                                    <th>Coefficient</th>
                                    <th>Type</th>
                                    <th>Observation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($etudiants as $etudiant)
                                    <tr>
                                        <td>{{ $etudiant->nom }} {{ $etudiant->prenom }}</td>
                                        <td>
                                            <input type="number" 
                                                   class="form-control form-control-sm" 
                                                   wire:model="notes.{{ $etudiant->id }}.note"
                                                   min="0" 
                                                   max="20" 
                                                   step="0.25">
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm" 
                                                    wire:model="notes.{{ $etudiant->id }}.coefficient_id">
                                                <option value="">Coefficient</option>
                                                @foreach($coefficients as $coef)
                                                    <option value="{{ $coef->id }}">{{ $coef->valeur }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm" 
                                                    wire:model="notes.{{ $etudiant->id }}.type_evaluation">
                                                <option value="CC">CC</option>
                                                <option value="TP">TP</option>
                                                <option value="Examen">Examen</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   class="form-control form-control-sm" 
                                                   wire:model="notes.{{ $etudiant->id }}.observation">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-right mt-3">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer les notes</button>
                    </div>
                </form>