@props(['text'])

<article class="card">
    <a class="card__category" href="{{ route('categories.show', $text->category) }}">
        {{ $text->category->name }}
    </a>

    <h2 class="card__title">
        <a href="{{ route('articles.show', $text) }}">{{ $text->heading }}</a>
    </h2>

    <p class="card__excerpt">{{ $text->excerpt }}</p>

    <footer class="card__meta">
        <span class="card__author">Автор: {{ $text->author->login }}</span>
        <time class="card__date" datetime="{{ $text->created_at->toIso8601String() }}">
            {{ $text->created_at->translatedFormat('d M Y') }}
        </time>
    </footer>
</article>
