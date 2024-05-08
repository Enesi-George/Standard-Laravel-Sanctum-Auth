@component('mail::message')
# Verify Your Email

Hello {{ $user->first_name }},

Thank you for registering with us. Please click the button below to verify your email address:

@component('mail::button', ['url' => $emailVerificationLink])
Verify Email
@endcomponent

If you did not register, you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
