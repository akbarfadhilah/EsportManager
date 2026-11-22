@extends('layouts.app')

@section('title', 'Personnel')

@section('content')
<div class="directory-page">
<div class="page-header">
    <div class="page-header-info">
        <div class="page-header-icon">👤</div>
        <div>
            <div class="page-eyebrow">DATABASE / PERSONNEL</div>
            <h2>Personnel</h2>
            <p class="page-subtitle">Athletes, coaches, managers &amp; staff</p>
        </div>
    </div>
    <button type="button" class="btn btn-primary" data-open-personnel-type>
        <i class="fas fa-plus"></i> Add Personnel
    </button>
</div>

<dialog class="personnel-type-dialog" data-personnel-type-dialog aria-labelledby="personnel-type-title" aria-describedby="personnel-type-description">
    <div class="personnel-type-dialog-content">
        <h2 id="personnel-type-title">Tambah personel apa dulu? 🤔</h2>
        <p id="personnel-type-description">
            Seseorang bisa ditambahkan sebagai Atlet esports atau manajemen biasa.
            Setiap orang yang ditambahkan sebagai "Common" bisa diubah menjadi Atlet, begitu pun sebaliknya.
        </p>
        <div class="personnel-type-options">
            <a href="{{ route('players.create', ['type' => 'common']) }}" class="btn btn-secondary">
                <i class="fas fa-user-tie"></i> Common
            </a>
            <a href="{{ route('players.create', ['type' => 'athlete']) }}" class="btn btn-primary">
                <i class="fas fa-gamepad"></i> Atlet
            </a>
        </div>
    </div>
</dialog>

<div class="stat-strip">
    <div class="directory-stat stat-violet">
        <div class="directory-stat-icon">👤</div>
        <div class="directory-stat-label">Total Personel</div>
        <div class="directory-stat-value">{{ $players->count() }}</div>
        <div class="directory-stat-note">terdaftar</div>
    </div>
    <div class="directory-stat stat-cyan">
        <div class="directory-stat-icon">🎮</div>
        <div class="directory-stat-label">Bertugas</div>
        <div class="directory-stat-value">{{ $players->filter(fn($p) => $p->jabatan !== 'None')->count() }}</div>
        <div class="directory-stat-note">di tim / region</div>
    </div>
    <div class="directory-stat stat-amber">
        <div class="directory-stat-icon">🆓</div>
        <div class="directory-stat-label">Free Agent</div>
        <div class="directory-stat-value">{{ $players->filter(fn($p) => $p->jabatan === 'None')->count() }}</div>
        <div class="directory-stat-note">belum berjabatan</div>
    </div>
</div>

<form action="{{ route('players.index') }}" method="GET">
    <div class="filter-row">
        <div class="filter-label"><i class="fas fa-filter"></i> Filter Region:</div>
        <select name="region_id" onchange="this.form.submit()" class="filter-select">
            <option value="">— Semua Negara —</option>
            @foreach($regions as $region)
                <option value="{{ $region->id }}" {{ (string) $selectedRegion === (string) $region->id ? 'selected' : '' }}>
                    {{ $region->nama_negara }}
                </option>
            @endforeach
        </select>
        @if($selectedRegion)
            <a href="{{ route('players.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i> Reset
            </a>
        @endif
    </div>
</form>

<div class="table-card">
    <div class="directory-toolbar">
        <div>
            <div class="directory-toolbar-title">Daftar Personel</div>
            <div class="directory-toolbar-sub">Atlet, pelatih, manajer &amp; staf</div>
        </div>
        <span class="directory-count">{{ $players->count() }} entri</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Identity</th>
                <th>Birth Date</th>
                <th>Jabatan</th>
                <th>Region</th>
                <th>Current Team</th>
                <th class="table-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($players as $player)
            <tr>
                <td>
                    <div class="player-identity">
                        @if(isset($player->photo) && $player->photo)
                            <img src="{{ asset('storage/' . $player->photo) }}" class="player-avatar" alt="">
                        @else
                            <div class="player-avatar-placeholder">{{ substr($player->nickname_game ?: $player->nama_asli, 0, 1) }}</div>
                        @endif
                        <div>
                            <span class="nickname">{{ $player->nickname_game ?: '—' }}</span>
                            <span class="real-name">{{ $player->nama_asli }}</span>
                            <span class="badge badge-outline" style="margin-top:2px;">{{ $player->kategori_personel }}</span>
                        </div>
                    </div>
                </td>
                <td>
                    <span style="font-size:0.9rem;">{{ $player->tanggal_lahir->format('d M Y') }}</span>
                </td>
                <td>
                    <span class="badge badge-role">{{ $player->jabatan }}</span>
                </td>
                <td class="cell-region">
                    @if($player->region)
                        @if($player->region->foto_negara)
                            <span class="region-pill">
                                <img src="{{ $player->region->foto_negara_url }}" class="region-flag" alt="{{ $player->region->nama_negara }}">
                                {{ $player->region->nama_negara }}
                            </span>
                        @else
                            <span class="badge badge-outline">{{ $player->region->nama_negara }}</span>
                        @endif
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    @php $ct = $player->current_team; @endphp
                    @if($ct['type'] === 'team')
                        <a class="team-chip" href="{{ route('teams.show', $ct['param']) }}">
                            @if($player->esportTeam->logo_esport)
                                <img src="{{ asset('storage/' . $player->esportTeam->logo_esport) }}" class="chip-logo" alt="">
                            @endif
                            <span>{{ $ct['label'] }}</span>
                        </a>
                    @elseif($ct['type'] === 'region')
                        <span class="badge badge-outline">🌍 <a href="{{ route('regions.index') }}">REGION</a></span>
                    @else
                        <span class="free-agent-badge">Free Agent</span>
                    @endif
                </td>
                <td>
                    <div class="action-btns right">
                        <a href="{{ route('players.show', $player) }}" class="btn-action btn-detail" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('players.edit', $player) }}" class="btn-action btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('players.destroy', $player) }}" method="POST" onsubmit="return confirm('Delete this personnel?')">
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
                <td colspan="6">
                    <div class="empty-state">
                        <div class="empty-icon">👤</div>
                        <p>Belum ada personel terdaftar.</p>
                        <button type="button" class="btn btn-primary btn-sm" data-open-personnel-type>
                            <i class="fas fa-plus"></i> Tambah Pertama
                        </button>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>

<script>
    (() => {
        const dialog = document.querySelector('[data-personnel-type-dialog]');
        if (!dialog) return;

        const openDialog = () => {
            dialog.classList.remove('is-closing');
            dialog.showModal();
        };

        const closeDialog = () => {
            if (!dialog.open || dialog.classList.contains('is-closing')) return;
            dialog.classList.add('is-closing');
        };

        document.querySelectorAll('[data-open-personnel-type]').forEach((button) => {
            button.addEventListener('click', openDialog);
        });

        // Tutup setelah animasi bounce-out selesai.
        dialog.addEventListener('animationend', (event) => {
            if (event.target === dialog && dialog.classList.contains('is-closing')) {
                dialog.classList.remove('is-closing');
                dialog.close();
            }
        });

        // Klik area backdrop untuk menutup modal.
        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) closeDialog();
        });

        // Escape juga menggunakan animasi bounce-out.
        dialog.addEventListener('cancel', (event) => {
            event.preventDefault();
            closeDialog();
        });
    })();
</script>
@endsection
