<div class="footer">
    <div class="copyright d-flex justify-content-between">
        <p>Copyright ©2024</p>
        @if(Auth()->user() && !Auth()->user()->estSuperAdmin())
        <livewire:countdown :campusId="Auth()->user()->campus_id" />
        @else
            <p>Conçu par <a href="https://sunucode.com/" target="_blank">Sunucode</a></p>
        @endif
    </div>
</div>