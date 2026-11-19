@php
    $selectedMainRole = old('role_utama', $player?->role_utama);
    $selectedReserveRoles = old('role_cadangan', $player?->role_cadangan ?? []);
    $selectedWeakRoles = old('role_kurang_kompeten', $player?->role_kurang_kompeten ?? []);
    $selectedGameplay = old('gaya_permainan', $player?->gaya_permainan ?? []);
    $selectedTargetingOrder = old('targeting_urut', $player?->targeting_urut ?? []);
    $selectedReserveRoles = is_array($selectedReserveRoles) ? $selectedReserveRoles : [];
    $selectedWeakRoles = is_array($selectedWeakRoles) ? $selectedWeakRoles : [];
    $selectedGameplay = is_array($selectedGameplay) ? $selectedGameplay : [];
    $selectedTargetingOrder = is_array($selectedTargetingOrder) ? $selectedTargetingOrder : [];
@endphp

<div class="form-group full-width athlete-fields">
    <h3>Data Atlet</h3>
    <fieldset class="form-group full-width athlete-fieldset">
        <legend>Role Utama *</legend>
        <div class="hero-choice-grid">
            @foreach(\App\Models\Player::ATHLETE_ROLE_OPTIONS as $role)
                <label class="hero-choice">
                    <input type="radio" name="role_utama" value="{{ $role }}" data-athlete-field
                        data-athlete-required @checked($selectedMainRole === $role)
                        @disabled($athleteFieldsDisabled)>
                    <span>{{ $role }}</span>
                </label>
            @endforeach
        </div>
        @error('role_utama') <p class="form-hint warn">{{ $message }}</p> @enderror
    </fieldset>

    <fieldset class="form-group full-width athlete-fieldset">
        <legend>Role Cadangan * <span class="hero-role-count"><span data-reserve-role-count>{{ count($selectedReserveRoles) }}</span>/2</span></legend>
        <div class="hero-choice-grid">
            @foreach(\App\Models\Player::ATHLETE_ROLE_OPTIONS as $role)
                <label class="hero-choice" data-reserve-role-label>
                    <input type="checkbox" name="role_cadangan[]" value="{{ $role }}" data-athlete-field
                        data-reserve-role @checked(in_array($role, $selectedReserveRoles, true))
                        @disabled($athleteFieldsDisabled)>
                    <span>{{ $role }}</span>
                </label>
            @endforeach
        </div>
        <p class="form-hint">Pilih 1 sampai 2 role selain Role Utama.</p>
        @error('role_cadangan') <p class="form-hint warn">{{ $message }}</p> @enderror
        @error('role_cadangan.*') <p class="form-hint warn">{{ $message }}</p> @enderror
    </fieldset>

    <fieldset class="form-group full-width athlete-fieldset">
        <legend>Role Kurang Kompeten (otomatis)</legend>
        <div class="hero-choice-grid">
            @foreach(\App\Models\Player::ATHLETE_ROLE_OPTIONS as $role)
                <label class="hero-choice">
                    <input type="checkbox" value="{{ $role }}" data-weak-role checked
                        disabled @checked(in_array($role, $selectedWeakRoles, true))>
                    <span>{{ $role }}</span>
                </label>
            @endforeach
        </div>
        <p class="form-hint">Otomatis berisi semua role yang tidak dipilih sebagai Role Utama atau Cadangan.</p>
    </fieldset>

    <fieldset class="form-group full-width athlete-fieldset">
        <legend>Gaya permainan * <span class="hero-role-count"><span data-gameplay-count>{{ count($selectedGameplay) }}</span>/2</span></legend>
        <div class="hero-choice-grid">
            @foreach(\App\Models\Player::ATHLETE_GAMEPLAY_OPTIONS as $style)
                <label class="hero-choice">
                    <input type="checkbox" name="gaya_permainan[]" value="{{ $style }}" data-athlete-field
                        data-gameplay-style @checked(in_array($style, $selectedGameplay, true))
                        @disabled($athleteFieldsDisabled)>
                    <span>{{ $style }}</span>
                </label>
            @endforeach
        </div>
        <p class="form-hint">Wajib memilih tepat 2 gaya permainan.</p>
        @error('gaya_permainan') <p class="form-hint warn">{{ $message }}</p> @enderror
        @error('gaya_permainan.*') <p class="form-hint warn">{{ $message }}</p> @enderror
    </fieldset>

    <fieldset class="form-group full-width athlete-fieldset">
        <legend>Gaya Targeting * (urutkan dari terbaik)</legend>
        <div class="targeting-rank-grid">
            @foreach(range(0, 2) as $rank)
                <div class="form-group">
                    <label for="targeting_urut_{{ $rank }}">Urutan {{ $rank + 1 }} *</label>
                    <select id="targeting_urut_{{ $rank }}" name="targeting_urut[]" data-athlete-field
                        data-athlete-required data-targeting-rank @disabled($athleteFieldsDisabled)>
                        <option value="">-- Pilih Jarak --</option>
                        @foreach(\App\Models\Player::TARGETING_ORDER_OPTIONS as $targeting)
                            <option value="{{ $targeting }}" @selected(($selectedTargetingOrder[$rank] ?? null) === $targeting)>
                                {{ $targeting }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endforeach
        </div>
        <p class="form-hint">Urutan 1 paling dikuasai, urutan 3 paling kurang dikuasai. Setiap pilihan hanya boleh dipakai sekali.</p>
        @error('targeting_urut') <p class="form-hint warn">{{ $message }}</p> @enderror
        @error('targeting_urut.*') <p class="form-hint warn">{{ $message }}</p> @enderror
    </fieldset>

    <p class="form-hint warn" data-athlete-validation hidden></p>
</div>

<script>
    (() => {
        const form = document.querySelector('[data-player-personnel-form]');
        if (!form) return;

        const statusInputs = Array.from(form.querySelectorAll('input[name="kategori_personel"][type="radio"]'));
        const hiddenStatus = form.querySelector('input[name="kategori_personel"][type="hidden"]');
        const athleteFields = Array.from(form.querySelectorAll('[data-athlete-field]'));
        const mainRoleInputs = Array.from(form.querySelectorAll('input[data-athlete-field][name="role_utama"]'));
        const reserveInputs = Array.from(form.querySelectorAll('[data-reserve-role]'));
        const weakRoleInputs = Array.from(form.querySelectorAll('[data-weak-role]'));
        const gameplayInputs = Array.from(form.querySelectorAll('[data-gameplay-style]'));
        const targetingSelects = Array.from(form.querySelectorAll('[data-targeting-rank]'));
        const validationMessage = form.querySelector('[data-athlete-validation]');

        const isAthlete = () => {
            const selectedStatus = statusInputs.find((input) => input.checked);
            return (selectedStatus?.value ?? hiddenStatus?.value) === 'Atlet';
        };

        const updateAthleteFields = () => {
            const athleteStatus = isAthlete();
            const fieldsEnabled = athleteStatus || form.dataset.hasAthleteData === 'true';

            athleteFields.forEach((field) => {
                field.disabled = !fieldsEnabled;
                field.required = athleteStatus && field.hasAttribute('data-athlete-required');
            });

            const selectedMainRole = mainRoleInputs.find((input) => input.checked)?.value;
            reserveInputs.forEach((input) => {
                if (input.value === selectedMainRole) input.checked = false;
            });

            const selectedReserveRoles = reserveInputs.filter((input) => input.checked).map((input) => input.value);
            reserveInputs.forEach((input) => {
                const isMainRole = input.value === selectedMainRole;
                input.closest('label').hidden = isMainRole;
                input.disabled = !fieldsEnabled || isMainRole || (selectedReserveRoles.length >= 2 && !input.checked);
            });

            const selectedGameplayCount = gameplayInputs.filter((input) => input.checked).length;
            gameplayInputs.forEach((input) => {
                input.disabled = !fieldsEnabled || (selectedGameplayCount >= 2 && !input.checked);
            });

            targetingSelects.forEach((select) => {
                const duplicate = targetingSelects.some((other) => other !== select && other.value && other.value === select.value);
                if (duplicate) select.value = '';
            });
            targetingSelects.forEach((select) => {
                const selectedElsewhere = targetingSelects
                    .filter((other) => other !== select)
                    .map((other) => other.value);
                Array.from(select.options).forEach((option) => {
                    option.disabled = Boolean(option.value && selectedElsewhere.includes(option.value));
                });
            });

            const selectedRoles = new Set([selectedMainRole, ...selectedReserveRoles].filter(Boolean));
            weakRoleInputs.forEach((input) => {
                input.checked = Boolean(selectedMainRole) && !selectedRoles.has(input.value);
            });

            form.querySelector('[data-reserve-role-count]').textContent = selectedReserveRoles.length;
            form.querySelector('[data-gameplay-count]').textContent = selectedGameplayCount;
            if (validationMessage) validationMessage.hidden = true;
        };

        [...statusInputs, ...athleteFields].forEach((input) => input.addEventListener('change', updateAthleteFields));

        form.addEventListener('submit', (event) => {
            if (!isAthlete()) return;

            const reserveCount = reserveInputs.filter((input) => input.checked).length;
            const gameplayCount = gameplayInputs.filter((input) => input.checked).length;
            if (reserveCount >= 1 && gameplayCount === 2) return;

            event.preventDefault();
            validationMessage.textContent = [
                reserveCount < 1 ? 'Pilih minimal 1 Role Cadangan.' : '',
                gameplayCount !== 2 ? 'Pilih tepat 2 gaya permainan.' : '',
            ].filter(Boolean).join(' ');
            validationMessage.hidden = false;
        });

        updateAthleteFields();
    })();
</script>
