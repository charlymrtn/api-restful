Hola {{$user->name}}
has cambiado tu correo, por favor verificaló usando el siguiente enlace:

{{route('users.verify',$user->verification_token)}}
