<?php $selectedValue = (isset($selectedValue)) ? $selectedValue : null ?>
@for ($i = 5; $i <= 50; $i+=5)
    <option value="{{ $i }}"{{ ($i == $selectedValue) ? ' selected' : '' }}>{{ $i }} miles</option>
@endfor
<option value="">any distance</option>
