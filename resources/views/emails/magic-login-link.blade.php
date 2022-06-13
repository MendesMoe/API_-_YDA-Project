@component('mail::message')

    Bonjour,
    Your Daily Assistant vous invite a vous connecter a votre espace personnel.

  @component('mail::button', ['url' => $url])
    Connexion
  @endcomponent
@endcomponent
