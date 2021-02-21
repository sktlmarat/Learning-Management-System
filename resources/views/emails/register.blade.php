@component('mail::message')

Dear {{ $data['name'] }},

This mail is sent to inform you that you were registered to Senior_LMS

Your credentials:<br>
Login: {{ $data['email'] }}<br>
Password: {{ $data['password'] }}<br>
@component('mail::button', ['url' => 'http://127.0.0.1:8000/login'])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
