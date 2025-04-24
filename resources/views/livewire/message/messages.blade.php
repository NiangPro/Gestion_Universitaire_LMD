<div class="container-fluid">
    <link rel="stylesheet" href="{{ asset('css/message.css') }}">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-body row">
                    <div class="col-md-3 px-0 mb-5">
                        <div class="p-0">
                            <a href="javascript:void()" wire:click='changeType("list")' class="list-group-item @if($type == "list") active @endif"><i
                                class="fa fa-inbox font-18 align-middle mr-2"></i> Boîte de reception @if(count(Auth()->user()->notRead()) > 0) <span
                                class="badge badge-danger badge-sm float-right">{{count(Auth()->user()->notRead())}}</span>@endif </a>
                        </div>
                        <div class="mail-list mt-4">
                            <a href="javascript:void()" wire:click='changeType("add")' class="list-group-item  @if($type == "add") active @endif"><i class="fa fa-plus-square font-18 align-middle mr-2"></i>Nouveau message</a> 
                            <a href="javascript:void()" wire:click='changeType("sent")' class="list-group-item  @if($type == "sent") active @endif"><i class="fa fa-paper-plane font-18 align-middle mr-2"></i>Messages envoyés</a> 
                            <a href="javascript:void()"  wire:click='changeType("important")' class="list-group-item   @if($type == "important") active @endif""><i
                                    class="fa fa-star font-18 align-middle mr-2"></i>Important 
                            </a>
                        </div>
                    </div>
                    @if($type == "add")
                        @include('livewire.message.add')
                    @elseif($type == "read")
                        @include('livewire.message.read')
                    @elseif($type == "sent")
                        @include('livewire.message.sent')
                    @elseif($type == "important")
                        @include('livewire.message.important')
                    @else 
                        @include('livewire.message.list')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('sentMsg', event =>{
        iziToast.success({
        title: 'Message',
        message: 'a été envoyé',
        position: 'topRight'
        });
    });

</script>