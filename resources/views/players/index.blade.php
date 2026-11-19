@extends('layouts.app')

@section('title', 'Personnel')

@section('content')
<div class="page-header">
    <div class="page-header-info">
        <h2>👤 Personnel</h2>
    </div>
    <button type="button" class="btn btn-primary" data-open-personnel-type>
        <i class="fas fa-plus"></i> Add Personnel
    </button>
</div>

<dialog class="personnel-type-dialog" data-personnel-type-dialog aria-labelledby="personnel-type-title" aria-describedby="personnel-type-description">
    <div class="personnel-type-dialog-content">
        <h2 id="personnel-type-title">tambah personel apa dulu nih bang?</h2>
        <p id="personnel-type-description">
            Menambahkan seseorang bisa berarti Atlet esports atau manajer biasa. Setiap orang yang ditambahkan di bagian
            “Common” bisa diubah menjadi Atlet, begitu pun sebaliknya. Namun, seseorang yang sebelumnya ditambahkan
            sebagai “Common” perlu sedikit penyesuaian sebagai Atlet.
        </p>
        <div class="personnel-type-options">
            <a href="{{ route('players.create', ['type' => 'common']) }}" class="btn btn-secondary">Common</a>
            <a href="{{ route('players.create', ['type' => 'athlete']) }}" class="btn btn-primary">Atlet</a>
        </div>
    </div>
</dialog>

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
                    <span class="badge badge-outline">{{ $player->kategori_personel }}</span>
                </td>
                <td>{{ $player->tanggal_lahir->format('d M Y') }}</td>
                <td>
                    <span class="badge badge-role">{{ $player->jabatan }}</span>
                </td>
                <td>
                    @if($player->region)
                        @if($player->region->foto_negara)
                            <img src="{{ $player->region->foto_negara_url }}" class="region-flag" alt="{{ $player->region->nama_negara }}" title="{{ $player->region->nama_negara }}">
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
                        No personnel found.
                        <button type="button" class="btn btn-secondary btn-sm" data-open-personnel-type>Add one!</button>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    (() => {
        const dialog = document.querySelector('[data-personnel-type-dialog]');
        if (!dialog) return;

        document.querySelectorAll('[data-open-personnel-type]').forEach((button) => {
            button.addEventListener('click', () => dialog.showModal());
        });

        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) dialog.close();
        });
    })();
</script>
@endsection