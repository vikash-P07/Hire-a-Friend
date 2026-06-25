@extends('layouts.admin')
@section('title', 'Marketing & Promos - Admin Console')

@section('content')
<div class="row g-4">
                
                <!-- Banners Management -->
                <div class="col-12">
                    <div class="portal-card">
                        <h4 class="fw-bold mb-4 text-theme-primary"><i class="bi bi-images text-primary me-2"></i>Homepage &amp; Promotional Banners</h4>
                        
                        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="row g-3 mb-4 border p-3 rounded bg-theme-secondary">
                            @csrf
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Banner Title</label>
                                <input type="text" name="title" class="form-control form-control-sm" placeholder="Title..." required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Type</label>
                                <select name="type" class="form-select form-select-sm" required>
                                    <option value="homepage">Homepage Banner</option>
                                    <option value="promotional">Promotional Banner</option>
                                    <option value="offer">Offer Banner</option>
                                    <option value="event">Event Banner</option>
                                    <option value="marketing">Marketing Banner</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Image Upload</label>
                                <input type="file" name="image" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-sm btn-primary w-100 rounded-pill">Upload Banner</button>
                            </div>
                        </form>

                        <div class="row g-3">
                            @forelse($banners as $b)
                                <div class="col-md-6">
                                    <div class="card shadow-sm h-100">
                                        <img src="{{ asset('storage/' . $b->image_path) }}" class="card-img-top object-fit-cover" height="150" alt="{{ $b->title }}">
                                        <div class="card-body p-3">
                                            <h6 class="fw-bold text-theme-primary mb-1">{{ $b->title }}</h6>
                                            <span class="badge bg-secondary text-uppercase mb-2" style="font-size: 0.65rem;">{{ $b->type }}</span>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">Active</small>
                                                <form action="{{ route('admin.banners.delete', $b->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0.5 rounded-pill px-2.5" style="font-size: 0.72rem;">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-3 text-muted small">No promotional banners uploaded.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Coupons Management -->
                <div class="col-12">
                    <div class="portal-card">
                        <h4 class="fw-bold mb-4 text-theme-primary"><i class="bi bi-tag text-primary me-2"></i>Coupon &amp; Campaign Management</h4>
                        
                        <form action="{{ route('admin.coupons.store') }}" method="POST" class="row g-3 mb-4 border p-3 rounded bg-theme-secondary">
                            @csrf
                            <div class="col-md-2">
                                <label class="form-label small fw-bold">Coupon Code</label>
                                <input type="text" name="code" class="form-control form-control-sm" placeholder="e.g. SUMMER50" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold">Discount Type</label>
                                <select name="type" class="form-select form-select-sm" required>
                                    <option value="percentage">Percentage</option>
                                    <option value="flat">Flat</option>
                                    <option value="cashback">Cashback</option>
                                    <option value="referral">Referral</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold">Value</label>
                                <input type="number" name="value" class="form-control form-control-sm" placeholder="e.g. 50" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold">Max Uses</label>
                                <input type="number" name="max_uses" class="form-control form-control-sm" placeholder="e.g. 100" value="100" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold">Expiry Date</label>
                                <input type="date" name="expires_at" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-sm btn-primary w-100 rounded-pill">Create</button>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table align-middle table-hover small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Discount</th>
                                        <th>Usage</th>
                                        <th>Expires At</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($coupons as $coupon)
                                        <tr>
                                            <td class="fw-bold text-theme-primary">{{ $coupon->code }}</td>
                                            <td class="text-uppercase text-muted">{{ $coupon->type }}</td>
                                            <td class="fw-bold text-success">{{ $coupon->type == 'percentage' ? $coupon->value.'%' : '₹'.number_format($coupon->value) }}</td>
                                            <td>{{ $coupon->uses_count }} / {{ $coupon->max_uses }}</td>
                                            <td>{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : 'Unlimited' }}</td>
                                            <td>
                                                <form action="{{ route('admin.coupons.toggle-status', $coupon->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm rounded-pill px-2.5 py-0.5 {{ $coupon->is_active ? 'btn-success' : 'btn-secondary' }}" style="font-size: 0.72rem;">
                                                        {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-outline-primary py-0.5 rounded-pill px-2.5 me-1" style="font-size: 0.72rem;" data-bs-toggle="modal" data-bs-target="#editCoupon{{ $coupon->id }}">Edit</button>
                                                <form action="{{ route('admin.coupons.delete', $coupon->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0.5 rounded-pill px-2.5" style="font-size: 0.72rem;" onclick="return confirm('Delete this coupon?');">Del</button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Edit Coupon Modal -->
                                        <div class="modal fade" id="editCoupon{{ $coupon->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fs-6 fw-bold">Edit Coupon: {{ $coupon->code }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold">Coupon Code</label>
                                                                <input type="text" name="code" class="form-control" value="{{ $coupon->code }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold">Discount Type</label>
                                                                <select name="type" class="form-select" required>
                                                                    <option value="percentage" {{ $coupon->type=='percentage'?'selected':'' }}>Percentage</option>
                                                                    <option value="flat" {{ $coupon->type=='flat'?'selected':'' }}>Flat Discount</option>
                                                                    <option value="cashback" {{ $coupon->type=='cashback'?'selected':'' }}>Cashback</option>
                                                                    <option value="referral" {{ $coupon->type=='referral'?'selected':'' }}>Referral</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold">Value</label>
                                                                <input type="number" name="value" class="form-control" value="{{ $coupon->value }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold">Max Uses</label>
                                                                <input type="number" name="max_uses" class="form-control" value="{{ $coupon->max_uses }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold">Expiry Date</label>
                                                                <input type="date" name="expires_at" class="form-control" value="{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '' }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light rounded-pill btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary rounded-pill btn-sm px-3">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr><td colspan="6" class="text-center py-3 text-muted">No coupon campaigns active.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


@endsection
