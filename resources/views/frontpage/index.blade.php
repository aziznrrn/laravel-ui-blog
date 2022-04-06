@extends('layouts.app')
@section('content')
    <h1 class="mb-4 text-white display-6">{{ config('app.name') }}'s Articles
        @if(!empty($category)) with <span class="badge p-2 text-gradient-violet">{{ $category->name }}</span> Category @endif
    </h1>
    @if(empty($articles->count()))
        <div class="alert alert-warning">
            <p class="mb-0">There are no articles @if(!empty($category)) with <span class="badge p-2 text-gradient-violet">{{ $category->name }}</span> Category. @endif</p>
        </div>
    @endif
    @foreach ($articles as $article)
        <div class="card">
            <div class="card-body">
                <h2 class="mb-1">
                    <a href="{{ route('posts.show', $article->id) }}"
                       class="text-gradient-violet fw-bold">{{ $article->title }}</a>
                </h2>
                <div class="mb-1">
                    <a href="{{ route('posts.index', 'category_id='.$article->category_id) }}"
                        class="badge-url badge bg-gradient-violet text-decoration-none"
                        data-id="{{ $article->category_id }}">{{ $article->category->name }}</a>
                </div>
                <p class="text-muted">by {{ $article->user->name }} | {{ date_format($article->updated_at, 'Y-m-d H:i') }}</p>
                <p>{!! $article->content !!}...</p>
            </div>
        </div>
    @endforeach
    @if(!empty($articles->links()->render()))
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                @if (empty($category))
                    {{ $articles->links() }}
                @else
                    {{ $articles->appends(['category_id' => $category->id])->links() }}
                @endif
                </div>
            </div>
        </div>
    @endif
@endsection