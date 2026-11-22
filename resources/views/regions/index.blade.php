@extends('layouts.app')

@section('title', 'Regions')

@section('content')
<div class="directory-page">
<div class="page-header">
    <div class="page-header-info">
        <div class="page-header-icon">🌍</div>
        <div>
            <div class="page-eyebrow">DATABASE / REGIONS</div>
            <h2>Regions</h2>
            <p class="page-subtitle">National esport associations &amp; coaching staff</p>
        </div>
    </div>
    <a href="{{ route('regions.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Region
    </a>
</div>

<div class="stat-strip">
    <div class="directory-stat stat-violet">
        <div class="directory-stat-icon">🌍</div>
        <div class="directory-stat-label">Total Region</div>
        <div class="directory-stat-value">{{ $regions->count() }}</div>
        <div class="directory-stat-note">negara terdaftar</div>
    </div>
    <div class="directory-stat stat-cyan">
        <div class="directory-stat-icon">👑</div>
        <div class="directory-stat-label">Ketua Assoc.</div>
        <div class="directory-stat-value">{{ $regions->filter(fn($r) => $r->ketua_persatuan_game_id)->count() }}</div>
        <div class="directory-stat-note">pimpinan persatuan</div>
    </div>
    <div class="directory-stat stat-amber">
        <div class="directory-stat-icon">📋</div>
        <div class="directory-stat-label">Pelatih Timnas</div>
        <div class="directory-stat-value">{{ $regions->filter(fn($r) => $r->pelatih_timnas_id)->count() }}</div>
        <div class="directory-stat-note">coach nasional</div>
    </div>
</div>

<div class="table-card">
    <div class="directory-toolbar">
        <div>
            <div class="directory-toolbar-title">Daftar Region</div>
            <div class="directory-toolbar-sub">Asosiasi &amp; staf pelatih nasional</div>
        </div>
        <span class="directory-count">{{ $regions->count() }} entri</span>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 80px;">Flag</th>
                <th>Country</th>
                <th>Head of Esports Assoc.</th>
                <th>National Team Coach</th>
                <th class="table-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($regions as $region)
            <tr>
                <td>
                    @if($region->foto_negara)
                        <img src="{{ $region->foto_negara_url }}" class="region-flag-lg" alt="{{ $region->nama_negara }}">
                    @else
                        <div class="region-flag-placeholder">{{ substr($region->nama_negara, 0, 1) }}</div>
                    @endif
                </td>
                <td>
                    <span class="team-name">{{ $region->nama_negara }}</span>
                </td>
                <td>
                    @if($region->ketua)
                        <div class="staff-chip">
                            <span class="staff-icon">👑</span>
                            <span>{{ $region->ketua->nama_asli ?? $region->ketua->nickname_game }}</span>
                        </div>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    @if($region->pelatihTimnas)
                        <div class="staff-chip">
                            <span class="staff-icon">📋</span>
                            <span>{{ $region->pelatihTimnas->nama_asli ?? $region->pelatihTimnas->nickname_game }}</span>
                        </div>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <div class="action-btns right">
                        <a href="{{ route('regions.edit', $region) }}" class="btn-action btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('regions.destroy', $region) }}" method="POST" onsubmit="return confirm('Delete region {{ addslashes($region->nama_negara) }}?')">
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
                <td colspan="5">
                    <div class="empty-state">
                        <div class="empty-icon">🌍</div>
                        <p>No regions found yet.</p>
                        <a href="{{ route('regions.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add First Region
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
