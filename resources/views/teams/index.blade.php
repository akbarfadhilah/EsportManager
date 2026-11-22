@extends('layouts.app')

@section('title', 'Teams')

@section('content')
<div class="directory-page">
<div class="page-header">
    <div class="page-header-info">
        <div class="page-header-icon">🛡️</div>
        <div>
            <div class="page-eyebrow">DATABASE / ORGANIZATIONS</div>
            <h2>Esport Teams</h2>
            <p class="page-subtitle">Manage all registered esport organizations</p>
        </div>
    </div>
    <a href="{{ route('teams.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Team
    </a>
</div>

<div class="stat-strip">
    <div class="directory-stat stat-violet">
        <div class="directory-stat-icon">🛡️</div>
        <div class="directory-stat-label">Total Tim</div>
        <div class="directory-stat-value">{{ $teams->count() }}</div>
        <div class="directory-stat-note">klub terdaftar</div>
    </div>
    <div class="directory-stat stat-cyan">
        <div class="directory-stat-icon">👥</div>
        <div class="directory-stat-label">Total Roster</div>
        <div class="directory-stat-value">{{ $teams->sum('players_count') }}</div>
        <div class="directory-stat-note">pemain aktif</div>
    </div>
    <div class="directory-stat stat-amber">
        <div class="directory-stat-icon">🌍</div>
        <div class="directory-stat-label">Negara</div>
        <div class="directory-stat-value">{{ $teams->pluck('region_id')->filter()->unique()->count() }}</div>
        <div class="directory-stat-note">region terwakili</div>
    </div>
</div>

<div class="table-card">
    <div class="directory-toolbar">
        <div>
            <div class="directory-toolbar-title">Daftar Tim</div>
            <div class="directory-toolbar-sub">Organisasi, staf, dan roster pemain</div>
        </div>
        <span class="directory-count">{{ $teams->count() }} entri</span>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 64px;"></th>
                <th>Team</th>
                <th>Region</th>
                <th>Head Coach</th>
                <th>Roster</th>
                <th>Total Worth</th>
                <th class="table-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teams as $team)
            <tr>
                <td>
                    @if($team->logo_esport)
                        <img src="{{ asset('storage/' . $team->logo_esport) }}" class="team-logo-small" alt="{{ $team->nama_tim }}">
                    @else
                        <div class="team-logo-small">{{ substr($team->nama_tim, 0, 1) }}</div>
                    @endif
                </td>
                <td>
                    <a href="{{ route('teams.show', $team) }}" style="text-decoration:none;">
                        <span class="team-name">{{ $team->nama_tim }}</span>
                    </a>
                    <span class="real-name">Owner: {{ $team->pemilik ? $team->pemilik->nama_asli : '—' }}</span>
                </td>
                <td>
                    @if($team->region)
                        @if($team->region->foto_negara)
                            <span class="region-pill">
                                <img src="{{ $team->region->foto_negara_url }}" class="region-flag" alt="">
                                {{ $team->region->nama_negara }}
                            </span>
                        @else
                            <span class="badge badge-outline">🌍 {{ $team->region->nama_negara }}</span>
                        @endif
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    @if($team->pelatihUtama)
                        <span class="coach-name">{{ $team->pelatihUtama->nama_asli }}</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <div class="roster-count">
                        <span class="roster-num">{{ $team->players_count }}</span>
                        <span class="roster-max">/ 10</span>
                    </div>
                </td>
                <td>
                    <span class="worth">Rp {{ number_format($team->total_kekayaan, 0, ',', '.') }}</span>
                </td>
                <td>
                    <div class="action-btns right">
                        <a href="{{ route('teams.show', $team) }}" class="btn-action btn-detail" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('teams.edit', $team) }}" class="btn-action btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('teams.destroy', $team) }}" method="POST" onsubmit="return confirm('Delete team {{ addslashes($team->nama_tim) }}?')">
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
                <td colspan="7">
                    <div class="empty-state">
                        <div class="empty-icon">🛡️</div>
                        <p>No teams registered yet.</p>
                        <a href="{{ route('teams.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create First Team
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
