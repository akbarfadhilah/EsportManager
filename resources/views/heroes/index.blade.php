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
                <th>Gaya Serangan</th>
                <th>Jenis Serangan</th>
                <th>Area Serangan</th>
                <th>Pola Serangan</th>
                <th>Role</th>
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
                    <span class="badge badge-role">{{ $hero->jenis_serangan ?? '-' }}</span>
                </td>
                <td>
                    <span class="badge badge-role">{{ $hero->area_serangan ?? '-' }}</span>
                </td>
                <td>
                    <span class="badge badge-role">{{ $hero->pola_serangan ?? '-' }}</span>
                </td>
                <td>
                    <div class="hero-role-badges">
                        @if($hero->role_utama)
                            <span class="badge badge-outline">Utama: {{ $hero->role_utama }}</span>
                            @foreach($hero->role_pendukung ?? [] as $role)
                                <span class="badge badge-outline">Pendukung: {{ $role }}</span>
                            @endforeach
                            @foreach($hero->role_tidak_direkomendasikan ?? [] as $role)
                                <span class="badge badge-outline">Tidak direkomendasikan: {{ $role }}</span>
                            @endforeach
                        @elseif($hero->roles)
                            @foreach($hero->roles as $role)
                                <span class="badge badge-outline">{{ $role }}</span>
                            @endforeach
                        @endif
                        @if(! $hero->role_utama && ! $hero->roles)
                            <span class="text-muted">-</span>
                        @endif
                    </div>
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
                <td colspan="11">
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