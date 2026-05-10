<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
    @switch($status)
        @case('Pending')
            bg-yellow-100 text-yellow-800
            @break
        @case('HOD_Approved')
            bg-blue-100 text-blue-800
            @break
        @case('Principal_Approved')
            bg-indigo-100 text-indigo-800
            @break
        @case('Trust_Approved')
            bg-purple-100 text-purple-800
            @break
        @case('Sent_to_Provider')
            bg-orange-100 text-orange-800
            @break
        @case('Supplied')
            bg-green-100 text-green-800
            @break
        @case('Rejected')
            bg-red-100 text-red-800
            @break
        @default
            bg-gray-100 text-gray-800
    @endswitch
">
    {{ ucwords(str_replace('_', ' ', $status)) }}
</span>
