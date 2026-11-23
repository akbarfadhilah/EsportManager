@extends('layouts.app')

@section('title', 'Import atlet')

@section('content')
<div class="athlete-import-page">
    <div class="page-header import-page-header">
        <div>
            <div class="page-eyebrow">PERSONNEL / ATHLETES</div>
            <h2>Import dari Excel</h2>
            <p class="page-subtitle">Tambahkan beberapa profil atlet sekaligus dengan mencocokkan kolom file.</p>
        </div>
        <a href="{{ route('players.index') }}" class="btn btn-secondary">Kembali ke personel</a>
    </div>

    <nav class="import-steps" aria-label="Tahapan import">
        <div class="import-step is-active"><span>01</span><div><strong>Upload &amp; cocokkan</strong><small>Pilih file dan kolom</small></div></div>
        <div class="import-step"><span>02</span><div><strong>Preview</strong><small>Periksa data atlet</small></div></div>
        <div class="import-step"><span>03</span><div><strong>Simpan</strong><small>Masuk ke database</small></div></div>
    </nav>

    @if($importErrors)
        <div class="import-error-panel" role="alert">
            <strong>Data belum bisa dipreview.</strong>
            <p>Periksa isi file atau pemetaan kolom, lalu pilih file kembali.</p>
            <ul>@foreach($importErrors as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <section class="import-card">
        <div class="import-card-heading">
            <div>
                <span class="import-step-kicker">LANGKAH 1</span>
                <h3>Pilih file dan cocokkan kolom</h3>
                <p>File dibaca di browser. Data baru disimpan setelah Anda meninjau preview dan menekan Lanjutkan.</p>
            </div>
            <span class="import-file-badge">CSV · XLSX</span>
        </div>

        <div class="import-dropzone" data-import-dropzone>
            <input id="athlete-import-file" type="file" accept=".csv,.xlsx,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" hidden>
            <div class="import-drop-icon"><i class="fas fa-file-arrow-up"></i></div>
            <strong data-import-file-label>Tarik file ke sini</strong>
            <span>atau</span>
            <button type="button" class="btn btn-secondary btn-sm" data-select-import-file>Pilih file</button>
            <small>CSV atau Excel .xlsx · maksimal 5 MB · hingga 500 baris</small>
        </div>
        <p class="import-status" data-import-status aria-live="polite"></p>

        <form action="{{ route('players.import.preview') }}" method="POST" class="import-mapping" data-import-form hidden>
            @csrf
            <div class="mapping-header">
                <div>
                    <h3>Pemetaan kolom</h3>
                    <p>Pilih kolom sumber yang sesuai. Sistem mencoba mengenali judul kolom secara otomatis.</p>
                </div>
                <span class="import-row-count" data-import-row-count></span>
            </div>
            <div class="mapping-grid" data-mapping-grid></div>
            <textarea name="headers" data-import-headers hidden></textarea>
            <textarea name="rows" data-import-rows hidden></textarea>
            <textarea name="mapping" data-import-mapping hidden></textarea>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" data-preview-submit disabled>Lihat preview</button>
                <a href="{{ route('players.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </section>
</div>
@vite('resources/js/player-import.js')
<script>
    window.playerImportFields = @json($fields);
</script>
@endsection
