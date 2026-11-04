@if ($paginator->hasPages())
    <nav class="pagination" role="navigation" aria-label="Навигация по страницам">
        @if ($paginator->onFirstPage())
            <span class="pagination__link is-disabled" aria-disabled="true">← Назад</span>
        @else
            <a class="pagination__link" href="{{ $paginator->previousPageUrl() }}" rel="prev">← Назад</a>
        @endif

        <span class="pagination__info">
            Страница {{ $paginator->currentPage() }} из {{ $paginator->lastPage() }}
        </span>

        @if ($paginator->hasMorePages())
            <a class="pagination__link" href="{{ $paginator->nextPageUrl() }}" rel="next">Вперёд →</a>
        @else
            <span class="pagination__link is-disabled" aria-disabled="true">Вперёд →</span>
        @endif
    </nav>
@endif
