<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Mail\ContactReplyMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class ContactAdminController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(10);
        return view('admin.contacts.index', compact('contacts'));
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['is_read' => true]); 
        return view('admin.contacts.show', compact('contact'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate(['reply_message' => 'required|string|min:5']);
        $contact = Contact::findOrFail($id);

        try {
            Mail::to($contact->email)->send(new ContactReplyMail($contact, $request->reply_message));
            $contact->update(['reply' => $request->reply_message]);
            Session::flash('success', 'Réponse envoyée avec succès !');
        } catch (\Exception $e) {
            Session::flash('error', 'Erreur d\'envoi : ' . $e->getMessage());
        }

        Session::save();
        return redirect()->route('admin.contacts.index');
    }

    public function destroy($id)
    {
        Contact::findOrFail($id)->delete();
        Session::flash('success', 'Message supprimé.');
        Session::save();
        return redirect()->route('admin.contacts.index');
    }
}