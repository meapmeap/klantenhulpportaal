<h1>Er is een nieuwe reactie op je ticket</h1>

<p>Beste {{ $ticket->user->voornaam }},</p>

<p>Er is een nieuwe reactie geplaatst op je ticket: {{ $ticket->titel }}</p>

<p>{{ $reactie->user->voornaam }} {{ $reactie->user->achternaam }} schreef:</p>

<blockquote>
    {{ $reactie->bericht }}
</blockquote>

<p>Log in op het klantenhulpportaal om je ticket te bekijken.</p>

<p>Met vriendelijke groet,<br>
Het klantenhulpportaal</p>