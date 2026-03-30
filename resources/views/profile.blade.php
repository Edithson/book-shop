@extends('admin.index')

@section('content')

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="max-w-xl">
                <livewire:profile.update-profile-information-form />
            </div>

            <div class="max-w-xl">
                <livewire:profile.update-password-form />
            </div>

            <div class="max-w-xl">
                <livewire:profile.delete-user-form />
            </div>
        </div>
    </div>

@endsection
