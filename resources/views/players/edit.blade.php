@extends('layouts.app')

@section('title', 'Edit Personnel')

@section('content')
<div class="form-container">
    <h2>Edit Personnel: {{ $player->nickname_game }}</h2>
    <p class="required-note">* Required fields</p>

    <form action="{{ route('players.update', $player) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group full-width">
                <label for="photo">Photo (Optional)</label>
                @if($player->photo)
                    <div class="form-current-image">
                        <img src="{{ asset('storage/' . $player->photo) }}" alt="Current Photo">
                        <p class="form-hint">Current Photo</p>
                    </div>
                @endif
                <input type="file" name="photo" id="photo" accept="image/*">
                <p class="form-hint">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB.</p>
            </div>

            <div class="form-group">
                <label for="nama_asli">Real Name *</label>
                <input type="text" name="nama_asli" id="nama_asli" value="{{ old('nama_asli', $player->nama_asli) }}" required>
            </div>

            <div class="form-group">
                <label for="nickname_game">Nickname / IGN *</label>
                <input type="text" name="nickname_game" id="nickname_game" value="{{ old('nickname_game', $player->nickname_game) }}" required>
            </div>

            <div class="form-group">
                <label for="tanggal_lahir">Birth Date *</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $player->tanggal_lahir->format('Y-m-d')) }}" required>
            </div>

            <div class="form-group">
                <label for="region_id">Region / Nationality</label>
                <select name="region_id" id="region_id">
                    <option value="">-- Select Region (Optional) --</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}" {{ old('region_id', $player->region_id) == $region->id ? 'selected' : '' }}>
                            {{ $region->nama_negara }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group full-width">
                <label>Esports Team &amp; Jabatan (otomatis)</label>
                <div class="legend">
                    Jabatan saat ini: <span class="badge badge-role">{{ $player->jabatan }}</span>
                    <br>Keanggotaan tim &amp; jabatan <strong>tidak diubah dari form ini</strong> — sistem otomatis mengisinya sesuai posisi di Team/Region.
                    Personel tanpa penempatan akan berjabatan <strong>None</strong> &amp; Free Agent.
                </div>
            </div>

            <div class="form-group full-width">
                <label for="deskripsi_pemain">Biography / Description *</label>
                <textarea name="deskripsi_pemain" id="deskripsi_pemain" required>{{ old('deskripsi_pemain', $player->deskripsi_pemain) }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Personnel</button>
            <a href="{{ route('players.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection