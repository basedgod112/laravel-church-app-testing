<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Announcement Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium">{{ $announcement->title }}</h3>
                        <p class="text-sm text-gray-500">Status: <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $announcement->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($announcement->status) }}</span></p>
                        <p class="text-sm text-gray-500">Publish Date: {{ $announcement->publish_date ? $announcement->publish_date->format('M d, Y H:i') : 'N/A' }}</p>
                        <p class="text-sm text-gray-500">Created: {{ $announcement->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-md font-medium">Content</h4>
                        <p class="text-gray-700">{{ $announcement->content }}</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Announcement
                        </a>
                        <a href="{{ route('admin.announcements.index') }}" class="text-gray-600 hover:text-gray-900">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
