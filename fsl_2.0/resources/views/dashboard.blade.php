@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="stats-grid fade-in">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Users</div>
            <div class="stat-value">12,345</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>12% from last month</span>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Revenue</div>
            <div class="stat-value">$45,678</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>8% from last month</span>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Orders</div>
            <div class="stat-value">1,234</div>
            <div class="stat-change negative">
                <i class="fas fa-arrow-down"></i>
                <span>3% from last month</span>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-eye"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Page Views</div>
            <div class="stat-value">89,012</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>24% from last month</span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card fade-in" style="animation-delay: 0.1s;">
    <div class="card-header">
        <h2 class="card-title">Recent Activity</h2>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>John Doe</td>
                        <td>Created new project</td>
                        <td><span class="badge badge-success">Completed</span></td>
                        <td>2024-01-15 10:30</td>
                    </tr>
                    <tr>
                        <td>Jane Smith</td>
                        <td>Updated profile</td>
                        <td><span class="badge badge-info">Pending</span></td>
                        <td>2024-01-15 09:45</td>
                    </tr>
                    <tr>
                        <td>Mike Johnson</td>
                        <td>Deleted file</td>
                        <td><span class="badge badge-warning">Warning</span></td>
                        <td>2024-01-15 08:20</td>
                    </tr>
                    <tr>
                        <td>Sarah Williams</td>
                        <td>Login successful</td>
                        <td><span class="badge badge-success">Success</span></td>
                        <td>2024-01-15 07:15</td>
                    </tr>
                    <tr>
                        <td>Tom Brown</td>
                        <td>Failed login attempt</td>
                        <td><span class="badge badge-danger">Failed</span></td>
                        <td>2024-01-14 23:50</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card fade-in" style="animation-delay: 0.2s; margin-top: 24px;">
    <div class="card-header">
        <h2 class="card-title">Quick Actions</h2>
    </div>
    <div class="card-body">
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <button class="btn btn-primary">
                <i class="fas fa-plus"></i>
                New Project
            </button>
            <button class="btn btn-secondary">
                <i class="fas fa-user-plus"></i>
                Add User
            </button>
            <button class="btn btn-secondary">
                <i class="fas fa-file-export"></i>
                Export Data
            </button>
            <button class="btn btn-secondary">
                <i class="fas fa-cog"></i>
                Settings
            </button>
        </div>
    </div>
</div>
@endsection

