{{-- Простая пагинация: назад, номер страницы, вперёд. --}}
@if ($paginator->hasPages()) {{-- Показываем блок, только если страниц больше одной. --}}
    <nav class="pagination"> {{-- Навигация по страницам. --}}
        @if ($paginator->onFirstPage()) {{-- Проверяем первую страницу. --}}
            <span class="disabled">← Назад</span> {{-- Неактивная кнопка «Назад». --}}
        @else
            <a href="{{ $paginator->previousPageUrl() }}">← Назад</a> {{-- Ссылка на предыдущую страницу. --}}
        @endif

        <span>Страница {{ $paginator->currentPage() }} из {{ $paginator->lastPage() }}</span> {{-- Номер текущей страницы. --}}

        @if ($paginator->hasMorePages()) {{-- Проверяем, есть ли следующая страница. --}}
            <a href="{{ $paginator->nextPageUrl() }}">Вперёд →</a> {{-- Ссылка на следующую страницу. --}}
        @else
            <span class="disabled">Вперёд →</span> {{-- Неактивная кнопка «Вперёд». --}}
        @endif
    </nav>
@endif
