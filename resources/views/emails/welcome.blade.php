Hola {{$user->name}}
gracias por crear una cuenta, por favor verificalá usando el siguiente enlace:

{{route('users.verify',$user->verification_token)}}
