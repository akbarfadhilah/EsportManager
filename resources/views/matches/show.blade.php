@extends('layouts.app')

@section('title', 'Match #' . $match->match_number)

@section('content')
<div class="match-header">
    <div class="match-header-info">
        <a href="{{ route('matches.index', $match->tournament) }}" class="back-link">← Back to Matches</a>
        <h2>Match #{{ $match->match_number }}</h2>
        <span class="format-badge">{{ $match->bo_format }}</span>
        <span class="status-badge status-{{ $match->status }}">
            {{ ucfirst(str_replace('_', ' ', $match->status)) }}
        </span>
        @if($match->is_grand_final)
            <span class="badge badge-gold">🏆 Grand Final</span>
        @endif
    </div>
</div>

@if($team1 && $team2)
<div class="teams-display">
    <div class="team-box {{ $match->winner_team_id == $team1->esport_team_id ? 'team-winner' : '' }}">
        <div class="team-name">{{ $team1->team->nama_tim ?? 'TBD' }}</div>
        <div class="team-score">{{ $team1->rounds_won }}</div>
        @if($match->winner_team_id == $team1->esport_team_id)
            <div class="winner-tag">🏆 Winner</div>
        @endif
    </div>
    <div class="vs">VS</div>
    <div class="team-box {{ $match->winner_team_id == $team2->esport_team_id ? 'team-winner' : '' }}">
        <div class="team-name">{{ $team2->team->nama_tim ?? 'TBD' }}</div>
        <div class="team-score">{{ $team2->rounds_won }}</div>
        @if($match->winner_team_id == $team2->esport_team_id)
            <div class="winner-tag">🏆 Winner</div>
        @endif
    </div>
</div>

