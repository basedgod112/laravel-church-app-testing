<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sacramental Record Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium">{{ $record->user->name }} - {{ ucfirst($record->sacrament_type) }}</h3>
                        <p class="text-sm text-gray-500">Status: <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $record->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($record->status) }}</span></p>
                        <p class="text-sm text-gray-500">Date: {{ $record->sacrament_date ? $record->sacrament_date->format('M d, Y') : 'N/A' }}</p>
                        <p class="text-sm text-gray-500">Location: {{ $record->location }}</p>
                        <p class="text-sm text-gray-500">Officiant: {{ $record->officiant }}</p>
                        <p class="text-sm text-gray-500">Created: {{ $record->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-md font-medium">Notes</h4>
                        <p class="text-gray-700">{{ $record->notes ?: 'No notes available.' }}</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.records.edit', $record) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Record
                        </a>
                        <a href="{{ route('admin.records.index') }}" class="text-gray-600 hover:text-gray-900">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
