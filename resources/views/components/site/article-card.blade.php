{{-- Карточка одной статьи: категория, заголовок, анонс, автор и дата. --}}
{{-- Используется на главной странице и на странице категории. --}}
@props(['text']) {{-- Компонент получает одну статью. --}}

<article class="card"> {{-- Карточка статьи. --}}
    <a class="category" href="{{ route('categories.show', $text->category) }}">{{ $text->category->name }}</a> {{-- Ссылка на категорию. --}}
    <h2><a href="{{ route('articles.show', $text) }}">{{ $text->heading }}</a></h2> {{-- Заголовок ведёт на статью. --}}
    <p>{{ $text->excerpt }}</p> {{-- Короткий анонс статьи. --}}
    <p class="meta">Автор: {{ $text->author->login }} · {{ $text->created_at->format('d.m.Y') }}</p> {{-- Автор и дата. --}}
</article>
