@extends('layouts.app')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-article-tab"
                            data-bs-toggle="pill" data-bs-target="#pills-article"
                            type="button" role="tab" aria-controls="pills-article"
                            aria-selected="true">Home</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-category-tab"
                            data-bs-toggle="pill" data-bs-target="#pills-category"
                            type="button" role="tab" aria-controls="pills-category"
                            aria-selected="false">Profile</button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-article"
                     role="tabpanel" aria-labelledby="pills-article-tab">
                    Home Pane
                </div>
                <div class="tab-pane fade" id="pills-category"
                     role="tabpanel" aria-labelledby="pills-category-tab">
                    Category Pane
                </div>
            </div>
        </div>
    </div>
@endsection
