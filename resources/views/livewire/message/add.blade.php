<div class="email-right-box ml-0 ml-sm-4 ml-sm-0">
    <div class="compose-content">
        <form action="#">
            <div class="form-group">
                <input type="text" wire:model.live='receiver_id' class="form-control bg-transparent" placeholder=" à:">
            </div>
            <div class="form-group">
                <input type="text" class="form-control bg-transparent" placeholder=" Titre:">
            </div>
            <div class="form-group">
                <textarea id="email-compose-editor" class="textarea_editor form-control bg-transparent" rows="10" placeholder="Entrer le text ..."></textarea>
            </div>
        </form>
        <h5 class="mb-4"><i class="fa fa-paperclip"></i> Attatchment</h5>
        <form action="#" class="d-flex flex-column align-items-center justify-content-center">
            <div class="fallback w-100">
                <input type="file" class="dropify" data-default-file="" />
            </div>
        </form>
    </div>
    <div class="text-left mt-4 mb-5">
        <button class="btn btn-primary btn-sl-sm mr-3" type="button"><span
                class="mr-2"><i class="fa fa-paper-plane"></i></span> Send</button>
        <button class="btn btn-dark btn-sl-sm" type="button"><span class="mr-2"><i
                    class="fa fa-times" aria-hidden="true"></i></span> Discard</button>
    </div>
</div>