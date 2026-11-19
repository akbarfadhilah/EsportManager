@extends('layouts.app')

@section('title', 'Edit Region')

@section('content')
<div class="form-container">
    <h2>Edit Region: {{ $region->nama_negara }}</h2>
    <p class="required-note">* Required fields</p>

    <form action="{{ route('regions.update', $region) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group full-width">
                <label for="nama_negara">Country Name *</label>
                <input type="text" name="nama_negara" id="nama_negara" value="{{ old('nama_negara', $region->nama_negara) }}" required>
            </div>

            <div class="form-group full-width">
                <label for="foto_negara">Country Flag/Photo</label>
                <input type="file" name="foto_negara" id="foto_negara" accept="image/*">
                @if($region->foto_negara)
                    <div class="form-current-image">
                        <img src="{{ asset('storage/' . $region->foto_negara) }}" alt="Current Photo">
                        <p class="form-hint">Current Photo</p>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="ketua_persatuan_game_id">Head of Esports Association</label>
                <select name="ketua_persatuan_game_id" id="ketua_persatuan_game_id">
                    <option value="">-- Select Person (Optional) --</option>
                    @foreach($ketuas as $player)
                        <option value="{{ $player->id }}" {{ old('ketua_persatuan_game_id', $region->ketua_persatuan_game_id) == $player->id ? 'selected' : '' }}>
                            {{ $player->nama_asli }} ({{ $player->nickname_game }})
                        </option>
                    @endforeach
                </select>
                <small class="form-hint">Dipilih di kolom ini → jabatan pemain otomatis menjadi "Ketua Persatuan Game".</small>
            </div>

            <div class="form-group">
                <label for="pelatih_timnas_id">National Team Coach</label>
                <select name="pelatih_timnas_id" id="pelatih_timnas_id">
                    <option value="">-- Select Person (Optional) --</option>
                    @foreach($pelatihTimnas as $player)
                        <option value="{{ $player->id }}" {{ old('pelatih_timnas_id', $region->pelatih_timnas_id) == $player->id ? 'selected' : '' }}>
                            {{ $player->nama_asli }} ({{ $player->nickname_game }})
                        </option>
                    @endforeach
                </select>
                <small class="form-hint">Dipilih di kolom ini → jabatan pemain otomatis menjadi "Pelatih Timnas".</small>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Region</button>
            <a href="{{ route('regions.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection