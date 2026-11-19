@extends('layouts.app')

@section('title', $tournament->nama_tournament . ' - Matches')

@section('content')
<div class="page-header">
    <div class="header-content">
        <a href="{{ route('tournaments.show', $tournament) }}" class="back-link">← Back to Tournament</a>
        <h2>{{ $tournament->nama_tournament }} - Matches</h2>
        <span class="tier-badge">🏆 {{ $tournament->tier }} • {{ $tournament->format === 'knockout' ? 'Knockout (Gugur)' : 'Standar (Round Robin 2 Pekan)' }} • {{ $tournament->bo_format }}</span>
    </div>
    <div class="header-btns">
        <a href="{{ route('leaderboard.show', $tournament) }}" class="btn-custom btn-secondary">Leaderboard</a>
    </div>
</div>

@if($days->count() > 0)
<div class="schedule-board">
    @foreach($days as $day => $dayMatches)
        <div class="day-section">
            <div class="day-header">
                <span class="day-label">
                    @if($tournament->format === 'knockout')
                        {{ $day == $days->keys()->last() ? 'Grand Final' : 'Ronde ' . $day }}
                    @else
                        Hari / Pekan {{ $day }}
                    @endif
                </span>
                <span class="day-count">{{ $dayMatches->count() }} match</span>
            </div>

            <div class="day-matches">
                @foreach($dayMatches as $match)
                    @php
                        $p1 = $match->participants[0] ?? null;
                        $p2 = $match->participants[1] ?? null;
                        $t1 = $p1 ? ($p1->team->nama_tim ?? ($p1->esport_team_id ? 'TBD' : 'Bye')) : 'TBD';
                        $t2 = $p2 ? ($p2->team->nama_tim ?? ($p2->esport_team_id ? 'TBD' : 'Bye')) : 'TBD';
                    @endphp
                    <a href="{{ route('matches.show', $match) }}" class="match-card status-{{ $match->status }}">
                        <div class="match-card-top">
                            <span class="match-num">#{{ $match->match_number }}</span>
                            <span class="format-badge">{{ $match->bo_format }}</span>
                            <span class="status-badge status-{{ $match->status }}">
                                {{ ucfirst(str_replace('_', ' ', $match->status)) }}
                            </span>
                        </div>
                        <div class="match-card-teams">
                            <span class="mc-team {{ $match->winner_team_id && ($p1 && $p1->esport_team_id == $match->winner_team_id) ? 'winner' : '' }}">{{ $t1 }}</span>
                            <span class="mc-vs">vs</span>
                            <span class="mc-team {{ $match->winner_team_id && ($p2 && $p2->esport_team_id == $match->winner_team_id) ? 'winner' : '' }}">{{ $t2 }}</span>
                        </div>
                        <div class="match-card-score">
                            <span class="mc-score">{{ $p1 ? $p1->rounds_won : 0 }}</span>
                            <span>-</span>
                            <span class="mc-score">{{ $p2 ? $p2->rounds_won : 0 }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@else
<div class="table-card empty">
    <div class="empty-state">
        <div class="empty-icon">⚔️</div>
        <h3>Belum ada jadwal pertandingan</h3>
        <p>Jadwal otomatis dibuat saat turnamen dibuat/diubah dengan tim peserta lengkap.</p>
        <a href="{{ route('tournaments.edit', $tournament) }}" class="btn-custom btn-primary">Atur Tim Peserta</a>
    </div>
</div>
@endif
@endsection