@extends('layouts.app')

@section('title', 'Edit Team: ' . $team->nama_tim)

@section('content')
<div class="form-container wide">
    <h2>Edit Team: {{ $team->nama_tim }}</h2>
    <p class="required-note">* Required fields</p>

    @php $taken = $takenPlayerIds->all(); @endphp

    <form action="{{ route('teams.update', $team) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group full-width">
                <label for="nama_tim">Team Name *</label>
                <input type="text" name="nama_tim" id="nama_tim" value="{{ old('nama_tim', $team->nama_tim) }}" required>
            </div>

            <div class="form-group full-width">
                <label for="deskripsi_tim">Team Description *</label>
                <textarea name="deskripsi_tim" id="deskripsi_tim" required>{{ old('deskripsi_tim', $team->deskripsi_tim) }}</textarea>
            </div>

            <div class="form-group full-width">
                <label for="logo_esport">Team Logo (Optional)</label>
                @if($team->logo_esport)
                    <div class="form-current-image">
                        <img src="{{ asset('storage/' . $team->logo_esport) }}" alt="Current Logo">
                        <p class="form-hint">Current Logo</p>
                    </div>
                @endif
                <input type="file" name="logo_esport" id="logo_esport" accept="image/*">
            </div>

            <div class="form-group">
                <label for="region_id">Negara / Region Asal Tim</label>
                <select name="region_id" id="region_id">
                    <option value="">-- Select Region (Optional) --</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}" {{ old('region_id', $team->region_id) == $region->id ? 'selected' : '' }}>
                            {{ $region->nama_negara }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="tempat_kota_gh">Gaming House Location *</label>
                <input type="text" name="tempat_kota_gh" id="tempat_kota_gh" value="{{ old('tempat_kota_gh', $team->tempat_kota_gh) }}" required>
            </div>

            <div class="form-group full-width">
                <p class="help-text">Personnel sudah terdaftar di tab <strong>Personnel</strong>. Jabatan pemain <strong>diisi otomatis</strong> sesuai kolom yang dipilih (mis. dipilih di kolom Pemilik → jabatannya menjadi Owner). Setiap personnel hanya boleh menempati <strong>1 jabatan</strong> — begitu dipilih, personel itu <strong>langsung hilang (realtime)</strong> dari kolom pilihan lainnya.</p>
            </div>

            <div class="form-group">
                <label for="pemilik_id">Owner</label>
                <select name="pemilik_id" id="pemilik_id">
                    <option value="">-- Select Personnel --</option>
                    @foreach($players as $player)
                        @include('partials.player-option', [
                            'player' => $player,
                            'selected' => old('pemilik_id', $team->pemilik_id) == $player->id,
                            'label' => $player->nama_asli . ' (' . $player->jabatan . ')',
                        ])
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="ceo_id">CEO</label>
                <select name="ceo_id" id="ceo_id">
                    <option value="">-- Select Personnel --</option>
                    @foreach($players as $player)
                        @include('partials.player-option', [
                            'player' => $player,
                            'selected' => old('ceo_id', $team->ceo_id) == $player->id,
                            'label' => $player->nama_asli . ' (' . $player->jabatan . ')',
                        ])
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="pelatih_utama_id">Head Coach</label>
                <select name="pelatih_utama_id" id="pelatih_utama_id">
                    <option value="">-- Select Personnel --</option>
                    @foreach($players as $player)
                        @include('partials.player-option', [
                            'player' => $player,
                            'selected' => old('pelatih_utama_id', $team->pelatih_utama_id) == $player->id,
                            'label' => $player->nama_asli . ' (' . $player->jabatan . ')',
                        ])
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="pelatih_kedua_id">Assistant Coach</label>
                <select name="pelatih_kedua_id" id="pelatih_kedua_id">
                    <option value="">-- Select Personnel --</option>
                    @foreach($players as $player)
                        @include('partials.player-option', [
                            'player' => $player,
                            'selected' => old('pelatih_kedua_id', $team->pelatih_kedua_id) == $player->id,
                            'label' => $player->nama_asli . ' (' . $player->jabatan . ')',
                        ])
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="manager_id">Manager</label>
                <select name="manager_id" id="manager_id">
                    <option value="">-- Select Personnel --</option>
                    @foreach($players as $player)
                        @include('partials.player-option', [
                            'player' => $player,
                            'selected' => old('manager_id', $team->manager_id) == $player->id,
                            'label' => $player->nama_asli . ' (' . $player->jabatan . ')',
                        ])
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="analis_id">Analyst</label>
                <select name="analis_id" id="analis_id">
                    <option value="">-- Select Personnel --</option>
                    @foreach($players as $player)
                        @include('partials.player-option', [
                            'player' => $player,
                            'selected' => old('analis_id', $team->analis_id) == $player->id,
                            'label' => $player->nama_asli . ' (' . $player->jabatan . ')',
                        ])
                    @endforeach
                </select>
            </div>

            <div class="form-group full-width">
                <p class="help-text">Roster pemain aktif (maks 10, opsional). Pemain yang dipilih di sini otomatis berjabatan <strong>Player</strong>. Total Worth &amp; Penghargaan <strong>dihitung otomatis oleh sistem</strong> dari hasil turnamen.</p>
            </div>

            @php $rosterIds = old('players', $roster->pluck('id')->all()); @endphp
            @for($i = 1; $i <= $playerSlots; $i++)
            <div class="form-group">
                <label for="players_{{ $i }}">Player {{ $i }} (Optional)</label>
                <select name="players[]" id="players_{{ $i }}">
                    <option value="">-- Select Player --</option>
                    @foreach($players as $player)
                        @include('partials.player-option', [
                            'player' => $player,
                            'selected' => ($rosterIds[$i - 1] ?? null) == $player->id,
                            'label' => $player->nickname_game . ' (' . $player->nama_asli . ') - ' . $player->jabatan,
                        ])
                    @endforeach
                </select>
            </div>
            @endfor
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-warning">Update Team</button>
            <a href="{{ route('teams.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@include('partials.realtime-person-filter')
@endsection