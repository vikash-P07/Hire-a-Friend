@extends('layouts.admin')
@section('title', 'Content Management (CMS) - Admin Console')

@section('content')
<div class="row g-4 mb-5">
                <!-- Create CMS Page Form -->
                <div class="col-md-4">
                    <div class="portal-card h-100">
                        <h5 class="fw-bold mb-3 text-theme-primary">Create Page</h5>
                        <form action="{{ route('admin.cms.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-theme-primary">Page Title</label>
                                <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. Terms of Service" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-theme-primary">Slug</label>
                                <input type="text" name="slug" class="form-control form-control-sm" placeholder="e.g. terms-of-service" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-theme-primary">Markdown/HTML Content</label>
                                <textarea name="content" class="form-control form-control-sm" rows="6" placeholder="Type page body content here..." required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-theme-primary">SEO Meta Title</label>
                                <input type="text" name="meta_title" class="form-control form-control-sm" placeholder="Optional SEO title">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-theme-primary">SEO Meta Description</label>
                                <textarea name="meta_description" class="form-control form-control-sm" rows="2" placeholder="Optional SEO summary"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-theme-primary">Visibility Status</label>
                                <select name="is_active" class="form-select form-select-sm" required>
                                    <option value="1">Published (Active)</option>
                                    <option value="0">Draft (Hidden)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary w-100 rounded-pill py-2">Publish Page</button>
                        </form>
                    </div>
                </div>

                <!-- Existing CMS Pages -->
                <div class="col-md-8">
                    <div class="portal-card h-100">
                        <h5 class="fw-bold mb-4 text-theme-primary">Static Pages Directory</h5>
                        
                        <div class="table-responsive">
                            <table class="table align-middle table-hover small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Page Title</th>
                                        <th>Route URL</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pages as $page)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-theme-primary">{{ $page->title }}</div>
                                            </td>
                                            <td>
                                                <a href="{{ route('cms.page', $page->slug) }}" target="_blank" class="small text-primary text-decoration-none">
                                                    /page/{{ $page->slug }}
                                                </a>
                                            </td>
                                            <td>
                                                @if($page->is_active)
                                                    <span class="badge bg-success-subtle text-success py-1 px-2.5 rounded-pill small">Published</span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary py-1 px-2.5 rounded-pill small">Draft</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex gap-1 justify-content-end">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editModal-{{ $page->id }}">
                                                        Edit
                                                    </button>
                                                    <form action="{{ route('admin.cms.delete', $page->id) }}" method="POST" onsubmit="return confirm('Delete this static page permanently?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>

                                                <!-- Edit CMS Page Modal -->
                                                <div class="modal fade" id="editModal-{{ $page->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content glass-card-static border-0 p-3 shadow-lg text-start">
                                                            <div class="modal-header border-0 pb-0">
                                                                 <h5 class="modal-title fw-bold text-theme-primary">Edit CMS Page: {{ $page->title }}</h5>
                                                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('admin.cms.update', $page->id) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">Page Title</label>
                                                                        <input type="text" name="title" class="form-control" value="{{ $page->title }}" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">Slug</label>
                                                                        <input type="text" name="slug" class="form-control" value="{{ $page->slug }}" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">Markdown/HTML Content</label>
                                                                        <textarea name="content" class="form-control" rows="8" required>{{ $page->content }}</textarea>
                                                                    </div>
                                                                    <div class="row g-2 mb-3">
                                                                        <div class="col-6">
                                                                            <label class="form-label fw-bold">SEO Meta Title</label>
                                                                            <input type="text" name="meta_title" class="form-control" value="{{ $page->meta_title }}">
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <label class="form-label fw-bold">SEO Meta Description</label>
                                                                            <input type="text" name="meta_description" class="form-control" value="{{ $page->meta_description }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">Visibility Status</label>
                                                                        <select name="is_active" class="form-select" required>
                                                                            <option value="1" {{ $page->is_active ? 'selected' : '' }}>Published (Active)</option>
                                                                            <option value="0" {{ !$page->is_active ? 'selected' : '' }}>Draft (Hidden)</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer border-0 pt-0">
                                                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-gradient px-4">Save Changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted small">No static pages created yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Part 2: Blogs Management CRUD -->
            <div class="row g-4">
                <!-- Create Blog Form -->
                <div class="col-md-4">
                    <div class="portal-card h-100">
                        <h5 class="fw-bold mb-3 text-theme-primary">Create Blog Post</h5>
                        <form action="{{ route('admin.blogs.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-theme-primary">Blog Title</label>
                                <input type="text" name="title" class="form-control form-control-sm" placeholder="Blog Title..." required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-theme-primary">Rich Text / Blog Content</label>
                                <textarea name="content" class="form-control form-control-sm" rows="6" placeholder="Type blog body content here..." required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-theme-primary">SEO Meta Title</label>
                                <input type="text" name="meta_title" class="form-control form-control-sm" placeholder="Optional SEO title">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-theme-primary">SEO Meta Description</label>
                                <textarea name="meta_description" class="form-control form-control-sm" rows="2" placeholder="Optional SEO description"></textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary w-100 rounded-pill py-2">Publish Blog Post</button>
                        </form>
                    </div>
                </div>

                <!-- Existing Blog Posts -->
                <div class="col-md-8">
                    <div class="portal-card h-100">
                        <h5 class="fw-bold mb-4 text-theme-primary">Blog Directory</h5>
                        <div class="table-responsive">
                            <table class="table align-middle table-hover small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Blog Title</th>
                                        <th>Author</th>
                                        <th>Published At</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($blogs as $blog)
                                        <tr>
                                            <td class="fw-bold text-theme-primary">{{ $blog->title }}</td>
                                            <td class="text-muted">{{ $blog->author_name }}</td>
                                            <td class="text-muted">{{ $blog->created_at->format('M d, Y') }}</td>
                                            <td class="text-end">
                                                <div class="d-flex gap-1 justify-content-end">
                                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2"
                                                        onclick="openEditBlog({{ $blog->id }}, '{{ addslashes($blog->title) }}', '{{ addslashes($blog->content) }}', '{{ addslashes($blog->meta_title ?? '') }}', '{{ addslashes($blog->meta_description ?? '') }}')">
                                                        Edit
                                                    </button>
                                                    <form action="{{ route('admin.blogs.delete', $blog->id) }}" method="POST" onsubmit="return confirm('Delete this blog post?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-4 text-muted">No blog posts registered yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

