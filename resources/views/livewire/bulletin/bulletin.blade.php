<div class="container-fluid">
    <!-- Formulaire de sélection -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-graduation-cap mr-2"></i>Bulletin de notes</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label class="font-weight-bold">Année Académique</label>
                        <select wire:model.live="selectedYear" class="form-control">
                            <option value="">Sélectionner une année académique</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}">
                                    {{ date('Y', strtotime($year->debut)) }} - {{ date('Y', strtotime($year->fin)) }}
                                    @if($year->encours) (En cours) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if($selectedYear)
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label class="font-weight-bold">Classe</label>
                        <select wire:model.live="selectedClasse" class="form-control">
                            <option value="">Sélectionner une classe</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}">{{ $classe->nom }} - {{ $classe->filiere->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

                @if($selectedClasse)
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label class="font-weight-bold">Étudiant</label>
                        <select wire:model.live="selectedStudent" class="form-control">
                            <option value="">Sélectionner un étudiant</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->prenom }} {{ $student->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Affichage du bulletin -->
    @if($selectedStudent && $bulletin)
    @include("livewire.bulletin.info")
    @endif
</div>
