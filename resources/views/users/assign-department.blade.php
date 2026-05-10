@extends('layouts.app')

@section('page_title', 'Assign Department - ' . $user->name)

@section('content')
<x-container>
    <x-page-header 
        title="Assign Department to User"
        subtitle="User: {{ $user->name }} | Role: {{ ucfirst($user->role) }}"
    />

    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">User Information</h2>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Role</p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Current Department</p>
                    <p class="text-gray-900 font-medium">{{ $user->department?->name ?? 'Not assigned' }}</p>
                </div>
            </div>

            @if (!in_array($user->role, ['teacher', 'hod']))
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <strong>ℹ️ Note:</strong> Department assignment is primarily for Teachers and HODs. Other roles may not require department assignment.
                    </p>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('users.assign-department', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">Select Department</label>
                    <select 
                        id="department_id" 
                        name="department_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('department_id') border-red-500 @enderror"
                        required
                    >
                        <option value="">-- Select a Department --</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected(old('department_id', $user->department_id) == $department->id)>
                                {{ $department->name }} ({{ $department->users()->where('role', 'teacher')->count() }} teachers)
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Add any notes about this department assignment..."
                    >{{ old('notes') }}</textarea>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Department Details</h3>
                    <div id="department-info" class="text-sm text-gray-600">
                        <p>Select a department to see its details</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium"
                    >
                        Assign Department
                    </button>
                    <a 
                        href="{{ route('users.show', $user) }}" 
                        class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('department_id').addEventListener('change', function() {
            const departmentId = this.value;
            const infoDiv = document.getElementById('department-info');
            
            if (!departmentId) {
                infoDiv.innerHTML = '<p>Select a department to see its details</p>';
                return;
            }

            // Get department option text for display
            const selectedOption = this.options[this.selectedIndex];
            const deptName = selectedOption.textContent.split('(')[0].trim();
            
            infoDiv.innerHTML = `
                <p><strong>${deptName}</strong> selected</p>
                <p class="text-xs text-gray-500 mt-1">This user will be associated with the selected department.</p>
            `;
        });
    </script>
</x-container>
@endsection
