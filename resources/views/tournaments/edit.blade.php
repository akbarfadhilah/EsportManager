@extends('layouts.app')

@section('title', 'Edit Tournament')

@section('content')
<div class="form-container wide">
    <h2>Edit Tournament: {{ $tournament->nama_tournament }}</h2>
    <p class="required-note">* Required fields</p>

    <form action="{{ route('tournaments.update', $tournament) }}" method="POST" enctype="multipart/form-data" id="tournamentForm">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group full-width">
                <label for="nama_tournament">Tournament Name *</label>
                <input type="text" name="nama_tournament" id="nama_tournament" value="{{ old('nama_tournament', $tournament->nama_tournament) }}" required>
            </div>

            <div class="form-group">
                <label for="penyelenggara">Organizer *</label>
                <input type="text" name="penyelenggara" id="penyelenggara" value="{{ old('penyelenggara', $tournament->penyelenggara) }}" required>
            </div>

            <div class="form-group">
                <label for="tier">Tier *</label>
                <select name="tier" id="tier" required>
                    <option value="">-- Select Tier --</option>
                    @foreach($tiers as $tier)
                        <option value="{{ $tier }}" {{ old('tier', $tournament->tier) == $tier ? 'selected' : '' }}>{{ $tier }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="negara_penyelenggara">Organizer Country *</label>
                <input type="text" name="negara_penyelenggara" id="negara_penyelenggara" value="{{ old('negara_penyelenggara', $tournament->negara_penyelenggara) }}" required>
            </div>

            <div class="form-group">
                <label for="format">Format *</label>
                <select name="format" id="format" required>
                    <option value="">-- Select Format --</option>
                    <option value="standard" {{ old('format', $tournament->format) == 'standard' ? 'selected' : '' }}>Format Standar (Round Robin 2 Pekan)</option>
                    <option value="knockout" {{ old('format', $tournament->format) == 'knockout' ? 'selected' : '' }}>Format Knockout (Gugur)</option>
                </select>
                <p class="form-hint">Standar: setiap tim saling bertemu 2x, jadwal otomatis untuk 2 pekan. Knockout: gugur tunggal, dimainkan harian.</p>
            </div>

            <div class="form-group">
                <label for="bo_format">Best of Format *</label>
                <select name="bo_format" id="bo_format" required>
                    <option value="">-- Select BO --</option>
                    @foreach(['BO3', 'BO5', 'BO7'] as $bo)
                        <option value="{{ $bo }}" {{ old('bo_format', $tournament->bo_format) == $bo ? 'selected' : '' }}>{{ $bo }}</option>
                    @endforeach
                </select>
                <p class="form-hint">Berlaku untuk semua pertandingan di turnamen ini.</p>
            </div>

            <div class="form-group">
                <label for="tempat_final">Final Venue *</label>
                <input type="text" name="tempat_final" id="tempat_final" value="{{ old('tempat_final', $tournament->tempat_final) }}" required>
            </div>

            <div class="form-group full-width">
                <label>Player Awards (Optional)</label>
                <p class="help-text">Add awards for individual players in this tournament</p>
                <button type="button" onclick="addAward()" class="btn btn-secondary btn-sm">+ Add Award</button>
                <div id="awardsContainer" class="slots-container">
                    @foreach($tournament->awards as $award)
                    <div class="slot-item award-row">
                        <select name="award_names[]">
                            <option value="">-- Select Award --</option>
                            <option value="MVP Finals" {{ $award->award_name == 'MVP Finals' ? 'selected' : '' }}>MVP Finals</option>
                            <option value="Top Player" {{ $award->award_name == 'Top Player' ? 'selected' : '' }}>Top Player</option>
                            <option value="Most Killed Player" {{ $award->award_name == 'Most Killed Player' ? 'selected' : '' }}>Most Killed Player</option>
                        </select>
                        <input type="number" name="award_prizes[]" placeholder="Prize (Rp)" value="{{ $award->prize_amount }}" step="0.01">
                        <button type="button" onclick="removeelement(this)" class="btn btn-danger btn-sm">Remove</button>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group full-width">
                <label for="deskripsi_tournament">Tournament Description *</label>
                <textarea name="deskripsi_tournament" id="deskripsi_tournament" required>{{ old('deskripsi_tournament', $tournament->deskripsi_tournament) }}</textarea>
            </div>

            <div class="form-group full-width">
                <label for="logo_tournament">Tournament Logo</label>
                <input type="file" name="logo_tournament" id="logo_tournament" accept="image/*">
                @if($tournament->logo_tournament)
                    <div class="form-current-image">
                        <img src="{{ asset('storage/' . $tournament->logo_tournament) }}" alt="Current logo">
                    </div>
                @endif
            </div>

            <div class="form-group full-width">
                <label>Participants & Prizes</label>
                <p class="help-text">Jumlah slot <strong>harus kelipatan 4</strong> (4, 8, 12, 16, 20, ...). Setiap baris = 1 Slot Tim + hadiah untuk peringkatnya (Row 1 = Slot 1 & Rank 1 Prize).</p>
                <div id="participantsContainer" class="slots-container">
                    @foreach($tournament->slots as $index => $slot)
                        @php $prize = $tournament->prizes->where('rank', $index + 1)->first(); @endphp
                        <div class="slot-item participant-row">
                            <label class="rank-label">Slot {{ $index + 1 }}</label>
                            <div>
                                <small>Team (Slot {{ $index + 1 }})</small>
                                <select name="slot_teams[]">
                                    <option value="">-- Select Team (Optional) --</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" {{ $slot->esport_team_id == $team->id ? 'selected' : '' }}>{{ $team->nama_tim }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <small>Prize (Rank {{ $index + 1 }})</small>
                                <input type="number" name="prize_amounts[]" placeholder="Rp" value="{{ $prize ? $prize->prize_amount : '' }}" step="0.01">
                                <input type="hidden" name="prize_ranks[]" value="{{ $index + 1 }}" class="rank-input">
                            </div>
                            <button type="button" onclick="removeParticipant(this)" class="btn btn-danger btn-sm">X</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addParticipant()" class="btn btn-secondary btn-block" style="border-style: dashed;">+ Add Slot</button>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Tournament</button>
            <a href="{{ route('tournaments.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
function addAward() {
    const container = document.getElementById('awardsContainer');
    const div = document.createElement('div');
    div.className = 'slot-item award-row';
    div.innerHTML = `
        <select name="award_names[]">
            <option value="">-- Select Award --</option>
            <option value="MVP Finals">MVP Finals</option>
            <option value="Top Player">Top Player</option>
            <option value="Most Killed Player">Most Killed Player</option>
        </select>
        <input type="number" name="award_prizes[]" placeholder="Prize (Rp)" step="0.01">
        <button type="button" onclick="removeelement(this)" class="btn btn-danger btn-sm">Remove</button>
    `;
    container.appendChild(div);
}

function removeelement(btn) {
    btn.parentElement.remove();
    updateIndices();
}

function addParticipant() {
    const container = document.getElementById('participantsContainer');
    const count = container.children.length + 1;
    const div = document.createElement('div');
    div.className = 'slot-item participant-row';

    let teamsOptions = '<option value="">-- Select Team (Optional) --</option>';
    const teams = @json($teams);
    teams.forEach(team => {
        teamsOptions += `<option value="${team.id}">${team.nama_tim}</option>`;
    });

    div.innerHTML = `
        <label class="rank-label">Slot ${count}</label>
        <div>
            <small>Team (Slot ${count})</small>
            <select name="slot_teams[]">${teamsOptions}</select>
        </div>
        <div>
            <small>Prize (Rank ${count})</small>
            <input type="number" name="prize_amounts[]" placeholder="Rp" step="0.01">
            <input type="hidden" name="prize_ranks[]" value="${count}" class="rank-input">
        </div>
        <button type="button" onclick="removeParticipant(this)" class="btn btn-danger btn-sm">X</button>
    `;
    container.appendChild(div);
    updateTeamOptions();
}

function removeParticipant(btn) {
    btn.parentElement.remove();
    updateIndices();
    updateTeamOptions();
}

function updateIndices() {
    const rows = document.querySelectorAll('.participant-row');
    rows.forEach((row, index) => {
        const num = index + 1;
        row.querySelector('.rank-label').textContent = `Slot ${num}`;
        row.querySelectorAll('small')[0].textContent = `Team (Slot ${num})`;
        row.querySelectorAll('small')[1].textContent = `Prize (Rank ${num})`;
        row.querySelector('.rank-input').value = num;
    });
}

function updateTeamOptions() {
    const selects = document.querySelectorAll('select[name="slot_teams[]"]');
    const selectedValues = Array.from(selects).map(select => select.value).filter(value => value !== '');

    selects.forEach(select => {
        const options = select.querySelectorAll('option');
        options.forEach(option => {
            if (option.value === '') return;
            option.disabled = selectedValues.includes(option.value) && option.value !== select.value;
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    updateIndices();
    updateTeamOptions();
});

document.addEventListener('change', function (e) {
    if (e.target.name === 'slot_teams[]') {
        updateTeamOptions();
    }
});
</script>
@endsection