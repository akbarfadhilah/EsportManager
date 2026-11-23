@extends('layouts.app')

@section('title', 'Preview import atlet')

@section('content')
<div class="athlete-import-page">
    <div class="page-header import-page-header">
        <div>
            <div class="page-eyebrow">PERSONNEL / ATHLETES</div>
            <h2>Periksa data sebelum disimpan</h2>
            <p class="page-subtitle">Pastikan seluruh kolom sudah benar. Belum ada data yang ditambahkan ke database.</p>
        </div>
        <span class="import-row-count">{{ count($players) }} atlet siap diimpor</span>
    </div>

    <nav class="import-steps" aria-label="Tahapan import">
        <div class="import-step is-complete"><span><i class="fas fa-check"></i></span><div><strong>Upload &amp; cocokkan</strong><small>Kolom telah dipetakan</small></div></div>
        <div class="import-step is-active"><span>02</span><div><strong>Preview</strong><small>Periksa data atlet</small></div></div>
        <div class="import-step"><span>03</span><div><strong>Simpan</strong><small>Masuk ke database</small></div></div>
    </nav>

    <section class="import-card preview-card">
        <div class="import-card-heading">
            <div>
                <span class="import-step-kicker">LANGKAH 2</span>
                <h3>{{ count($players) }} baris akan ditambahkan</h3>
                <p>Role kurang kompeten akan dihitung otomatis dari role utama dan role cadangan.</p>
            </div>
        </div>

        <div class="preview-table-wrap">
            <table class="import-preview-table">
                <thead><tr><th>No.</th>@foreach($fields as $field => $label)<th>{{ $label }}</th>@endforeach</tr></thead>
                <tbody>
                    @foreach($players as $index => $player)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            @foreach($fields as $field => $label)
                                <td>
                                    @switch($field)
                                        @case('tanggal_lahir') {{ \Carbon\Carbon::parse($player[$field])->format('d/m/Y') }} @break
                                        @case('region') {{ $regionNames[$player['region_id']] ?? '—' }} @break
                                        @case('role_cadangan_1') {{ $player['role_cadangan'][0] ?? '—' }} @break
                                        @case('role_cadangan_2') {{ $player['role_cadangan'][1] ?? '—' }} @break
                                        @case('gaya_permainan_1') {{ $player['gaya_permainan'][0] ?? '—' }} @break
                                        @case('gaya_permainan_2') {{ $player['gaya_permainan'][1] ?? '—' }} @break
                                        @case('targeting_urut_1') {{ $player['targeting_urut'][0] ?? '—' }} @break
                                        @case('targeting_urut_2') {{ $player['targeting_urut'][1] ?? '—' }} @break
                                        @case('targeting_urut_3') {{ $player['targeting_urut'][2] ?? '—' }} @break
                                        @default {{ $player[$field] !== '' ? $player[$field] : '—' }}
                                    @endswitch
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="import-preview-actions">
            <form action="{{ route('players.import.store') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Lanjutkan dan simpan</button>
            </form>
            <form action="{{ route('players.import.cancel') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary">Batal</button>
            </form>
        </div>
    </section>
</div>
@endsection
