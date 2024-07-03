<x-app-layout :title=$title>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Note') }}
        </h2>
    </x-slot>


    <div class="max-w-2xl bg-gray-800 rounded-lg mt-6 mx-auto sm:px-6 lg:px-8 p-4">
        @session('success')
        <div class="text-white bg-green-500 p-2 mb-4">
            <h3 class="text-lg font-bold">{{ session('success') }}</h3>
        </div>
        @endsession
        <div class="text-white">
            <h3 class="text-xl">{{ $note->title }}</h3>
            <p class="text-sm mt-2 ">{{ $note->body }}</p>
            <div class="mt-6 flex justify-end gap-4">
                <x-primary-button type="a" href="{{ route('notes.edit', $note) }}">{{ __('Edit') }}</x-primary-button>
                <form action="{{ route('notes.destroy', $note) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <x-danger-button onclick="return confirm('Are you sure to delete this note?'">{{__('Delete') }}
                    </x-danger-button>

                </form>
            </div>
        </div>
    </div>

</x-app-layout>