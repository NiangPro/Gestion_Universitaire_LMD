<div class="col-md-8">
    <h4><i class="fa fa-file-text-o"></i> {{ $titre }}</h4>

    <div class="email-list">
        @foreach($receivedMessages as $m)
        <div class="message mb-1"  @if($m->is_read == 0) style="background: lightgrey" @endif>
            <div class="d-flex">
                <div class="d-flex message-single">
                    <div class="">
                        <button wire:click='toggleFavorite({{$m->id}})' class="border-0 bg-transparent align-middle p-0"><i
                                class="fa fa-star @if($m->isFavoriteForUser(Auth()->id())) text-warning @endif" aria-hidden="true"></i></button>
                    </div>
                    <div class="user">
                        <i class="fa fa-hand-o-right"></i>
                    </div>
                </div>
                <a href="javascript:void()" wire:click='readMessage({{$m->id}})' class="col-mail unread col-mail-2 d-flex justify-content-between">
                    <div class=" ml-2"> de {{ $m->sender->prenom}} {{ $m->sender->nom}}</div>
                    <div class="d-none d-md-block">{{ ucfirst(substr($m->content, 0, 30))}}...</div>
                    <div class="d-none d-md-block">{{ date("d/m/Y à H:i", strtotime($m->created_at))}}</div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    <!-- panel -->
    <div class="d-flex justify-content-center mt-4 m-4 mx-sm-4">
        {{$receivedMessages->links()}}
    </div>
</div>