@extends('layouts.app')

@section('content')
<h1>Contacts</h1>

<nav>
    <ul>
        <li>
            <form action="{{ route('contacts.index') }}" method="GET">
                <input type="search" name="q" placeholder="Search" value="{{ $search }}"/>
            </form>
        </li>
        @if ($search)
        <li>
            <a href="{{ route('contacts.index') }}">Clear</a>
        </li>
        @endif
    </ul>
    <ul>
        <li><a href="{{ route('contacts.create') }}">Add contact</a></li>
    </ul>
</nav>


<hr>

<div class="overflow-auto">
    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($contacts as $contact)
                <tr>
                    <td>{{ $contact->email }}</td>
                    <td>{{ $contact->first_name }}</td>
                    <td>{{ $contact->last_name }}</td>
                    <td>
                        <a href="{{ route('contacts.edit', $contact) }}">Edit</a>
                    </td>
                    <td>
                        <form action="{{ route('contacts.destroy', $contact) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="secondary" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No contacts yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($contacts->hasPages())
    {{ $contacts->links() }}
@endif

@endsection
