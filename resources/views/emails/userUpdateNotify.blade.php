@component('mail::message')

    <h2>Hi, {{ $user_name }}</h2>
    <h2>{{ config('app.name') }} - Admin Updated Your Information, Please login</h2>

   <h3>Email: {{ $user_email }}</h3>
   <h3>Role: {{ \Illuminate\Support\Str::title($user_role) }}</h3>
    @component('mail::button', ['url' =>  route($url) ])
        Login Now
    @endcomponent

    Thanks,
    {{ config('app.name') }}
@endcomponent




