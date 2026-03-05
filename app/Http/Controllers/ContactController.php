<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();
        $search = $request->input('q');

        if ($search) {
            $query->whereFullText(['email', 'first_name', 'last_name'], $search);
        }


        $contacts = $query->latest()->paginate(20)->withQueryString();
        return view('contacts.index', compact('contacts', 'search'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email:rfc|unique:contacts,email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        Contact::create($validated);

        return redirect()->route('contacts.index')->with('success', 'Contact created.');
    }

    public function show(Contact $contact)
    {
        return redirect()->route('contacts.edit', $contact);
    }

    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'email' => 'required|email:rfc|unique:contacts,email,' . $contact->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        $contact->update($validated);

        return redirect()->route('contacts.index')->with('success', 'Contact updated.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('contacts.index')->with('success', 'Contact deleted.');
    }
}
