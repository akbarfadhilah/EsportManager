@extends('layouts.app')

@section('title', 'Tournaments')

@section('content')
<div class="page-header">
    <h2>Tournaments</h2>
    <a href="{{ route('tournaments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Tournament
    </a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th style="width: 70px;">Logo</th>
                <th>Tournament</th>
                <th>Tier</th>
                <th>Location</th>
                <th>Prize Pool</th>
                <th>Slots</th>
                <th>Winner</th>
                <th class="table-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tournaments as $tournament)
            <tr>
                <td>
                    @if($tournament->logo_tournament)
                        <img src="{{ asset('storage/' . $tournament->logo_tournament) }}" class="tournament-logo" alt="">
                    @else
                        <div class="tournament-logo">{{ substr($tournament->nama_tournament, 0, 1) }}</div>
                    @endif
                </td>
                <td>
                    <span class="tournament-name">{{ $tournament->nama_tournament }}</span>
                    <span class="organizer">by {{ $tournament->penyelenggara }}</span>
                </td>
                <td><span class="tier-badge">{{ $tournament->tier }}</span></td>
                <td>{{ $tournament->negara_penyelenggara }}</td>
                <td><strong>Rp {{ number_format($tournament->total_hadiah, 0, ',', '.') }}</strong></td>
                <td>{{ $tournament->jumlah_slot }} Teams</td>
                <td>
                    @if($tournament->status === 'completed' && $tournament->winner)
                        <div class="winner-box">🏆 {{ $tournament->winner->nama_tim }}</div>
                    @else
                        <span class="status-badge status-{{ $tournament->status ?? 'pending' }}">{{ ucfirst($tournament->status ?? 'Active') }}</span>
                    @endif
                </td>
                <td>
                    <div class="action-btns right">
                        <a href="{{ route('tournaments.show', $tournament) }}" class="btn-action btn-detail" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('tournaments.edit', $tournament) }}" class="btn-action btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('tournaments.destroy', $tournament) }}" method="POST" onsubmit="return confirm('Delete this tournament?')">
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
                <td colspan="8">
                    <div class="empty-state">
                        <div class="empty-icon">🏆</div>
                        No tournaments found. <a href="{{ route('tournaments.create') }}">Start one!</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection