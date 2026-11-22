@extends('layouts.app')

@section('title', 'Heroes')

@section('content')
<div class="directory-page">
<div class="page-header">
    <div class="page-header-info">
        <div class="page-header-icon">⚔️</div>
        <div>
            <div class="page-eyebrow">DATABASE / HERO POOL</div>
            <h2>Heroes</h2>
            <p class="page-subtitle">Hero pool, roles &amp; attack patterns</p>
        </div>
    </div>
    <a href="{{ route('heroes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Hero
    </a>
</div>

<div class="stat-strip">
    <div class="directory-stat stat-violet">
        <div class="directory-stat-icon">⚔️</div>
        <div class="directory-stat-label">Total Hero</div>
        <div class="directory-stat-value">{{ $heroes->count() }}</div>
        <div class="directory-stat-note">hero terdaftar</div>
    </div>
    <div class="directory-stat stat-cyan">
        <div class="directory-stat-icon">🎯</div>
        <div class="directory-stat-label">Total Pick</div>
        <div class="directory-stat-value">{{ $heroes->sum('lineups_count') }}</div>
        <div class="directory-stat-note">digunakan di lineup</div>
    </div>
    <div class="directory-stat stat-amber">
        <div class="directory-stat-icon">🔥</div>
        <div class="directory-stat-label">Paling Sering</div>
        <div class="directory-stat-value">{{ $heroes->max('lineups_count') ?? 0 }}×</div>
        <div class="directory-stat-note">pick tertinggi</div>
    </div>
</div>

<div class="table-card">
    <div class="directory-toolbar">
        <div>
            <div class="directory-toolbar-title">Daftar Hero</div>
            <div class="directory-toolbar-sub">Kelola hero, role, dan gaya serangan</div>
        </div>
        <span class="directory-count">{{ $heroes->count() }} entri</span>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 72px;"></th>
                <th>Hero</th>
                <th>Targeting</th>
                <th>Gaya Serangan</th>
                <th>Jenis Serangan</th>
                <th>Role</th>
                <th>Used</th>
                <th class="table-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($heroes as $hero)
            <tr>
                <td>
                    @if($hero->foto)
                        <img src="{{ asset('storage/' . $hero->foto) }}" class="hero-avatar" alt="{{ $hero->nama_hero }}">
                    @else
                        <div class="hero-avatar hero-avatar-placeholder">⚔️</div>
                    @endif
                </td>
                <td>
                    <span class="nickname" style="font-size: 1rem;">{{ $hero->nama_hero }}</span>
                    @if($hero->deskripsi)
                        <span class="real-name">{{ Str::limit($hero->deskripsi, 50) }}</span>
                    @endif
                </td>
                <td>
                    @if($hero->targeting)
                        <span class="badge badge-role">{{ $hero->targeting }}</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    @if($hero->gaya_permainan)
                        <span class="badge badge-indigo">{{ $hero->gaya_permainan }}</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    @if($hero->jenis_serangan)
                        <span class="badge badge-teal">{{ $hero->jenis_serangan }}</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <div class="hero-role-badges">
                        @if($hero->role_utama)
                            <span class="badge badge-role">{{ $hero->role_utama }}</span>
                            @foreach($hero->role_pendukung ?? [] as $role)
                                <span class="badge badge-outline">{{ $role }}</span>
                            @endforeach
                        @elseif($hero->roles)
                            @foreach($hero->roles as $role)
                                <span class="badge badge-outline">{{ $role }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </div>
                </td>
                <td>
                    <span class="used-badge">{{ $hero->lineups_count }}<span style="font-weight:400;font-size:0.7rem;">×</span></span>
                </td>
                <td>
                    <div class="action-btns right">
                        <a href="{{ route('heroes.edit', $hero) }}" class="btn-action btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('heroes.destroy', $hero) }}" method="POST" onsubmit="return confirm('Delete hero {{ addslashes($hero->nama_hero) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <div class="empty-icon">⚔️</div>
                        <p>Belum ada hero terdaftar.</p>
                        <a href="{{ route('heroes.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambahkan Hero Pertama
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>
@endsection
