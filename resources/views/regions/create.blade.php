@extends('layouts.app')

@section('title', isset($region) ? 'Edit Region' : 'Add Region')

@section('content')
<div class="form-container">
    <h2>{{ isset($region) ? 'Edit Region: ' . $region->nama_negara : 'Add New Region' }}</h2>
    <p class="required-note">* Required fields</p>

    @php $taken = $takenPlayerIds->all(); @endphp

    <form action="{{ isset($region) ? route('regions.update', $region) : route('regions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($region))
            @method('PUT')
        @endif

        <div class="form-grid">
            <div class="form-group full-width">
                <label for="nama_negara">Country Name *</label>
                <input type="text" name="nama_negara" id="nama_negara" value="{{ old('nama_negara', $region->nama_negara ?? '') }}" required>
            </div>

            <div class="form-group full-width">
                <label for="foto_negara">Country Flag/Photo</label>
                <input type="file" name="foto_negara" id="foto_negara" accept="image/*">
                @if(isset($region) && $region->foto_negara)
                    <div class="form-current-image">
                        <img src="{{ $region->foto_negara_url }}" alt="Current Photo">
                        <p class="form-hint">Current Photo</p>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="ketua_persatuan_game_id">Head of Esports Association</label>
                <select name="ketua_persatuan_game_id" id="ketua_persatuan_game_id">
                    <option value="">-- Select Person (Optional) --</option>
                    @foreach($ketuas as $player)
                        @include('partials.player-option', [
                            'player' => $player,
                            'selected' => old('ketua_persatuan_game_id', $region->ketua_persatuan_game_id ?? '') == $player->id,
                            'label' => $player->nama_asli . ' (' . $player->nickname_game . ')',
                        ])
                    @endforeach
                </select>
                <small class="form-hint">Dipilih di kolom ini → jabatan pemain otomatis menjadi "Ketua Persatuan Game".</small>
            </div>

            <div class="form-group">
                <label for="pelatih_timnas_id">National Team Coach</label>
                <select name="pelatih_timnas_id" id="pelatih_timnas_id">
                    <option value="">-- Select Person (Optional) --</option>
                    @foreach($pelatihTimnas as $player)
                        @include('partials.player-option', [
                            'player' => $player,
                            'selected' => old('pelatih_timnas_id', $region->pelatih_timnas_id ?? '') == $player->id,
                            'label' => $player->nama_asli . ' (' . $player->nickname_game . ')',
                        ])
                    @endforeach
                </select>
                <small class="form-hint">Dipilih di kolom ini → jabatan pemain otomatis menjadi "Pelatih Timnas".</small>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                {{ isset($region) ? 'Update Region' : 'Save Region' }}
            </button>
            <a href="{{ route('regions.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@include('partials.realtime-person-filter')
@endsection