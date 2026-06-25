@extends('layouts.admin')
@section('title', 'Categories & Services - Admin Console')

@section('styles')
<style>
    .category-group {
        border-bottom: 1px solid var(--card-border);
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .category-group:last-child {
        border-bottom: none;
        padding-bottom: 0;
        margin-bottom: 0;
    }
</style>
@endsection

@section('content')
            <div class="row g-4">
                <!-- Manage Categories Left side (Forms) -->
                <div class="col-md-4">
                    <!-- Create Category -->
                    <div class="portal-card mb-4">
                        <h5 class="fw-bold mb-3 text-theme-primary">New Category</h5>
                        <form action="{{ route('admin.categories.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="cat-name" class="form-label small fw-semibold text-theme-primary">Category Name</label>
                                <input type="text" name="name" id="cat-name" class="form-control" placeholder="e.g. Travel, Academics" required>
                            </div>
                            <div class="mb-3">
                                <label for="cat-desc" class="form-label small fw-semibold text-theme-primary">Description</label>
                                <textarea name="description" id="cat-desc" class="form-control" rows="3" placeholder="Brief outline..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Create Category</button>
                        </form>
                    </div>

                    <!-- Create Service -->
                    <div class="portal-card">
                        <h5 class="fw-bold mb-3 text-theme-primary">New Service Item</h5>
                        <form action="{{ route('admin.services.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="service-cat" class="form-label small fw-semibold text-theme-primary">Parent Category</label>
                                <select name="category_id" id="service-cat" class="form-select" required>
                                    <option value="" disabled selected>Select category...</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="service-name" class="form-label small fw-semibold text-theme-primary">Service Name</label>
                                <input type="text" name="name" id="service-name" class="form-control" placeholder="e.g. Local Tour Guide, Math Tutor" required>
                            </div>
                            <div class="mb-3">
                                <label for="service-desc" class="form-label small fw-semibold text-theme-primary">Description</label>
                                <textarea name="description" id="service-desc" class="form-control" rows="3" placeholder="Service description..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-secondary w-100 rounded-pill py-2">Add Service</button>
                        </form>
                    </div>
                </div>

                <!-- Categories and Services Right side (Display) -->
                <div class="col-md-8">
                    <div class="portal-card">
                        <h5 class="fw-bold mb-4 text-theme-primary">Category & Services Map</h5>
                        
                        @forelse($categories as $category)
                            <div class="category-group">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-theme-primary" style="font-size: 1.1rem;">{{ $category->name }}</h6>
                                        <small class="text-muted">{{ $category->description ?? 'No description' }}</small>
                                    </div>
                                    <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST" onsubmit="return confirm('Deleting this category will remove all sub-services! Continue?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Delete Category</button>
                                    </form>
                                </div>

                                <div class="ps-3 border-start border-2 border-light mt-3">
                                    <div class="row g-2">
                                        @forelse($category->services as $service)
                                            <div class="col-12 d-flex justify-content-between align-items-center bg-theme-secondary-subtle p-2 rounded border border-light">
                                                <div>
                                                    <span class="fw-medium text-theme-primary small">{{ $service->name }}</span>
                                                    @if($service->description)
                                                        <span class="text-muted small d-block" style="font-size: 0.8rem;">{{ $service->description }}</span>
                                                    @endif
                                                </div>
                                                <form action="{{ route('admin.services.delete', $service->id) }}" method="POST" onsubmit="return confirm('Delete this service?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link text-danger p-0 border-0 text-decoration-none small" style="font-size: 0.8rem;">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @empty
                                            <div class="col-12 text-muted small py-1">No specific services defined under this category yet.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-tags" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2">No categories defined in system directory.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
@endsection
