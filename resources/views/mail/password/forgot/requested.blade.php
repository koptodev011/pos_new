<x-mail::message>
# Hello

You are receiving this email because you are requested password reset for your account. Use below code to reset your password

<x-mail::button :url="''">
{{ $token }}
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
