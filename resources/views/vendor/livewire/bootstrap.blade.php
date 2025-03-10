@if ($paginator->hasPages())
<nav>
    <ul class="pagination">
        {{-- Bouton Previous --}}
        @if ($paginator->onFirstPage())
        <li class="page-item disabled">
            <span class="page-link">«</span>
        </li>
        @else
        <li class="page-item">
            <button type="button" class="page-link" wire:click="previousPage" wire:loading.attr="disabled">
                «
            </button>
        </li>
        @endif

        {{-- Numéros de page --}}
        @foreach ($elements as $element)
        @if (is_string($element))
        <li class="page-item disabled">
            <span class="page-link">{{ $element }}</span>
        </li>
        @endif

        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <li class="page-item active">
            <span class="page-link">{{ $page }}</span>
        </li>
        @else
        <li class="page-item">
            <button type="button" class="page-link" wire:click="gotoPage({{ $page }})">
                {{ $page }}
            </button>
        </li>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- Bouton Next --}}
        @if ($paginator->hasMorePages())
        <li class="page-item">
            <button type="button" class="page-link" wire:click="nextPage" wire:loading.attr="disabled">
                »
            </button>
        </li>
        @else
        <li class="page-item disabled">
            <span class="page-link">»</span>
        </li>
        @endif
    </ul>
</nav>
@endif