<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index()
    {
        $data = DB::table('contact')->first();
        return view('backend.contact.index', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required',
            'content' => 'required'
        ], [
            'name.required' => 'Nama harus diisi',
            'content.required' => 'Konten harus diisi'
        ]);
        // Update model
        $contact->fill([
            'name' => $validated['name'],
            'content' => $validated['content']
        ]);
        $contact->save();

        session()->flash('success', 'Kontak berhasil diupdate.');
        return response()->json([
            'message' => 'Berhasil diupdate',
            'redirect' => route('kontak.index')
        ]);
    }
}
