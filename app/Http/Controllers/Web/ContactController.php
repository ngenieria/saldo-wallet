<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $userToAdd = User::where('email', $request->email)->first();

        if ($userToAdd->id === Auth::id()) {
            return back()->withErrors(['email' => 'You cannot add yourself as a contact.']);
        }

        // Check if already exists
        if (Contact::where('user_id', Auth::id())->where('contact_user_id', $userToAdd->id)->exists()) {
            return back()->withErrors(['email' => 'Contact already exists.']);
        }

        Contact::create([
            'user_id' => Auth::id(),
            'contact_user_id' => $userToAdd->id,
            'nickname' => $userToAdd->name, // Default to user's name
        ]);

        return back()->with('success', 'Contact added successfully!');
    }
}
