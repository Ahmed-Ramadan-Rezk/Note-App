<x-app-layout :title=$title>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Note') }}
        </h2>
    </x-slot>


    <div class="max-w-2xl bg-gray-800 rounded-lg mt-6 mx-auto sm:px-6 lg:px-8 p-4">
        <form method="post" action="{{ route('notes.store') }}" class="mt-6 space-y-6">
            @csrf

            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')"
                    required autofocus autocomplete="title" />
                <x-input-error class="mt-2" :messages="$errors->get('title')" />
            </div>

            <div>
                <x-input-label for="body" :value="__('Body')" />
                <x-textarea-input id="body" name="body" class="mt-1 block w-full" :value="old('body')" required>
                    {{ old('body') }}
                </x-textarea-input>
                <x-input-error class="mt-2" :messages="$errors->get('body')" />
            </div>


            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Create') }}</x-primary-button>


            </div>
        </form>
    </div>

</x-app-layout>