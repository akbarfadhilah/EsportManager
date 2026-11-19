@extends('layouts.app')

@section('title', $team->nama_tim . ' - Match History')

@section('content')
<div class="team-header">
    <h2>{{ $team->nama_tim }} - Match History</h2>
    <p class="subtitle">{{ $tournament->nama_tournament }}</p>

    @if($stats)
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label">Points</div>
            <div class="stat-value">{{ $stats->points }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Matches</div>
            <div class="stat-value">{{ $stats->matches_played }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Wins</div>
            <div class="stat-value">{{ $stats->matches_won }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Win Rate</div>
            <div class="stat-value">{{ number_format($stats->win_rate, 1) }}%</div>
        </div>
    </div>
    @endif
</div>

@if($matches->count() > 0)
<div class="table-card matches-table">
    <table>
        <thead>
            <tr>
                <th>Match #</th>
                <th>Opponent</th>
                <th>Score</th>
                <th>Result</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matches as $match)
            @php
                $teamParticipant = $match->participants->where('esport_team_id', $team->id)->first();
                $opponentParticipant = $match->participants->where('esport_team_id', '!=', $team->id)->first();
                $isWinner = $match->winner_team_id == $team->id;
            @endphp
            <tr>
                <td><strong>#{{ $match->match_number }}</strong></td>
                <td>{{ $opponentParticipant ? $opponentParticipant->team->nama_tim : 'N/A' }}</td>
                <td>{{ $teamParticipant->rounds_won ?? 0 }} - {{ $opponentParticipant->rounds_won ?? 0 }}</td>
                <td>
                    @if($match->status === 'completed')
                        <span class="{{ $isWinner ? 'result-win' : 'result-loss' }}">
                            {{ $isWinner ? 'WIN' : 'LOSS' }}
                        </span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>{{ ucfirst(str_replace('_', ' ', $match->status)) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="no-matches">
    <p class="text-muted">No matches played yet.</p>
</div>
@endif

<div class="bottom-nav">
    <a href="{{ route('leaderboard.show', $tournament) }}" class="btn btn-secondary">Back to Leaderboard</a>
</div>
@endsection