@if($match->rounds->count() > 0)
<div class="info-box">
    <h3>Rounds History ({{ $match->rounds->count() }} / {{ $match->max_rounds }})</h3>
    <div class="rounds-full">
        @foreach($match->rounds as $round)
        <div class="round-block">
            <div class="round-head">
                <span class="round-tag">Round {{ $round->round_number }}</span>
                <span class="round-winner">🏆 {{ $round->winner->nama_tim }}</span>
                @if($round->mvp)
                    <span class="round-mvp">👤 MVP: {{ $round->mvp->nickname_game }}</span>
                @endif
            </div>
            @if($round->lineups->count() > 0)
                <div class="lineup-grid">
                    @foreach($round->lineups->groupBy('esport_team_id') as $teamId => $lineups)
                        @php $lt = $lineups->first()->team; @endphp
                        <div class="lineup-col">
                            <div class="lineup-team">{{ $lt->nama_tim ?? 'Team' }}</div>
                            @foreach($lineups as $lu)
                                <div class="lineup-player">
                                    <span class="lu-player">
                                        {{ $lu->player->nickname_game ?? '?' }}
                                        @if($round->mvp_player_id == $lu->player_id)
                                            <span class="mvp-star">⭐</span>
                                        @endif
                                    </span>
                                    <span class="lu-hero">{{ $lu->hero->nama_hero ?? '?' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@else
<div class="info-box">
    <h3>Rounds History (0 / {{ $match->max_rounds }})</h3>
    <p class="round-note">Belum ada ronde yang dimainkan.</p>
</div>
@endif

@if($match->status !== 'completed' && $playable)
<div class="info-box lineup-form-box">
    <h3>⚡ Play Round {{ $match->rounds()->count() + 1 }}</h3>
    <p class="round-note">
        🎲 Pilih <strong>5 pemain + 5 hero</strong> (unik, tidak boleh sama) untuk setiap tim.
        Pemenang dihitung otomatis oleh sistem berdasarkan gabungan <strong>winrate pemain</strong>,
        dan MVP dipilih acak dari tim pemenang.
    </p>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('matches.recordRound', $match) }}" method="POST" id="lineupForm">
        @csrf
        <div class="lineup-form-grid">
            @foreach([
                ['team' => $team1, 'players' => $team1Players, 'pfx' => 'team1'],
                ['team' => $team2, 'players' => $team2Players, 'pfx' => 'team2'],
            ] as $side)
            <div class="lineup-form-col">
                <div class="lineup-form-team">{{ $side['team']->team->nama_tim ?? 'Team' }}</div>

                <div class="lf-group">
                    <label>Pemain (5)</label>
                    @for($i = 1; $i <= 5; $i++)
                        <select name="{{ $side['pfx'] }}_players[]" class="player-select select-{{ $side['pfx'] }}" required>
                            <option value="">-- Pilih Pemain --</option>
                            @foreach($side['players'] as $p)
                                <option value="{{ $p->id }}" {{ old($side['pfx'] . '_players.' . ($i - 1)) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nickname_game }} (WR {{ number_format($p->winrate, 0) }}%)
                                </option>
                            @endforeach
                        </select>
                    @endfor
                </div>

                <div class="lf-group hero-pick-group" data-team="{{ $side['pfx'] }}">
                    <label>Hero (5, unik) — klik kotak hero di bawah</label>
                    <div class="hero-slot-dots" data-team="{{ $side['pfx'] }}">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="dot"></span>
                        @endfor
                    </div>
                    @for($i = 1; $i <= 5; $i++)
                        <input type="hidden" name="{{ $side['pfx'] }}_heroes[]" value="{{ old($side['pfx'] . '_heroes.' . ($i - 1)) ?? '' }}">
                    @endfor
                </div>

                @if($side['players']->count() < 5)
                    <p class="form-hint warn">Tim ini baru punya {{ $side['players']->count() }} pemain berjabatan Player. Tambahkan minimal 5 di tab Personil & Team.</p>
                @endif
            </div>
            @endforeach
        </div>

        <div class="hero-mode-toggle">
            <p class="round-note">Pilih <strong>Tim 1</strong> atau <strong>Tim 2</strong> dulu, lalu klik kotak hero untuk memilih. Hero untuk kedua tim unik (10 hero berbeda). Klik hero yang sudah terpilih untuk membatalkan.</p>
            <button type="button" class="mode-btn mode-t1 active" data-team="team1"><span class="swatch sw-t1"></span> Tim 1</button>
            <button type="button" class="mode-btn mode-t2" data-team="team2"><span class="swatch sw-t2"></span> Tim 2</button>
        </div>

        <div class="hero-pick-grid">
            @foreach($heroes as $h)
            <div class="hero-tile" data-hero="{{ $h->id }}">
                @php($heroMeta = array_filter(array_merge([$h->targeting, $h->gaya_permainan], $h->roles ?? [])))
                <span class="hero-tip">{{ $h->nama_hero }} @if($heroMeta)<em>({{ Str::limit(implode(' · ', $heroMeta), 64) }})</em>@endif</span>
                @if($h->foto)
                    <img src="{{ asset('storage/' . $h->foto) }}" alt="{{ $h->nama_hero }}" loading="lazy">
                @else
                    <span class="hero-placeholder">{{ mb_substr($h->nama_hero, 0, 1) }}</span>
                @endif
            </div>
            @endforeach
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
                🎮 Start Round {{ $match->rounds()->count() + 1 }}
            </button>
        </div>
    </form>
</div>
@elseif($match->status !== 'completed')
<div class="info-box">
    <h3>⏳ Menunggu Lawan</h3>
    <p class="round-note">Pertandingan ini belum siap dimainkan — salah satu timnya belum terkunci (format Knockout menunggu pemenang pertandingan sebelumnya).</p>
</div>
@endif

<div class="action-btns mt-1">
    <a href="{{ route('matches.index', $match->tournament) }}" class="btn btn-secondary">Back to Matches</a>
    <a href="{{ route('leaderboard.show', $match->tournament) }}" class="btn btn-secondary">View Leaderboard</a>
</div>
@else
<div class="info-box">
    <h3>⏳ Menunggu Peserta</h3>
    <p class="round-note">Pertandingan format Knockout ini menunggu pemenang pertandingan sebelumnya untuk mengisi slot tim.</p>
    <a href="{{ route('matches.index', $match->tournament) }}" class="btn btn-secondary">Back to Matches</a>
</div>
@endif

