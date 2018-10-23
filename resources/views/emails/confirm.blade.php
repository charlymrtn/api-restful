@component('mail::message')
Hola {{$user->name}}

Has cambiado tu correo, por favor verificaló usando el siguiente enlace:

@component('mail::button', ['url' => route('users.verify',$user->verification_token)])
Confirmar cuenta
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
