@extends('layouts.app')

@section('content')
    <h1>Import Contacts</h1>

    <form action="{{ route('import.run') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label for="file">
            XML File
            <input type="file" name="file" id="file" required accept=".xml" {{ $errors->has('file') ? 'aria-invalid=true' : '' }}>
            @error('file')
                <small>{{ $message }}</small>
            @enderror
        </label>

        <button type="submit">Import Contacts</button>
    </form>
@endsection
