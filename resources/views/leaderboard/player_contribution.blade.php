@extends('layouts.app')

@section('title', $tournament->nama_tournament . ' - Player Contribution')

@section('content')
<div class="page-header">
    <div class="header-content">
        <a href="{{ route('tournaments.show', $tournament) }}" class="back-link">← Back to Tournament</a>
        <h2>{{ $tournament->nama_tournament }} - Player Contribution</h2>
    </div>
    <div class="header-btns">
        <a href="{{ route('matches.index', $tournament) }}" class="btn-custom btn-primary">Schedule Matches</a>
    </div>
</div>

<div class="tabs">
    <a href="{{ route('leaderboard.show', $tournament) }}" class="tab">Klasemen Tim</a>
    <a href="{{ route('leaderboard.playerContribution', $tournament) }}" class="tab active">Player Contribution</a>
</div>

<div class="tab-content">
    @if(count($rows) > 0)
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Nama Nickname Player</th>
                    <th>Jumlah MVP</th>
                    <th>Asal Tim</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                <tr>
                    <td>
                        <div class="rank-box {{ $loop->index == 0 ? 'rank-1' : ($loop->index == 1 ? 'rank-2' : ($loop->index == 2 ? 'rank-3' : 'rank-other')) }}">
                            {{ $row['number'] }}
                        </div>
                    </td>
                    <td>
                        <div class="team-info">
                            {{ $row['player']->nickname_game }}
                            @if($loop->index == 0)
                                <span>⭐</span>
                            @endif
                            <div class="real-name">{{ $row['player']->nama_asli }}</div>
                        </div>
                    </td>
                    <td><span class="points-badge">{{ $row['mvp_count'] }}x MVP</span></td>
                    <td>
                        @if($row['team'])
                            {{ $row['team']->nama_tim }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="table-card empty">
        <div class="empty-state">
            <div class="empty-icon">📊</div>
            <h3>Belum ada data kontribusi player</h3>
            <p>Data MVP setiap ronde akan muncul otomatis begitu pertandingan dimainkan.</p>
            <a href="{{ route('matches.index', $tournament) }}" class="btn-custom btn-primary">Go to Matches</a>
        </div>
    </div>
    @endif
</div>

<div class="bottom-nav">
    <a href="{{ route('tournaments.show', $tournament) }}" class="btn-custom btn-secondary">
        Return to Tournament Dashboard
    </a>
</div>
@endsection