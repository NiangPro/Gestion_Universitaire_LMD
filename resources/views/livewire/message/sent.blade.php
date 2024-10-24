<div class="col-md-8">
    <h4><i class="fa fa-file-text-o"></i> {{ $titre }}</h4>
    @if(count($sentMessages) > 0)
        <div class="email-list">
            @foreach($sentMessages as $m)
            <div class="message">
                <div class="d-flex">
                    <div class="d-flex message-single">
                        <div class="ml-2">
                            <button  wire:click='toggleFavorite({{$m->id}})' class="border-0 bg-transparent align-middle p-0"><i
                                    class="fa fa-star  @if($m->isFavoriteForUser(Auth()->id())) text-warning @endif" aria-hidden="true"></i></button>
                        </div>
                        <div class="user">
                            <i class="fa fa-hand-o-right"></i>
                        </div>
                    </div>
                    <a href="javascript:void()" wire:click='readMessage({{$m->id}})' class="col-mail unread col-mail-2 d-flex justify-content-between">
                        <div class="text-primary"> à {{ $m->receiver->prenom}} {{ $m->receiver->nom}}</div>
                        <div class="d-none d-md-block">{{ ucfirst(substr($m->content, 0, 30))}}...</div>
                        <div class="d-none d-md-block">{{ date("H\h:i\m\\n", strtotime($m->created_at))}}</div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <!-- panel -->
        <div class="d-flex justify-content-center mt-4 m-4 mx-sm-4">
            {{$sentMessages->links()}}
        </div>
    @else 
        <h3 class="text-primary text-center">Aucun message pour le moment</h3>
    @endif
</div>