@extends('layouts.app')

@section('title', $tournament->nama_tournament)

@section('content')
<div class="page-header">
    <div class="header-content">
        <a href="{{ route('tournaments.index') }}" class="back-link">← Back to Tournaments</a>
        <h2>{{ $tournament->nama_tournament }}</h2>
        <span class="tier-badge">🏆 {{ $tournament->tier }}</span>
    </div>
    <div class="header-actions">
        <a href="{{ route('matches.index', $tournament) }}" class="btn-custom btn-primary">Matches</a>
        <a href="{{ route('leaderboard.show', $tournament) }}" class="btn-custom btn-primary">Leaderboard</a>
        <a href="{{ route('tournaments.edit', $tournament) }}" class="btn-custom btn-warning">Edit</a>
    </div>
</div>

<div class="card-grid">
    <div class="main-content">
        <div class="premium-card">
            <h3>📍 Tournament Overview</h3>
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Organizer</span>
                    <span class="info-value">{{ $tournament->penyelenggara }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Country / Region</span>
                    <span class="info-value">{{ $tournament->negara_penyelenggara }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Prize Pool</span>
                    <span class="info-value success">Rp {{ number_format($tournament->total_hadiah, 0, ',', '.') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Final Venue</span>
                    <span class="info-value">{{ $tournament->tempat_final }}</span>
                </div>
            </div>
            @if($tournament->deskripsi_tournament)
                <div class="divider">
                    <span class="info-label">Description</span>
                    <p class="text-muted">{{ $tournament->deskripsi_tournament }}</p>
                </div>
            @endif
        </div>

        <div class="premium-card">
            <h3>🎁 Rank Prizes</h3>
            @if($tournament->prizes->count() > 0)
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Rewards</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tournament->prizes->sortBy('rank') as $prize)
                            <tr>
                                <td class="rank-num">#{{ $prize->rank }}</td>
                                <td class="prize-amount">Rp {{ number_format($prize->prize_amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($tournament->status === 'completed')
                                        <span class="status-badge status-completed">✓ Distributed</span>
                                    @else
                                        <span class="text-muted">Pending Completion</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">No rewards configured for this tournament yet.</p>
            @endif
        </div>
    </div>

    <div class="sidebar">
        @if($tournament->status !== 'completed' && $tournament->prizes->count() > 0)
            <div class="status-alert {{ $matchReadiness['ready'] ? 'alert-ready' : 'alert-not-ready' }}">
                <h4>{{ $matchReadiness['ready'] ? '✅ Tournament Ready' : '⚠️ Matches Remaining' }}</h4>

                <div class="progress-info">
                    <div class="progress-stat">
                        <div class="progress-value">{{ $matchReadiness['completedMatches'] }}</div>
                        <div class="progress-label">Done</div>
                    </div>
                    <div class="progress-divider">/</div>
                    <div class="progress-stat">
                        <div class="progress-value">{{ $matchReadiness['requiredMatches'] }}</div>
                        <div class="progress-label">Goal</div>
                    </div>
                </div>

                @if(!$matchReadiness['ready'])
                    <p>{{ $matchReadiness['message'] }}</p>
                    @if(count($matchReadiness['missingPairs']) > 0)
                        <div>
                            <strong>Upcoming matches needed:</strong>
                            <ul>
                                @foreach($matchReadiness['missingPairs'] as $pair)
                                    <li>{{ $pair }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <button class="btn-custom btn-secondary" disabled>
                        Incomplete
                    </button>
                @else
                    <button class="btn-custom btn-primary" onclick="document.getElementById('tournamentModal').style.display='flex'">
                        Selesaikan Tournament
                    </button>
                @endif
            </div>
        @elseif($tournament->status === 'completed' && $tournament->winner)
            <div class="status-alert alert-ready champion-box">
                <div class="trophy">🏆</div>
                <h4>Tournament Champion</h4>
                <div class="team-title">{{ $tournament->winner->nama_tim }}</div>
                <div class="caption">Congratulations!</div>
            </div>
        @endif

        <div class="premium-card">
            <h3>👥 Teams</h3>
            <table class="premium-table">
                <tbody>
                    @foreach($tournament->slots->sortBy('slot_number') as $slot)
                        <tr>
                            <td class="rank-num">#{{ $slot->slot_number }}</td>
                            <td>
                                @if($slot->esportTeam)
                                    <a href="{{ route('teams.show', $slot->esportTeam) }}">{{ $slot->esportTeam->nama_tim }}</a>
                                @else
                                    <span class="text-muted">TBD</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Custom Modal --}}
<div id="tournamentModal" class="modal-overlay">
    <div class="premium-modal">
        <h3>🏁 Tournament Final Phase</h3>
        <p class="text-muted">All regular matches are complete. You can now proceed to the Grand Final or wrap up the tournament based on current standings.</p>

        <div class="modal-section">
            <form action="{{ route('tournaments.grandFinal', $tournament) }}" method="POST">
                @csrf
                <h4>Option 1: Grand Final</h4>
                <div class="bo-options">
                    @foreach(['BO3', 'BO5', 'BO7'] as $bo)
                        <label>
                            <input type="radio" name="bo_format" value="{{ $bo }}" {{ $bo == 'BO5' ? 'checked' : '' }}> {{ $bo }}
                        </label>
                    @endforeach
                </div>
                <button type="submit" class="btn-custom btn-primary">
                    🏆 Start Grand Final (Top 2 Teams)
                </button>
            </form>
        </div>

        <div class="modal-section">
            <form action="{{ route('tournaments.complete', $tournament) }}" method="POST" onsubmit="return confirm('Sudahi tournament sesuai klasemen saat ini?');">
                @csrf
                <h4>Option 2: Direct Finish</h4>
                <button type="submit" class="btn-custom btn-warning">
                    📊 Finish via Standings
                </button>
            </form>
        </div>

        <button class="btn-custom btn-secondary" onclick="document.getElementById('tournamentModal').style.display='none'">
            Dismiss
        </button>
    </div>
</div>
@endsection