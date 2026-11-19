@extends('layouts.app')

@section('title', isset($hero) ? 'Edit Hero' : 'Add Hero')

@section('content')
<div class="form-container">
    <h2>{{ isset($hero) ? 'Edit Hero: ' . $hero->nama_hero : 'Add New Hero' }}</h2>
    <p class="required-note">* Required fields</p>

    <form action="{{ isset($hero) ? route('heroes.update', $hero) : route('heroes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($hero))
            @method('PUT')
        @endif

        <div class="form-grid">
            <div class="form-group">
                <label for="nama_hero">Hero Name *</label>
                <input type="text" name="nama_hero" id="nama_hero" value="{{ old('nama_hero', $hero->nama_hero ?? '') }}" required>
            </div>

            @include('heroes._identity-fields', ['hero' => $hero ?? null])

            <div class="form-group full-width">
                <label for="deskripsi">Description</label>
                <textarea name="deskripsi" id="deskripsi">{{ old('deskripsi', $hero->deskripsi ?? '') }}</textarea>
            </div>

            <div class="form-group full-width">
                <label for="foto">Hero Photo</label>
                <input type="file" name="foto" id="foto" accept="image/*">
                @if(isset($hero) && $hero->foto)
                    <div class="form-current-image">
                        <img src="{{ asset('storage/' . $hero->foto) }}" alt="Current Photo">
                    </div>
                @endif
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">{{ isset($hero) ? 'Update Hero' : 'Save Hero' }}</button>
            <a href="{{ route('heroes.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection