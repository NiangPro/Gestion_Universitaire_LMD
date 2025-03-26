<div>
    <button 
        type="button"
        wire:click="switching"
        class="btn btn-outline-primary theme-switch-btn"
        id="themeSwitchBtn"
    >
        @if($darkMode)
            <i class="fas fa-sun"></i>
        @else
            <i class="fas fa-moon"></i>
        @endif
    </button>
</div> 