<!-- Edit Blog Modal -->
<div class="modal fade" id="editBlogModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Edit Blog Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editBlogForm" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label small fw-bold">Blog Title</label>
            <input type="text" name="title" id="editBlogTitle" class="form-control form-control-sm" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-bold">Blog Content</label>
            <textarea name="content" id="editBlogContent" class="form-control form-control-sm" rows="8" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-bold">SEO Meta Title</label>
            <input type="text" name="meta_title" id="editBlogMetaTitle" class="form-control form-control-sm">
          </div>
          <div class="mb-3">
            <label class="form-label small fw-bold">SEO Meta Description</label>
            <textarea name="meta_description" id="editBlogMetaDesc" class="form-control form-control-sm" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-primary rounded-pill">Update Blog Post</button>
        </div>
      </form>
    </div>
  </div>
</div>

@section('scripts')
<script>
function openEditBlog(id, title, content, metaTitle, metaDesc) {
    document.getElementById('editBlogForm').action = '/admin/cms/blogs/' + id + '/update';
    document.getElementById('editBlogTitle').value = title;
    document.getElementById('editBlogContent').value = content;
    document.getElementById('editBlogMetaTitle').value = metaTitle;
    document.getElementById('editBlogMetaDesc').value = metaDesc;
    new bootstrap.Modal(document.getElementById('editBlogModal')).show();
}
</script>
@endsection

@endsection
