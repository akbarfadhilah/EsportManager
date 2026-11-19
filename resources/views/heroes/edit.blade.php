@extends('layouts.app')

@section('title', 'Edit Hero')

@section('content')
<div class="form-container">
    <h2>Edit Hero: {{ $hero->nama_hero }}</h2>
    <p class="required-note">* Required fields</p>

    <form action="{{ route('heroes.update', $hero) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label for="nama_hero">Hero Name *</label>
                <input type="text" name="nama_hero" id="nama_hero" value="{{ old('nama_hero', $hero->nama_hero) }}" required>
            </div>

            @include('heroes._identity-fields', ['hero' => $hero])

            <div class="form-group full-width">
                <label for="deskripsi">Description</label>
                <textarea name="deskripsi" id="deskripsi">{{ old('deskripsi', $hero->deskripsi) }}</textarea>
            </div>

            <div class="form-group full-width">
                <label for="foto">Hero Photo</label>
                <input type="file" name="foto" id="foto" accept="image/*">
                @if($hero->foto)
                    <div class="form-current-image">
                        <img src="{{ asset('storage/' . $hero->foto) }}" alt="Current Photo">
                    </div>
                @endif
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Hero</button>
            <a href="{{ route('heroes.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection