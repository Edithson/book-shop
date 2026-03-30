<x-mail::message>
# Nouveau message depuis Zérolib

**De :** {{ $contact->name }} ({{ $contact->email }})
**Motif :** {{ ucfirst($contact->motif) }}
**Sujet :** {{ $contact->sujet }}

<x-mail::panel>
{{ $contact->message }}
</x-mail::panel>

<x-mail::button :url="config('app.url') . '/admin/contacts/' . $contact->id">
Voir dans l'administration
</x-mail::button>

Merci,<br>
Le système Zérolib
</x-mail::message>
