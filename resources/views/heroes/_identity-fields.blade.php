@php($selectedRoles = old('roles', $hero?->roles ?? []))

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
    <legend>Gaya Permainan *</legend>
    <div class="hero-choice-grid">
        @foreach($gameplayOptions as $option)
            <label class="hero-choice">
                <input type="radio" name="gaya_permainan" value="{{ $option }}" required @checked(old('gaya_permainan', $hero?->gaya_permainan) === $option)>
                <span>{{ $option }}</span>
            </label>
        @endforeach
    </div>
    @error('gaya_permainan') <p class="form-hint warn">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="form-group full-width hero-identity-fieldset" data-hero-role-group>
    <legend>Role * <span class="hero-role-count"><span data-hero-role-count>{{ is_array($selectedRoles) ? count($selectedRoles) : 0 }}</span>/3</span></legend>
    <div class="hero-choice-grid">
        @foreach($roleOptions as $option)
            <label class="hero-choice">
                <input type="checkbox" name="roles[]" value="{{ $option }}" data-hero-role @checked(is_array($selectedRoles) && in_array($option, $selectedRoles, true))>
                <span>{{ $option }}</span>
            </label>
        @endforeach
    </div>
    <p class="form-hint">Pilih 1 sampai 3 role.</p>
    @error('roles') <p class="form-hint warn">{{ $message }}</p> @enderror
    @error('roles.*') <p class="form-hint warn">{{ $message }}</p> @enderror
</fieldset>

<script>
    (() => {
        const roleInputs = Array.from(document.querySelectorAll('[data-hero-role]'));
        const roleCount = document.querySelector('[data-hero-role-count]');

        const updateRoleChoices = () => {
            const selectedCount = roleInputs.filter((input) => input.checked).length;

            roleInputs.forEach((input) => {
                input.disabled = selectedCount >= 3 && !input.checked;
            });

            roleCount.textContent = selectedCount;
        };

        roleInputs.forEach((input) => input.addEventListener('change', updateRoleChoices));
        updateRoleChoices();
    })();
</script>
