@extends('layouts.app')

@section('content')
    <h1>Import Report</h1>

    <article>
        <ul>
            <li>Total records: {{ $import['total'] }}</li>
            <li>Imported: {{ $import['imported'] }}</li>
            <li>Duplicates: {{ $import['duplicates'] }}</li>
            <li>Invalid: {{ $import['invalid'] }}</li>
            <li>Status: {{ $import['status'] }}</li>
            <li>Time taken: {{ $import['time'] }} seconds</li>
            <li>
                Errors:
                @if ($import['errors'])
                    <ul>
                        @foreach ($import['errors'] as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @else
                    None
                @endif
            </li>
        </ul>
    </article>

    <div role="group">
        <a href="{{ route('import.form') }}" role="button" class="secondary">Import more</a>
        <a href="{{ route('contacts.index') }}" role="button">Show contacts</a>
    </div>
@endsection
