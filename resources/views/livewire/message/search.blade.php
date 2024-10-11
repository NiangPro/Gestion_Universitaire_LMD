@if(count($users) > 0)

<div class="" id="searchuser" style="border-radius:10px;padding:10px;box-shadow:1px 1px 5px black;
            position: absolute;top:50px;z-index:20;background:white;">
            <ul>
                @foreach($users as $u)
                <li class="mb-2" style="cursor: pointer;" wire:click='getReceiver({{$u->id}})'>
                    <img src="{{asset("storage/images/".$u->image)}}" width="50" height="40" alt="">
                    {{$u->prenom}} {{$u->nom}} - {{$u->email}}
                </li>
                @endforeach
            </ul>
</div>
@endif