@extends('layouts.app')

@section('title', $tournament->nama_tournament . ' - Leaderboard')

@section('content')
<div class="page-header">
    <div class="header-content">
        <a href="{{ route('tournaments.show', $tournament) }}" class="back-link">← Back to Tournament</a>
        <h2>{{ $tournament->nama_tournament }} - Leaderboard</h2>
    </div>
    <div class="header-btns">
        <a href="{{ route('matches.index', $tournament) }}" class="btn-custom btn-primary">Schedule Matches</a>
    </div>
</div>

<div class="tabs">
    <a href="{{ route('leaderboard.show', $tournament) }}" class="tab {{ request()->routeIs('leaderboard.show') ? 'active' : '' }}">Klasemen Tim</a>
    <a href="{{ route('leaderboard.playerContribution', $tournament) }}" class="tab {{ request()->routeIs('leaderboard.playerContribution') ? 'active' : '' }}">Player Contribution</a>
</div>

<div class="tab-content">

@if($statistics->count() > 0)
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Team</th>
                <th>Points</th>
                <th>Matches</th>
                <th>Wins</th>
                <th>Win Rate</th>
                <th class="table-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($statistics as $index => $stat)
            <tr>
                <td>
                    <div class="rank-box {{ $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : 'rank-other')) }}">
                        {{ $index + 1 }}
                    </div>
                </td>
                <td>
                    <div class="team-info">
                        {{ $stat->team->nama_tim }}
                        @if($index == 0 && $tournament->status == 'completed')
                            <span>🏆</span>
                        @endif
                    </div>
                </td>
                <td><span class="points-badge">{{ $stat->points }} pts</span></td>
                <td><span class="stat-text">{{ $stat->matches_played }}</span></td>
                <td><span class="stat-text text-success">{{ $stat->matches_won }}</span></td>
                <td>
                    <div class="stat-inline">{{ number_format($stat->win_rate, 1) }}%</div>
                    <div class="winrate-bar">
                        <div class="winrate-fill" style="width: {{ $stat->win_rate }}%;"></div>
                    </div>
                </td>
                <td>
                    <div class="action-btns right">
                        <a href="{{ route('leaderboard.teamHistory', [$tournament, $stat->team]) }}" class="btn-history">
                            History
                        </a>
                    </div>
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
        <h3>Leaderboard is empty</h3>
        <p>Standings will be updated automatically as matches are completed.</p>
        <a href="{{ route('matches.index', $tournament) }}" class="btn-custom btn-primary">Go to Matches</a>
    </div>
</div>
@endif

<div class="bottom-nav">
    <a href="{{ route('tournaments.show', $tournament) }}" class="btn-custom btn-secondary">
        Return to Tournament Dashboard
    </a>
</div>
</div>
@endsection