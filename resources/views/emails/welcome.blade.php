@component('mail::message')
Hola {{$user->name}}

Gracias por crear una cuenta, por favor verificalá usando el siguiente enlace:

@component('mail::button', ['url' => route('users.verify',$user->verification_token)])
Confirmar cuenta
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
