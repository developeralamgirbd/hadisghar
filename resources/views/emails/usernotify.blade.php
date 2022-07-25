@component('mail::message')
    <p style="font-size: 20px">Email: <span style="color: orangered">{{ $email }}</span></p>
    <p style="font-size: 20px">Role: <span style="color: orangered"> {{ \Illuminate\Support\Str::title($role) }} </span></p>

To set your password, visit the following address:
@component('mail::button', ['url' =>  $url ])
    Reset Password
@endcomponent
    <p style="margin-bottom: 10px">This password reset link will expire in {{ $expire }} Minutes</p>
<span>

If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
      <a target="_blank" rel="noopener noreferrer" href="{{ $url  }}" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3869d4;">{{$url}}</a>
</span>


Thanks,<br>
{{ config('app.name') }}
@endcomponent


