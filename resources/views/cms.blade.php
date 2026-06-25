@extends('layouts.app')
@section('title', $page->title . ' - Companion Platform')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card glass-card-static shadow p-5 rounded-4 border-0">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
                    </ol>
                </nav>

                <h1 class="fw-bold mb-4 border-bottom pb-3">{{ $page->title }}</h1>
                
                <div class="page-content text-muted lh-lg">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