<script>
(function () {
    function initLineupUI() {
        var form = document.getElementById('lineupForm');
        if (!form) return;

        /* ---------- Player duplicate prevention ---------- */
        function playersOf(team) {
            return Array.prototype.filter.call(
                form.querySelectorAll('.player-select.select-' + team),
                function (s) { return s.value; }
            );
        }

        form.querySelectorAll('.player-select').forEach(function (sel) {
            sel.addEventListener('change', function () {
                var used = new Set();
                var dupe = [];
                form.querySelectorAll('.player-select').forEach(function (s) {
                    if (!s.value) return;
                    if (used.has(s.value)) dupe.push(s);
                    used.add(s.value);
                });
                if (dupe.length && dupe.indexOf(sel) !== -1) {
                    alert('Setiap pemain hanya boleh diturunkan 1 kali dalam satu ronde!');
                    sel.value = '';
                }
            });
        });

        /* ---------- Hero picker (grid) ---------- */
        var teams = { team1: { inputs: [], dots: [] }, team2: { inputs: [], dots: [] } };
        ['team1', 'team2'].forEach(function (t) {
            form.querySelectorAll('input[name="' + t + '_heroes[]"]').forEach(function (i) {
                teams[t].inputs.push(i);
            });
            form.querySelectorAll('.hero-slot-dots[data-team="' + t + '"] .dot').forEach(function (d) {
                teams[t].dots.push(d);
            });
        });
        var tiles = form.querySelectorAll('.hero-tile');
        var modeBtns = document.querySelectorAll('.hero-mode-toggle .mode-btn');
        var activeTeam = 'team1';

        function picks() {
            return {
                team1: teams.team1.inputs.map(function (i) { return i.value; }).filter(Boolean),
                team2: teams.team2.inputs.map(function (i) { return i.value; }).filter(Boolean)
            };
        }

        function render() {
            var p = picks();
            ['team1', 'team2'].forEach(function (t) {
                teams[t].inputs.forEach(function (inp, idx) {
                    teams[t].dots[idx].classList.toggle('filled', !!inp.value);
                });
            });
            tiles.forEach(function (tl) {
                var id = tl.getAttribute('data-hero');
                tl.classList.toggle('picked-t1', p.team1.indexOf(id) !== -1);
                tl.classList.toggle('picked-t2', p.team2.indexOf(id) !== -1);
            });
        }

        function setTeam(t) {
            activeTeam = t;
            modeBtns.forEach(function (b) {
                b.classList.toggle('active', b.getAttribute('data-team') === t);
            });
        }

        function pickHero(id) {
            var p = picks();
            var own = p[activeTeam];
            var other = activeTeam === 'team1' ? p.team2 : p.team1;

            if (own.indexOf(id) !== -1) {
                var input = teams[activeTeam].inputs.find(function (i) { return i.value === id; });
                if (input) input.value = '';
                render();
                return;
            }
            if (other.indexOf(id) !== -1) {
                alert('Hero sudah dipakai Tim ' + (activeTeam === 'team1' ? '2' : '1') + ' — 10 hero harus unik per ronde!');
                return;
            }
            var empty = teams[activeTeam].inputs.find(function (i) { return !i.value; });
            if (!empty) {
                alert('Tim ' + (activeTeam === 'team1' ? '1' : '2') + ' sudah memilih 5 hero!');
                return;
            }
            empty.value = id;
            render();
        }

        tiles.forEach(function (tl) {
            tl.addEventListener('click', function () { pickHero(tl.getAttribute('data-hero')); });
        });
        modeBtns.forEach(function (b) {
            b.addEventListener('click', function () { setTeam(b.getAttribute('data-team')); });
        });

        /* ---------- Submit validation ---------- */
        form.addEventListener('submit', function (e) {
            var p = picks();
            if (playersOf('team1').length !== 5 || playersOf('team2').length !== 5) {
                e.preventDefault();
                alert('Setiap tim wajib memilih tepat 5 pemain!');
                return;
            }
            if (p.team1.length !== 5) {
                e.preventDefault();
                alert('Tim 1 wajib memilih 5 hero lewat kotak hero!');
                return;
            }
            if (p.team2.length !== 5) {
                e.preventDefault();
                alert('Tim 2 wajib memilih 5 hero lewat kotak hero!');
                return;
            }
        });

        setTeam('team1');
        render();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLineupUI);
    } else {
        initLineupUI();
    }
})();
</script>
@endsection