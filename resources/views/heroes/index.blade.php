@extends('layouts.app')

@section('title', 'Heroes')

@section('content')
<div class="page-header">
    <div class="page-header-info">
        <h2>⚔️ Heroes</h2>
    </div>
    <a href="{{ route('heroes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Hero
    </a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Photo</th>
                <th>Hero</th>
                <th>Targeting</th>
                <th>Gaya Permainan</th>
                <th>Roles</th>
                <th>Description</th>
                <th>Used</th>
                <th class="table-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($heroes as $hero)
            <tr>
                <td>
                    @if($hero->foto)
                        <img src="{{ asset('storage/' . $hero->foto) }}" class="tournament-logo" alt="">
                    @else
                        <div class="tournament-logo">⚔️</div>
                    @endif
                </td>
                <td>
                    <span class="nickname">{{ $hero->nama_hero }}</span>
                </td>
                <td>
                    <span class="badge badge-role">{{ $hero->targeting ?? '-' }}</span>
                </td>
                <td>
                    <span class="badge badge-role">{{ $hero->gaya_permainan ?? '-' }}</span>
                </td>
                <td>
                    @if($hero->roles)
                        <div class="hero-role-badges">
                            @foreach($hero->roles as $role)
                                <span class="badge badge-outline">{{ $role }}</span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    <span class="real-name">{{ Str::limit($hero->deskripsi, 60) ?: 'Tanpa deskripsi' }}</span>
                </td>
                <td>
                    <span class="points-badge">{{ $hero->lineups_count }}x</span>
                </td>
                <td>
                    <div class="action-btns right">
                        <a href="{{ route('heroes.edit', $hero) }}" class="btn-action btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('heroes.destroy', $hero) }}" method="POST" onsubmit="return confirm('Delete this hero?')">
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
                        <div class="empty-icon">⚔️</div>
                        Belum ada hero. <a href="{{ route('heroes.create') }}">Tambahkan hero pertama!</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection