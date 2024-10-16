<div class="col-md-8">
    <h4><i class="fa fa-file-text-o"></i> {{ $titre }}</h4>

    <form wire:submit.prevent="sendMessage">
        <div class="compose-content">
            <div class="form-group">
                <input type="email" required wire:model.live="receiver_id" class="form-control bg-transparent" placeholder="À :">
                @error('receiver_id') <div class="text-danger">{{ $message }}</div> @enderror

            </div>
            @if(count($users) > 0)
                @include('livewire.message.search')
            @endif
            <div class="form-group">
                <input type="text" required wire:model="title" class="form-control bg-transparent" placeholder="Titre :">
                @error('title') <div class="text-danger">{{ $message }}</div> @enderror

            </div>
            <div class="form-group">
                <textarea required wire:model="content" class="summernote form-control" rows="10" placeholder="Entrer le texte ..."></textarea>
                @error('content') <div class="text-danger">{{ $message }}</div> @enderror

            </div>
            <h5 class="mb-4"><i class="fa fa-paperclip"></i> Image</h5>
            <div class="fallback w-100">
                <input type="file" wire:model="image" class="dropify" data-default-file="" />
                @error('image') <div class="text-danger">{{ $message }}</div> @enderror

            </div>
        </div>
        <div class="text-left mt-4 mb-5">
            <button class="btn btn-primary btn-sl-sm mr-3" type="submit">
                <span class="mr-2"><i class="fa fa-paper-plane"></i></span> Envoyer
            </button>
        </div>
    
    </form>
    
</div>