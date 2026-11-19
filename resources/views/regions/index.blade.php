@extends('layouts.app')

@section('title', 'Regions')

@section('content')
<div class="page-header">
    <div class="page-header-info">
        <h2>🌍 Regions</h2>
    </div>
    <a href="{{ route('regions.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Region
    </a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th style="width: 80px;">Flag</th>
                <th>Country</th>
                <th>Head of Esports Assoc.</th>
                <th>National Team Coach</th>
                <th class="table-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($regions as $region)
            <tr>
                <td>
                    @if($region->foto_negara)
                        <img src="{{ $region->foto_negara_url }}" class="team-logo-small" alt="">
                    @else
                        <div class="team-logo-small">{{ substr($region->nama_negara, 0, 1) }}</div>
                    @endif
                </td>
                <td>
                    <span class="team-name">{{ $region->nama_negara }}</span>
                </td>
                <td>
                    @if($region->ketua)
                        <span class="badge badge-outline">👑 {{ $region->ketua->nama_asli ?? $region->ketua->nickname_game }}</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    @if($region->pelatihTimnas)
                        <span class="badge badge-role">📋 {{ $region->pelatihTimnas->nama_asli ?? $region->pelatihTimnas->nickname_game }}</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    <div class="action-btns right">
                        <a href="{{ route('regions.edit', $region) }}" class="btn-action btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('regions.destroy', $region) }}" method="POST" onsubmit="return confirm('Delete this region?')">
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
                <td colspan="5">
                    <div class="empty-state">
                        <div class="empty-icon">🌍</div>
                        No regions found. <a href="{{ route('regions.create') }}">Create one!</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection