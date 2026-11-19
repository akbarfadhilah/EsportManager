@extends('layouts.app')

@section('title', $player->nickname_game . ' - Profile')

@section('content')
<div class="pp-panel">
<div class="inner">

    <div class="pp-grid">
        <div class="pp-column">
            <div class="glass pp-profile-card">
                <div class="pp-avatar">
                    @if($player->photo)
                        <img src="{{ asset('storage/' . $player->photo) }}" alt="{{ $player->nickname_game }}">
                    @else
                        {{ strtoupper(substr($player->nickname_game, 0, 1)) }}
                    @endif
                </div>

                <h2 class="pp-nickname">{{ $player->nickname_game }}</h2>
                <p class="pp-realname">{{ $player->nama_asli }}</p>

                <div class="pp-jabatan">
                    <span class="badge badge-role">{{ $player->jabatan }}</span>
                </div>

                <div class="pp-stats-grid">
                    <div class="pp-stat">
                        <div class="pp-stat-label">Tim</div>
                        <div class="pp-stat-value team">
                            @php $ct = $player->current_team; @endphp
                            @if($ct['type'] === 'team')
                                <a href="{{ route('teams.show', $ct['param']) }}">{{ $ct['label'] }}</a>
                            @elseif($ct['type'] === 'region')
                                <a href="{{ route('regions.index') }}">REGION</a>
                            @else
                                <span class="free-agent">Free Agent</span>
                            @endif
                        </div>
                    </div>
                    <div class="pp-stat">
                        <div class="pp-stat-label">Negara / Region</div>
                        <div class="pp-stat-value">{{ $player->region ? $player->region->nama_negara : '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="glass pp-section">
                <div class="pp-section-title">
                    <i class="fas fa-info-circle"></i> Info Personal
                </div>

                <div class="pp-info-list">
                    <div class="pp-info-row">
                        <span class="pp-key">Nama Asli</span>
                        <span class="pp-val">{{ $player->nama_asli }}</span>
                    </div>
                    <div class="pp-info-row">
                        <span class="pp-key">Tanggal Lahir</span>
                        <span class="pp-val">{{ $player->tanggal_lahir ? $player->tanggal_lahir->format('d F Y') : '-' }}</span>
                    </div>
                    <div class="pp-info-row">
                        <span class="pp-key">Umur</span>
                        <span class="pp-val">{{ $player->tanggal_lahir ? $player->tanggal_lahir->age . ' Tahun' : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="pp-column">
            <div class="glass accent-indigo pp-section padded">
                <div class="pp-bio-header">
                    <i class="fas fa-book-open"></i> Biografi / Deskripsi
                </div>
                @if($player->deskripsi_pemain)
                    <div class="pp-bio">{{ $player->deskripsi_pemain }}</div>
                @else
                    <p class="pp-bio-empty">Belum ada biografi.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="pp-actions">
        <a href="{{ route('players.edit', $player) }}" class="btn btn-edit-profile">
            <i class="fas fa-edit"></i> Edit Personnel
        </a>
        <a href="{{ route('players.index') }}" class="btn btn-back-list">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

</div>
</div>
@endsection