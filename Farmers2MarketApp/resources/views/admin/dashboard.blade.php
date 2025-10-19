@extends('layouts.dashboard')

@section('content')
<div class="modern-dashboard">
    <div class="container-fluid px-4 py-4">
        {{-- Header Section --}}
        <div class="dashboard-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="dashboard-title mb-2">
                        <span class="gradient-text">Admin Dashboard</span>
                    </h1>
                    <p class="text-muted mb-0">
                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i>
                        Manage your platform efficiently
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="date-badge">
                        <i class="bi bi-calendar-event me-2"></i>
                        {{ date('l, F d, Y') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Analytics Cards --}}
        <div class="row g-4 mb-4">
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="stat-card stat-card-blue">
                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Users</span>
                        <h3 class="stat-value">{{ $analytics['total_users'] }}</h3>
                        <span class="stat-trend up">
                            <i class="bi bi-arrow-up"></i> 12.5%
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="stat-card stat-card-green">
                    <div class="stat-icon">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Farmers</span>
                        <h3 class="stat-value">{{ $analytics['total_farmers'] }}</h3>
                        <span class="stat-trend up">
                            <i class="bi bi-arrow-up"></i> 8.2%
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="stat-card stat-card-cyan">
                    <div class="stat-icon">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Buyers</span>
                        <h3 class="stat-value">{{ $analytics['total_buyers'] }}</h3>
                        <span class="stat-trend up">
                            <i class="bi bi-arrow-up"></i> 15.3%
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="stat-card stat-card-orange">
                    <div class="stat-icon">
                        <i class="bi bi-flower3"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Crops</span>
                        <h3 class="stat-value">{{ $analytics['total_crops'] }}</h3>
                        <span class="stat-trend up">
                            <i class="bi bi-arrow-up"></i> 20.1%
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="stat-card stat-card-purple">
                    <div class="stat-icon">
                        <i class="bi bi-cart-fill"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Orders</span>
                        <h3 class="stat-value">{{ $analytics['total_orders'] }}</h3>
                        <span class="stat-trend up">
                            <i class="bi bi-arrow-up"></i> 5.7%
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="stat-card stat-card-pink">
                    <div class="stat-icon">
                        <i class="bi bi-credit-card-fill"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Payments</span>
                        <h3 class="stat-value">{{ $analytics['total_payments'] }}</h3>
                        <span class="stat-trend up">
                            <i class="bi bi-arrow-up"></i> 18.9%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Farmers Section --}}
        <div class="modern-card mb-4">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title-modern mb-1">
                            <i class="bi bi-person-check-fill text-success me-2"></i>
                            Pending Farmers Verification
                        </h5>
                        <p class="card-subtitle-modern mb-0">Review and approve farmer registrations</p>
                    </div>
                    @if($pendingFarmers->count() > 0)
                        <span class="status-badge status-warning">
                            {{ $pendingFarmers->count() }} Pending
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-body-modern">
                @forelse($pendingFarmers as $farmer)
                <div class="approval-item">
                    <div class="approval-avatar bg-success">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="approval-info">
                        <h6 class="approval-name">{{ $farmer->user->name ?? 'N/A' }}</h6>
                        <p class="approval-email">{{ $farmer->user->email ?? 'N/A' }}</p>
                    </div>
                    <div class="approval-meta">
                        <span class="meta-badge">
                            <i class="bi bi-hash"></i> {{ $farmer->farmer_code ?? 'N/A' }}
                        </span>
                        <span class="meta-location">
                            <i class="bi bi-geo-alt-fill"></i> {{ $farmer->location ?? 'N/A' }}
                        </span>
                    </div>
                    <div class="approval-action">
                        <a href="{{ route('admin.user.verify', $farmer->farmer_id) }}" class="btn-approve">
                            <i class="bi bi-check-circle-fill me-1"></i> Verify
                        </a>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h5>All Caught Up!</h5>
                    <p>No pending farmer verifications at the moment</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Pending Buyers Section --}}
        <div class="modern-card mb-4">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title-modern mb-1">
                            <i class="bi bi-briefcase-fill text-info me-2"></i>
                            Pending Buyers Verification
                        </h5>
                        <p class="card-subtitle-modern mb-0">Review and approve buyer registrations</p>
                    </div>
                    @if($pendingBuyers->count() > 0)
                        <span class="status-badge status-warning">
                            {{ $pendingBuyers->count() }} Pending
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-body-modern">
                @forelse($pendingBuyers as $buyer)
                <div class="approval-item">
                    <div class="approval-avatar bg-info">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <div class="approval-info">
                        <h6 class="approval-name">{{ $buyer->user->name ?? 'N/A' }}</h6>
                        <p class="approval-email">{{ $buyer->user->email ?? 'N/A' }}</p>
                    </div>
                    <div class="approval-meta">
                        <span class="meta-badge">
                            <i class="bi bi-hash"></i> {{ $buyer->buyer_code ?? 'N/A' }}
                        </span>
                        <span class="meta-location">
                            <i class="bi bi-building"></i> {{ $buyer->company_name ?? 'N/A' }}
                        </span>
                    </div>
                    <div class="approval-action">
                        <a href="{{ route('admin.user.verify', $buyer->buyer_id) }}" class="btn-approve">
                            <i class="bi bi-check-circle-fill me-1"></i> Verify
                        </a>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h5>All Caught Up!</h5>
                    <p>No pending buyer verifications at the moment</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Pending Crops Section --}}
        <div class="modern-card mb-4">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title-modern mb-1">
                            <i class="bi bi-flower3 text-warning me-2"></i>
                            Pending Crop Listings
                        </h5>
                        <p class="card-subtitle-modern mb-0">Review and approve crop submissions</p>
                    </div>
                    @if($pendingCrops->count() > 0)
                        <span class="status-badge status-warning">
                            {{ $pendingCrops->count() }} Pending
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-body-modern">
                @forelse($pendingCrops as $crop)
                <div class="approval-item">
                    <div class="approval-avatar bg-warning">
                        <i class="bi bi-flower3"></i>
                    </div>
                    <div class="approval-info">
                        <h6 class="approval-name">{{ $crop->crop_name ?? 'N/A' }}</h6>
                        <p class="approval-email">By {{ $crop->farmer?->name ?? 'N/A' }}</p>
                    </div>
                    <div class="approval-meta">
                        <span class="meta-badge">
                            <i class="bi bi-box"></i> {{ $crop->quantity_available ?? 'N/A' }} kg
                        </span>
                        <span class="meta-price">
                            <i class="bi bi-currency-dollar"></i> Rs. {{ $crop->price ?? 'N/A' }}/kg
                        </span>
                        <span class="status-badge status-warning-sm">
                            {{ $crop->status ?? 'Pending' }}
                        </span>
                    </div>
                    <div class="approval-action">
                        <a href="{{ route('admin.crop.verify', $crop->crop_id) }}" class="btn-approve">
                            <i class="bi bi-check-circle-fill me-1"></i> Verify
                        </a>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h5>All Caught Up!</h5>
                    <p>No pending crop listings to verify</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Dashboard Styles */
