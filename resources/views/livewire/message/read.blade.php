<div class="col-md-8">
    <h4><i class="fa fa-file-text-o"></i> {{ $titre }}</h4>

    <div class="row">
        <div class="col-12">
            <div class="right-box-padding">
                <div class="read-content">
                    <div class="media">
                        <img class="mr-4 rounded-circle" alt="image" width="100" height="50" src="{{asset('storage/images/'.(Auth()->id() == $msg->sender_id ? $msg->receiver->image : $msg->sender->image))}}">
                        <div class="media-body">
                            @if(Auth()->user()->id == $msg->sender_id)
                            <h5 class="text-primary">{{$msg->receiver->prenom}} {{$msg->receiver->nom}}</h5>
                            <p class="mb-0">{{$msg->receiver->email}}</p>
                            @else
                            <h5 class="text-primary">{{$msg->sender->prenom}} {{$msg->sender->nom}}</h5>
                            <p class="mb-0">{{$msg->sender->email}}</p>
                            @endif
                        </div>
                        <a href="javascript:void()" class="text-muted "><i
                                class="fa fa-reply" wire:click='changeType("{{$back}}")'></i> </a>
                        <a href="javascript:void()" class="text-muted ml-3"><i
                                class="fa fa-trash"></i></a>
                    </div>
                    <hr>
                    <div class="media mb-4 mt-5">
                        <div class="media-body"><span class="pull-right">Le {{ date("d/m/Y à h:i", strtotime($msg->created_at))}}</span>
                            <h5 class="my-1 text-primary">{{$msg->titre}}</h5>
                        </div>
                    </div>
                    <div class="read-content-body">
                        {!! nl2br(e($msg->content)) !!}
                        <hr>
                        @if($msg->image)
                        <a href="{{asset('storage/images/'.$msg->image)}}" target="_blank" rel="noopener noreferrer">
                            <img width="100%" src="{{asset('storage/images/'.$msg->image)}}" alt="">
                        </a>
                        @endif
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>