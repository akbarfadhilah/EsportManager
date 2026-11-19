@extends('layouts.app')

@section('title', $team->nama_tim)

@section('content')
<div class="page-header">
    <div class="page-header-info">
        @if($team->logo_esport)
            <img src="{{ asset('storage/' . $team->logo_esport) }}" class="team-logo-small" alt="">
        @else
            <div class="team-logo-small">{{ substr($team->nama_tim, 0, 1) }}</div>
        @endif
        <div>
            <h2>{{ $team->nama_tim }}</h2>
            <p class="text-muted">
                @if($team->region) 🌍 {{ $team->region->nama_negara }} @endif
                | 📍 {{ $team->tempat_kota_gh }}
            </p>
        </div>
    </div>
    <div class="action-btns">
        <a href="{{ route('teams.edit', $team) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit Team
        </a>
    </div>
</div>

<div class="form-grid">
    <div class="info-box">
        <h3><i class="fas fa-users"></i> Pertandingan & Pemain</h3>
        <div class="info-grid">
            <div class="info-label">Winrate</div>
            <div class="info-value"><span class="badge badge-role">{{ $team->winrate }}%</span></div>

            <div class="info-label">Total Kekayaan</div>
            <div class="info-value"><span class="worth">Rp {{ number_format($team->total_kekayaan, 0, ',', '.') }}</span></div>

            <div class="info-label">Jumlah Pemain Aktif</div>
            <div class="info-value">{{ count($team->players) }} / 10</div>
        </div>

        <h4>Struktur Kepelatihan & Manajemen</h4>
        <div class="info-grid">
            <div class="info-label">Pemilik</div>
            <div class="info-value">{{ $team->pemilik ? $team->pemilik->nama_asli : '-' }}</div>

            <div class="info-label">CEO</div>
            <div class="info-value">{{ $team->ceo ? $team->ceo->nama_asli : '-' }}</div>

            <div class="info-label">Pelatih Utama</div>
            <div class="info-value">{{ $team->pelatihUtama ? $team->pelatihUtama->nama_asli : '-' }}</div>

            <div class="info-label">Pelatih Kedua (Asisten)</div>
            <div class="info-value">{{ $team->pelatihKedua ? $team->pelatihKedua->nama_asli : '-' }}</div>

            <div class="info-label">Manager</div>
            <div class="info-value">{{ $team->manager ? $team->manager->nama_asli : '-' }}</div>

            <div class="info-label">Analis</div>
            <div class="info-value">{{ $team->analis ? $team->analis->nama_asli : '-' }}</div>
        </div>
    </div>

    <div>
        <div class="info-box">
            <h3><i class="fas fa-info-circle"></i> Deskripsi</h3>
            <p class="pre-line">{{ $team->deskripsi_tim }}</p>
        </div>

        <div class="info-box">
            <h3><i class="fas fa-trophy"></i> Penghargaan</h3>
            @if($team->penghargaan)
                <p class="pre-line">{{ $team->penghargaan }}</p>
            @else
                <p class="text-muted">Belum ada penghargaan tercatat.</p>
            @endif
        </div>
    </div>
</div>

<div class="table-card">
    <h3><i class="fas fa-gamepad"></i> Roster Pemain</h3>

    @if(count($team->players) > 0)
        <table>
            <thead>
                <tr>
                    <th>IGN</th>
                    <th>Nama Asli</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($team->players->sortBy('nickname_game') as $player)
                <tr>
                    <td><span class="nickname">{{ $player->nickname_game }}</span></td>
                    <td><span class="real-name">{{ $player->nama_asli }}</span></td>
                    <td><span class="badge badge-role">{{ $player->jabatan }}</span></td>
                    <td>
                        <a href="{{ route('players.show', $player) }}" class="btn-action btn-detail" title="Detail Pemain">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">Belum ada pemain di tim ini.</p>
    @endif
</div>

@endsection