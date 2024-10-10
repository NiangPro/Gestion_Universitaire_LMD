<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="email-left-box px-0 mb-5">
                        <div class="p-0">
                            <a href="javascript:void()" wire:click='changeType("list")' class="list-group-item @if($type == "list") active @endif"><i
                                class="fa fa-inbox font-18 align-middle mr-2"></i> Boîte de reception <span
                                class="badge badge-primary badge-sm float-right">198</span> </a>
                        </div>
                        <div class="mail-list mt-4">
                            <a href="javascript:void()" wire:click='changeType("add")' class="list-group-item  @if($type == "add") active @endif"><i class="fa fa-plus-square font-18 align-middle mr-2"></i>Nouveau message</a> 
                            <a href="javascript:void()" class="list-group-item"><i class="fa fa-paper-plane font-18 align-middle mr-2"></i>Messages envoyés</a> 
                                    <a href="javascript:void()" class="list-group-item"><i
                                    class="fa fa-star-o font-18 align-middle mr-2"></i>Important <span
                                    class="badge badge-danger text-white badge-sm float-right">47</span>
                            </a>
                        </div>
                    </div>
                    @if($type == "add")
                        @include('livewire.message.add')
                    @else 
                        @include('livewire.message.list')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
