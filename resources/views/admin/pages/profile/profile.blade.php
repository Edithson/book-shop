@extends('admin.index')

@section('content')
    {{-- @include('admin.pages.profile.update-profile-information-form') --}}
    @include('admin.pages.profile.update-password-form')
    @include('admin.pages.profile.delete-user-form')
@endsection
