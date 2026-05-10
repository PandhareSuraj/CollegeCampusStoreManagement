@if ($message = Session::get('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        <p class="font-medium">✓ Success</p>
        <p class="text-sm">{{ $message }}</p>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <p class="font-medium">✗ Error</p>
        <p class="text-sm">{{ $message }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <p class="font-medium">✗ Validation Errors</p>
        <ul class="text-sm mt-2 space-y-1">
            @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
