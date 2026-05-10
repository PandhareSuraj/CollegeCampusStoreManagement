<a href="{{ $href }}" class="inline-flex items-center px-4 py-2 rounded-lg font-medium transition
    @switch($variant)
        @case('primary')
            bg-blue-600 text-white hover:bg-blue-700
            @break
        @case('secondary')
            bg-gray-200 text-gray-800 hover:bg-gray-300
            @break
        @case('danger')
            bg-red-600 text-white hover:bg-red-700
            @break
        @case('success')
            bg-green-600 text-white hover:bg-green-700
            @break
        @default
            bg-blue-600 text-white hover:bg-blue-700
    @endswitch
">
    {{ $label }}
</a>
