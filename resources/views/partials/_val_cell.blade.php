{{--
    _val_cell.blade.php
    Variables: $actual, $target, $valid (bool)
--}}
<div class="inline-flex flex-col items-center gap-0.5">
    <span class="font-semibold {{ $valid ? 'text-green-700' : 'text-red-600' }}">
        {{ $actual }}
    </span>
    <span class="text-gray-300 text-xs leading-none">/</span>
    <span class="text-xs text-gray-500">{{ $target }}</span>
</div>