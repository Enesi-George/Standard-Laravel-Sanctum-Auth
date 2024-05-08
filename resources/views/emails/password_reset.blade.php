@component('mail::message')
# Password Reset Link

Hello {{ $user->first_name }},

You have requested to reset your password. Please click the button below to reset your password:

@component('mail::button', ['url' => $resetLink])
Reset Password
@endcomponent

If you did not request this password reset, you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
