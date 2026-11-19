@extends('layouts.app')

@section('title', 'Teams')

@section('content')
<div class="page-header">
    <div class="page-header-info">
        <h2>🛡️ Teams</h2>
    </div>
    <a href="{{ route('teams.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Team
    </a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th style="width: 60px;">Logo</th>
                <th>Team Info</th>
                <th>Origin Region</th>
                <th>Head Coach</th>
                <th>Players</th>
                <th>Total Worth</th>
                <th class="table-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teams as $team)
            <tr>
                <td>
                    @if($team->logo_esport)
                        <img src="{{ asset('storage/' . $team->logo_esport) }}" class="team-logo-small" alt="">
                    @else
                        <div class="team-logo-small">{{ substr($team->nama_tim, 0, 1) }}</div>
                    @endif
                </td>
                <td>
                    <span class="team-name">{{ $team->nama_tim }}</span>
                    <span class="team-meta">Owner: {{ $team->pemilik ? $team->pemilik->nama_asli : '-' }}</span>
                </td>
                <td>
                    @if($team->region)
                        <span class="badge badge-outline">🌍 {{ $team->region->nama_negara }}</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>{{ $team->pelatihUtama ? $team->pelatihUtama->nama_asli : '-' }}</td>
                <td>
                    <span class="player-count">{{ $team->players_count }}</span><span class="team-meta">/10</span>
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
                        <form action="{{ route('teams.destroy', $team) }}" method="POST" onsubmit="return confirm('Delete this team?')">
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
                        No teams found. <a href="{{ route('teams.create') }}">Create one!</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection