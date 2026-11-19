@if(!in_array($player->id, $taken))
    <option value="{{ $player->id }}" {{ $selected ? 'selected' : '' }}>{{ $label }}</option>
@endif