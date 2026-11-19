@extends('layouts.app')

@section('title', 'Personnel')

@section('content')
<div class="page-header">
    <div class="page-header-info">
        <h2>👤 Personnel</h2>
    </div>
    <a href="{{ route('players.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Personnel
    </a>
</div>

<form action="{{ route('players.index') }}" method="GET" class="form-group">
    <label for="region_id">Filter by Region / Negara</label>
    <div class="filter-row">
        <select name="region_id" id="region_id" onchange="this.form.submit()">
            <option value="">-- Semua Negara --</option>
            @foreach($regions as $region)
                <option value="{{ $region->id }}" {{ (string) $selectedRegion === (string) $region->id ? 'selected' : '' }}>
                    {{ $region->nama_negara }}
                </option>
            @endforeach
        </select>
        @if($selectedRegion)
            <a href="{{ route('players.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        @endif
    </div>
</form>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Identity</th>
                <th>Birth Date</th>
                <th>Jabatan</th>
                <th>Region</th>
                <th>Current Team</th>
                <th class="table-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($players as $player)
            <tr>
                <td>
                    <span class="nickname">{{ $player->nickname_game }}</span>
                    <span class="real-name">{{ $player->nama_asli }}</span>
                </td>
                <td>{{ $player->tanggal_lahir->format('d M Y') }}</td>
                <td>
                    <span class="badge badge-role">{{ $player->jabatan }}</span>
                </td>
                <td>
                    @if($player->region)
                        @if($player->region->foto_negara)
                            <img src="{{ asset('storage/' . $player->region->foto_negara) }}" class="region-flag" alt="{{ $player->region->nama_negara }}" title="{{ $player->region->nama_negara }}">
                        @else
                            <span>{{ $player->region->nama_negara }}</span>
                        @endif
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    @php $ct = $player->current_team; @endphp
                    @if($ct['type'] === 'team')
                        <a class="team-link team-info" href="{{ route('teams.show', $ct['param']) }}" title="{{ $ct['label'] }}">
                            @if($player->esportTeam->logo_esport)
                                <img src="{{ asset('storage/' . $player->esportTeam->logo_esport) }}" class="team-logo-small" alt="">
                            @endif
                            <span>{{ $ct['label'] }}</span>
                        </a>
                    @elseif($ct['type'] === 'region')
                        <span class="badge badge-outline">🌍 <a class="team-link" href="{{ route('regions.index') }}">REGION</a></span>
                    @else
                        <span class="text-muted text-faded">Free Agent</span>
                    @endif
                </td>
                <td>
                    <div class="action-btns right">
                        <a href="{{ route('players.show', $player) }}" class="btn-action btn-detail" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('players.edit', $player) }}" class="btn-action btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('players.destroy', $player) }}" method="POST" onsubmit="return confirm('Delete this personnel?')">
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
                <td colspan="6">
                    <div class="empty-state">
                        <div class="empty-icon">👤</div>
                        No personnel found. <a href="{{ route('players.create') }}">Add one!</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection