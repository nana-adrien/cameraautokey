<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }

    public function professionals(): View
    {
        return view('pages.professionals');
    }

    public function becomeTechnician(): View
    {
        return view('pages.become-technician');
    }

    public function faq(): View
    {
        return view('pages.faq', ['faqs' => Faq::where('is_published', true)->orderBy('position')->get()]);
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function legal(): View
    {
        return view('pages.legal');
    }

    public function terms(): View
    {
        return view('pages.terms');
    }

    public function subscribe(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email', 'max:150']]);

        ContactMessage::firstOrCreate(
            ['type' => 'newsletter', 'email' => $data['email']],
            ['name' => 'Abonné newsletter', 'message' => 'Inscription à la newsletter depuis le pied de page.'],
        );

        return back()->with('newsletter', 'Merci, vous êtes inscrit(e) !');
    }

    public function sendMessage(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(ContactMessage::TYPES))],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'company' => ['nullable', 'string', 'max:100'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        ContactMessage::create($data);

        return back()->with('success', 'Merci ! Votre message a bien été envoyé. Nous vous répondons rapidement.');
    }
}
