@extends('layouts.app')

@section('content')
<h1>Create Contact</h1>

<form action="{{ route('contacts.store') }}" method="POST">
    @csrf

    <label for="email">
        Email
        <input type="email" name="email" id="email" required value="{{ old('email') }}" {{ $errors->has('email') ? 'aria-invalid=true' : '' }} autocomplete="email">
        @error('email')
            <small>{{ $message }}</small>
        @enderror
    </label>

    <label for="first_name">
        First Name
        <input type="text" name="first_name" id="first_name" required value="{{ old('first_name') }}" {{ $errors->has('first_name') ? 'aria-invalid=true' : '' }} autocomplete="given-name">
        @error('first_name')
            <small>{{ $message }}</small>
        @enderror
    </label>

    <label for="last_name">
        Last Name
        <input type="text" name="last_name" id="last_name" required value="{{ old('last_name') }}" {{ $errors->has('last_name') ? 'aria-invalid=true' : '' }} autocomplete="family-name">
        @error('last_name')
            <small>{{ $message }}</small>
        @enderror
    </label>

    <div role="group">
        <a href="{{ route('contacts.index') }}" role="button" class="secondary">Cancel</a>
        <button type="submit">Create Contact</button>
    </div>
</form>
@endsection
