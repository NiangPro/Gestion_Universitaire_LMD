<div class="col-md-8">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0"><i class="fa fa-file-text-o"></i> {{ $titre }}</h4>
        <div class="message-actions text-right">
            <button class="btn btn-outline-primary btn-sm"><i class="fa fa-refresh"></i> Actualiser</button>
        </div>
    </div>
    @if(count($receivedMessages) > 0)
    <div class="email-list">
        @foreach($receivedMessages as $m)
        <div class="message @if($m->is_read == 0) unread @endif">
            <div class="d-flex align-items-center">
                <div class="message-single">
                    <button wire:click='toggleFavorite({{$m->id}})' class="star-btn border-0 bg-transparent p-0 @if($m->isFavoriteForUser(Auth()->id())) active @endif">
                        <i class="fa fa-star" aria-hidden="true"></i>
                    </button>
                </div>
                <a href="javascript:void()" wire:click='readMessage({{$m->id}})' class="message-content d-flex align-items-center w-100">
                    <div class="message-header w-100">
                        <div class="sender-info">
                            <span class="font-weight-bold">{{ $m->sender->prenom}} {{ $m->sender->nom}}</span>
                        </div>
                        <div class="message-preview flex-grow-1 px-3">
                            {{ ucfirst(substr($m->content, 0, 30))}}...
                        </div>
                        <div class="message-time">
                            {{ date("H\h:i", strtotime($m->created_at))}}
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    <!-- panel -->
    <div class="d-flex justify-content-center mt-4 m-4 mx-sm-4">
        {{$receivedMessages->links()}}
    </div>
    @else 
        <h3 class="text-primary text-center">Aucun message pour le moment</h3>
    @endif
</div>