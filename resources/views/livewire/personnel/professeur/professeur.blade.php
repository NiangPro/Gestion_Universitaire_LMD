<div>
    @if ($status == "list")
    <section class="pricing-section">
        <div class="container">
            <button wire:click='changeStatus("add")' class="btn btn-success"><span class="btn-icon-left text-success"><i class="fa fa-plus-circle"></i></span>Ajouter</button>
            <div class="card mt-2">
                <div class="card-header">
                    <h4 class="card-title">Liste des professeurs</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example2" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Office</th>
                                    <th>Age</th>
                                    <th>Start date</th>
                                    <th>Salary</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Tiger Nixon</td>
                                    <td>System Architect</td>
                                    <td>Edinburgh</td>
                                    <td>61</td>
                                    <td>2011/04/25</td>
                                    <td>$320,800</td>
                                </tr>
                                <tr>
                                    <td>Garrett Winters</td>
                                    <td>Accountant</td>
                                    <td>Tokyo</td>
                                    <td>63</td>
                                    <td>2011/07/25</td>
                                    <td>$170,750</td>
                                </tr>
                                <tr>
                                    <td>Ashton Cox</td>
                                    <td>Junior Technical Author</td>
                                    <td>San Francisco</td>
                                    <td>66</td>
                                    <td>2009/01/12</td>
                                    <td>$86,000</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Office</th>
                                    <th>Age</th>
                                    <th>Start date</th>
                                    <th>Salary</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @else       
        @include('livewire.personnel.professeur.add') 
    @endif
</div>


@section('script')
    <script>
      window.addEventListener('addSuccessful', event =>{
        iziToast.success({
        title: 'Professeur',
        message: 'Ajout avec succes',
        position: 'topRight'
        });
    });
    window.addEventListener('updateSuccessful', event =>{
        iziToast.success({
        title: 'Professeur',
        message: 'Mis à jour avec succes',
        position: 'topRight'
        });
    });
    </script>
@endsection