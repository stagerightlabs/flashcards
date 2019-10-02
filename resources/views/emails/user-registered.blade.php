@component('mail::message')
# New User

A new user registration has occured: {{ $user->email }}.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
