@extends('layouts.app')

@section('title', 'Add Personnel')

@section('content')
<div class="form-container">
    <h2>Add New Personnel</h2>
    <p class="required-note">* Required fields</p>

    <form action="{{ route('players.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-grid">
            <div class="form-group full-width">
                <label for="photo">Photo (Optional)</label>
                <input type="file" name="photo" id="photo" accept="image/*">
                <p class="form-hint">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB.</p>
            </div>

            <div class="form-group">
                <label for="nama_asli">Real Name *</label>
                <input type="text" name="nama_asli" id="nama_asli" value="{{ old('nama_asli') }}" required>
            </div>

            <div class="form-group">
                <label for="nickname_game">Nickname / IGN *</label>
                <input type="text" name="nickname_game" id="nickname_game" value="{{ old('nickname_game') }}" required>
            </div>

            <div class="form-group">
                <label for="tanggal_lahir">Birth Date *</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
            </div>

            <div class="form-group">
                <label for="region_id">Region / Nationality</label>
                <select name="region_id" id="region_id">
                    <option value="">-- Select Region (Optional) --</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>
                            {{ $region->nama_negara }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group full-width">
                <label>Esports Team &amp; Jabatan (otomatis)</label>
                <div class="legend">
                    Keanggotaan tim esport &amp; jabatan <strong>diisi otomatis oleh sistem</strong> lewat tab Teams / Regions:
                    <br><span>• Dipilih di kolom <strong>Pemilik</strong> Team → <strong>Owner</strong></span>
                    <br><span>• Dipilih di kolom <strong>CEO</strong> Team → <strong>CEO</strong></span>
                    <br><span>• Dipilih di kolom <strong>Pelatih Utama/Kedua</strong> → <strong>Head Coach/Assistant Coach</strong></span>
                    <br><span>• Dipilih di kolom <strong>Manager/Analis</strong> → <strong>Manager/Analyst</strong></span>
                    <br><span>• Dipilih di kolom <strong>Player 1–10</strong> Team → <strong>Player</strong></span>
                    <br><span>• Dipilih di Region sebagai <strong>Ketua/Pelatih Timnas</strong></span>
                    <br><span class="badge badge-role">None</span> Personel baru berjabatan <strong>None</strong> &amp; Free Agent sampai ditempatkan di Tim / Region.
                </div>
            </div>

            <div class="form-group full-width">
                <label for="deskripsi_pemain">Biography / Description *</label>
                <textarea name="deskripsi_pemain" id="deskripsi_pemain" required>{{ old('deskripsi_pemain') }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Personnel</button>
            <a href="{{ route('players.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection