
<div>

  @if ($status == "list")
     <!-- Section Tarifs (avec l'image) -->
  <section class="pricing-section">
    <div class="container">
      <button wire:click='changeStatus("add")' class="btn btn-success"><span class="btn-icon-left text-success"><i class="fa fa-plus-circle"></i></span>Ajouter</button>
      <div class="row justify-content-center mt-2">
  
        @foreach ($pks as $p)
        <div class="col-lg-4 col-md-6">
          <div class="card card-pricing">
            <div class="card-header" style="background: {{$p->couleur}};color:{{$p->text}};">
              {{$p->nom}}
            </div>
            <div class="card-body text-center">
        <h3 class="pricing-price">{{ number_format($p->annuel, 0, ",", " ") }} XOF/an</h3>
              <p class="pricing-subtext">0 à {{$p->limite}} élèves <br> {{ number_format($p->mensuel, 0, ",", " ") }} / mois facturé annuellement <br> ({{floor($p->annuel/$p->mensuel)}} mois)</p>
              <ul class="pricing-list">
                <li><span class="text-success">✓</span> Toutes les fonctionnalités My-Scool</li>
                <li><span class="text-success">✓</span> Accès parents / profs / administration</li>
                <li><span class="text-success">✓</span> Disponible 24/24 - 7/7</li>
                <li><span class="text-success">✓</span> Maintenance et évolution inclus</li>
              </ul>
              <div class="d-flex justify-content-between">

                <button wire:click='info({{$p->id}})' class="btn btn-pricing" style="background: {{$p->couleur}};color:{{$p->text}};width:40px"><i class="fa fa-edit"></i></button>
                @if($deleteItem == 1)
                  <button type="button" data-toggle="modal"
                  data-target="#modalId" class="btn btn-pricing btn-danger"><i class="fa fa-trash"></i></button>
                  <!-- Button trigger modal -->
                @endif
                
                <!-- Modal -->
                <div
                  class="modal fade"
                  id="modalId"
                  tabindex="-1"
                  role="dialog"
                  aria-labelledby="modalTitleId"
                  aria-hidden="true"
                >
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">
                          Suppression
                        </h5>
                        <button
                          type="button"
                          class="close"
                          data-dismiss="modal"
                          aria-label="Close"
                        >&times;</button>
                      </div>
                      <div class="modal-body">
                        Êtes-vous sûr de vouloir supprimer?
                      </div>
                      <div class="modal-footer">
                        <button
                          type="button"
                          class="btn btn-success"
                          data-dismiss="modal"
                        >
                          Non
                        </button>
                        <button type="button" wire:click='delete({{$p->id}})'  class="btn btn-danger">Oui</button>
                      </div>
                    </div>
                  </div>
                </div>
                
                
              </div>
            </div>
          </div>
        </div>
        @endforeach
        <!-- Large Plan -->
        
  
      </div>
    </div>
  </section>
  @else
     @include('livewire.pack.add') 
  @endif
   
</div>

@section('css')
<style>
    /* Custom styles for the pricing section */
    .pricing-section {
        background-color: #f5f5f5;
        padding: 5px 0;
      }
      .card-pricing {
        border: none;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        margin-bottom: 30px;
      }
      .card-pricing .card-header {
        font-size: 1.5rem;
        font-weight: bold;
        border-radius: 10px 10px 0 0;
        text-align: center;
        justify-content: center;
      }
      .pricing-price {
        font-size: 1.5rem;
        font-weight: bold;
      }
      .pricing-subtext {
        font-size: 0.9rem;
        color: #666;
      }
      .pricing-list {
        list-style: none;
        padding: 0;
        margin: 20px 0;
      }
      .pricing-list li {
        font-size: 1rem;
        margin-bottom: 10px;
      }
      .btn-pricing {
        border-radius: 30px;
        font-size: 1.1rem;
      }
  </style>
@endsection

@section('script')
    <script>
      window.addEventListener('addSuccessful', event =>{
        iziToast.success({
        title: 'Pack',
        message: 'Ajout avec succes',
        position: 'topRight'
        });
    });
    window.addEventListener('updateSuccessful', event =>{
        iziToast.success({
        title: 'Pack',
        message: 'Mis à jour avec succes',
        position: 'topRight'
        });
    });
    window.addEventListener('deletePack', event =>{
        iziToast.success({
        title: 'Pack',
        message: 'Supprimé avec succes',
        position: 'topRight'
        });
        $('#modalId').modal('hide');
    });
    </script>
@endsection