.modern-dashboard {
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

.dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    margin: -1rem -1rem 2rem -1rem;
    padding: 2rem;
    border-radius: 0 0 30px 30px;
    color: white;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
}

.dashboard-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
}

.gradient-text {
    background: linear-gradient(to right, #ffffff, #e0e7ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.date-badge {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    display: inline-block;
    font-weight: 500;
}

/* Stat Cards */
.stat-card {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.stat-card:hover::before {
    transform: scale(1.1);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}

.stat-card-blue .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
.stat-card-green .stat-icon { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; }
.stat-card-cyan .stat-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; }
.stat-card-orange .stat-icon { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; }
.stat-card-purple .stat-icon { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #764ba2; }
.stat-card-pink .stat-icon { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: #e91e63; }

.stat-label {
    display: block;
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.stat-trend {
    display: inline-block;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 50px;
    margin-top: 0.5rem;
}

.stat-trend.up {
    background: #d1fae5;
    color: #065f46;
}

/* Modern Card */
.modern-card {
    background: white;
    border-radius: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.card-header-modern {
    padding: 1.5rem 2rem;
    background: linear-gradient(to right, #f8f9fa, #ffffff);
    border-bottom: 2px solid #f1f3f5;
}

.card-title-modern {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.card-subtitle-modern {
    font-size: 0.875rem;
    color: #6c757d;
}

.card-body-modern {
    padding: 1rem 2rem 2rem 2rem;
}

/* Approval Items */
.approval-item {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 16px;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.approval-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.approval-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.approval-info {
    flex: 1;
    min-width: 0;
}

.approval-name {
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 0.25rem 0;
}

.approval-email {
    font-size: 0.875rem;
    color: #6c757d;
    margin: 0;
}

.approval-meta {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.meta-badge, .meta-location, .meta-price {
    font-size: 0.813rem;
    padding: 0.5rem 1rem;
    background: white;
    border-radius: 50px;
    color: #495057;
    font-weight: 500;
}

.status-badge {
    padding: 0.5rem 1.25rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
}

.status-warning {
    background: linear-gradient(135deg, #ffc107, #ff9800);
    color: white;
}

.status-warning-sm {
    background: #fff3cd;
    color: #856404;
    font-size: 0.75rem;
    padding: 0.375rem 0.875rem;
}

.btn-approve {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    display: inline-block;
    box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);
}

.btn-approve:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(17, 153, 142, 0.4);
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: white;
}

.empty-state h5 {
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #6c757d;
}

/* Responsive */
@media (max-width: 768px) {
    .approval-item {
        flex-wrap: wrap;
    }
    
    .approval-meta {
        width: 100%;
        margin-top: 0.5rem;
    }
    
    .approval-action {
        width: 100%;
        margin-top: 0.5rem;
    }
    
    .btn-approve {
        width: 100%;
        text-align: center;
    }
}
</style>
@endsection