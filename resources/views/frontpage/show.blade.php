@extends('layouts.app')
@section('content')
    @empty($article)
        <div class="alert alert-warning">
            <p class="mb-0">There is no article.</p>
        </div>
    @endempty
    @if(!empty($article))
        <div class="card">
            <div class="card-body">
                <h1 class="mb-1 text-gradient-violet fw-bold">{{ $article->title }}</h1>
                <div class="mb-1">
                    <a href="{{ route('posts.index', 'category_id='.$article->category_id) }}"
                        class="badge-url badge bg-gradient-violet text-decoration-none"
                        data-id="{{ $article->category_id }}">{{ $article->category->name }}</a>
                </div>
                <p class="text-muted">by {{ $article->user->name }} | {{ date_format($article->updated_at, 'Y-m-d H:i') }}</p>
                <p>{!! $article->content !!}</p>
            </div>
        </div>
    @endif
@endsection