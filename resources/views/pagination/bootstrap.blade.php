@if ($paginator->hasPages())
<nav>
    <div class="pagination-wrapper">

        <div class="pagination-info">
            {{ __('gallery::gallery.pagination.showing') }}
            {{ $paginator->firstItem() }}
            -
            {{ $paginator->lastItem() }}
            /
            {{ $paginator->total() }}
        </div>

        <ul class="pagination">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                        &lsaquo;
                    </a>
                </li>
            @endif


            {{-- Pages --}}
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
                                <a class="page-link" href="{{ $url }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif

                    @endforeach
                @endif

            @endforeach


            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}">
                        &rsaquo;
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">&rsaquo;</span>
                </li>
            @endif

        </ul>

    </div>
</nav>
@endif