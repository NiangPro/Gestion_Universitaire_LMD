<div class="email-right-box ml-0 ml-sm-4 ml-sm-0">
    <div class="email-list">
        @foreach($sentMessages as $m)
        <div class="message">
            <div class="d-flex">
                <div class="d-flex message-single">
                    <div class="ml-2">
                        <button class="border-0 bg-transparent align-middle p-0"><i
                                class="fa fa-star" aria-hidden="true"></i></button>
                    </div>
                    <div class="user">
                        <i class="fa fa-hand-o-right"></i>
                    </div>
                </div>
                <a href="javascript:void()" wire:click='readMessage({{$m->id}})' class="col-mail unread col-mail-2 d-flex justify-content-between">
                    <div class=" ml-2 text-primary"> à {{ $m->receiver->prenom}} {{ $m->sender->nom}}</div>
                    <div class="">{{ ucfirst(substr($m->content, 0, 30))}}...</div>
                    <div class="">{{ date("d/m/Y à h:i", strtotime($m->created_at))}}</div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    <!-- panel -->
    <div class="row mt-4 m-4 mx-sm-4">
        <div class="col-7">
            <div class="text-left">1 - 20 of 568</div>
        </div>
        <div class="col-5">
            <div class="btn-group float-right">
                <button class="btn btn-dark" type="button"><i
                        class="fa fa-angle-left"></i>
                </button>
                <button class="btn btn-dark" type="button"><i
                        class="fa fa-angle-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>