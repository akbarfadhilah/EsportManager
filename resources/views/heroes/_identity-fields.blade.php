@php($selectedSupportRoles = old('role_pendukung', $hero?->role_pendukung ?? []))
@php($selectedNotRecommendedRoles = old('role_tidak_direkomendasikan', $hero?->role_tidak_direkomendasikan ?? []))

<fieldset class="form-group full-width hero-identity-fieldset">
    <legend>Targeting *</legend>
    <div class="hero-choice-grid">
        @foreach($targetingOptions as $option)
            <label class="hero-choice">
                <input type="radio" name="targeting" value="{{ $option }}" required @checked(old('targeting', $hero?->targeting) === $option)>
                <span>{{ $option }}</span>
            </label>
        @endforeach
    </div>
    @error('targeting') <p class="form-hint warn">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="form-group full-width hero-identity-fieldset">
    <legend>Gaya serangan *</legend>
    <div class="hero-choice-grid">
        @foreach($attackStyleOptions as $option)
            <label class="hero-choice">
                <input type="radio" name="gaya_permainan" value="{{ $option }}" required @checked(old('gaya_permainan', $hero?->gaya_permainan) === $option)>
                <span>{{ $option }}</span>
            </label>
        @endforeach
    </div>
    @error('gaya_permainan') <p class="form-hint warn">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="form-group full-width hero-identity-fieldset">
    <legend>Jenis Serangan *</legend>
    <div class="hero-choice-grid">
        @foreach($attackTypeOptions as $option)
            <label class="hero-choice">
                <input type="radio" name="jenis_serangan" value="{{ $option }}" required @checked(old('jenis_serangan', $hero?->jenis_serangan) === $option)>
                <span>{{ $option }}</span>
            </label>
        @endforeach
    </div>
    @error('jenis_serangan') <p class="form-hint warn">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="form-group full-width hero-identity-fieldset">
    <legend>Area Serangan *</legend>
    <div class="hero-choice-grid">
        @foreach($attackAreaOptions as $option)
            <label class="hero-choice">
                <input type="radio" name="area_serangan" value="{{ $option }}" required @checked(old('area_serangan', $hero?->area_serangan) === $option)>
                <span>{{ $option }}</span>
            </label>
        @endforeach
    </div>
    @error('area_serangan') <p class="form-hint warn">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="form-group full-width hero-identity-fieldset">
    <legend>Pola Serangan *</legend>
    <div class="hero-choice-grid">
        @foreach($attackPatternOptions as $option)
            <label class="hero-choice">
                <input type="radio" name="pola_serangan" value="{{ $option }}" required @checked(old('pola_serangan', $hero?->pola_serangan) === $option)>
                <span>{{ $option }}</span>
            </label>
        @endforeach
    </div>
    @error('pola_serangan') <p class="form-hint warn">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="form-group full-width hero-identity-fieldset" data-hero-role-group>
    <legend>Tahap 1. Pilih Role Utama *</legend>
    <div class="hero-choice-grid">
        @foreach($roleOptions as $option)
            <label class="hero-choice">
                <input type="radio" name="role_utama" value="{{ $option }}" required data-hero-main-role @checked(old('role_utama', $hero?->role_utama) === $option)>
                <span>{{ $option }}</span>
            </label>
        @endforeach
    </div>
    @error('role_utama') <p class="form-hint warn">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="form-group full-width hero-identity-fieldset" data-hero-role-group>
    <legend>Tahap 2. Role Pendukung <span class="hero-role-count"><span data-hero-support-count>{{ is_array($selectedSupportRoles) ? count($selectedSupportRoles) : 0 }}</span>/2</span></legend>
    <div class="hero-choice-grid">
        @foreach($roleOptions as $option)
            <label class="hero-choice">
                <input type="checkbox" name="role_pendukung[]" value="{{ $option }}" data-hero-support-role @checked(is_array($selectedSupportRoles) && in_array($option, $selectedSupportRoles, true))>
                <span>{{ $option }}</span>
            </label>
        @endforeach
    </div>
    <p class="form-hint">Boleh dikosongkan. Pilih maksimal 2 role selain Role Utama.</p>
    @error('role_pendukung') <p class="form-hint warn">{{ $message }}</p> @enderror
    @error('role_pendukung.*') <p class="form-hint warn">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="form-group full-width hero-identity-fieldset" data-hero-role-group>
    <legend>Tahap 3. Role yang tidak direkomendasikan * <span class="hero-role-count"><span data-hero-not-recommended-count>{{ is_array($selectedNotRecommendedRoles) ? count($selectedNotRecommendedRoles) : 0 }}</span>/4</span></legend>
    <div class="hero-choice-grid">
        @foreach($roleOptions as $option)
            <label class="hero-choice">
                <input type="checkbox" name="role_tidak_direkomendasikan[]" value="{{ $option }}" data-hero-not-recommended-role @checked(is_array($selectedNotRecommendedRoles) && in_array($option, $selectedNotRecommendedRoles, true))>
                <span>{{ $option }}</span>
            </label>
        @endforeach
    </div>
    <p class="form-hint">Wajib memilih minimal 1 role yang tersisa setelah Tahap 1 dan 2.</p>
    @error('role_tidak_direkomendasikan') <p class="form-hint warn">{{ $message }}</p> @enderror
    @error('role_tidak_direkomendasikan.*') <p class="form-hint warn">{{ $message }}</p> @enderror
</fieldset>

<script>
    (() => {
        const mainInputs = Array.from(document.querySelectorAll('[data-hero-main-role]'));
        const supportInputs = Array.from(document.querySelectorAll('[data-hero-support-role]'));
        const notRecommendedInputs = Array.from(document.querySelectorAll('[data-hero-not-recommended-role]'));
        const supportCount = document.querySelector('[data-hero-support-count]');
        const notRecommendedCount = document.querySelector('[data-hero-not-recommended-count]');

        const updateRoleChoices = () => {
            const mainRole = mainInputs.find((input) => input.checked)?.value;

            supportInputs.forEach((input) => {
                if (input.value === mainRole) {
                    input.checked = false;
                }
            });

            const selectedSupportRoles = supportInputs.filter((input) => input.checked).map((input) => input.value);
            notRecommendedInputs.forEach((input) => {
                if (input.value === mainRole || selectedSupportRoles.includes(input.value)) {
                    input.checked = false;
                }
            });

            const selectedNotRecommendedCount = notRecommendedInputs.filter((input) => input.checked).length;

            supportInputs.forEach((input) => {
                const isMainRole = input.value === mainRole;
                input.closest('.hero-choice').hidden = isMainRole;
                input.disabled = isMainRole || (selectedSupportRoles.length >= 2 && !input.checked);
            });
            notRecommendedInputs.forEach((input) => {
                const alreadySelected = input.value === mainRole || selectedSupportRoles.includes(input.value);
                input.closest('.hero-choice').hidden = alreadySelected;
                input.disabled = alreadySelected || (selectedNotRecommendedCount >= 4 && !input.checked);
            });

            supportCount.textContent = selectedSupportRoles.length;
            notRecommendedCount.textContent = selectedNotRecommendedCount;
        };

        [...mainInputs, ...supportInputs, ...notRecommendedInputs]
            .forEach((input) => input.addEventListener('change', updateRoleChoices));
        updateRoleChoices();
    })();
</script>
