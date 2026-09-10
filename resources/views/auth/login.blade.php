@extends('nativephp-auth::layouts.app', ['title' => config('nativephp-auth.text.login.title')])
@php $t = config('nativephp-auth.text.login'); @endphp

@section('content')
    <h1 class="nap-title">{{ $t['title'] }}</h1>
    <p class="nap-subtitle">{{ $t['subtitle'] }}</p>

    @if (session('status'))
        <div class="nap-status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('nativephp-auth.login') }}">
        @csrf

        <div class="nap-field">
            <label class="nap-label">{{ $t['email_label'] }}</label>
            <input class="nap-input" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
            @error('email') <div class="nap-error">{{ $message }}</div> @enderror
        </div>

        <div class="nap-field">
            <label class="nap-label">{{ $t['password_label'] }}</label>
            <input class="nap-input" type="password" name="password" autocomplete="current-password" required>
            @error('password') <div class="nap-error">{{ $message }}</div> @enderror
        </div>

        <div class="nap-row">
            <span></span>
            <a class="nap-link" href="{{ route('nativephp-auth.password.forgot') }}">{{ $t['forgot_link'] }}</a>
        </div>

        <button type="submit" class="nap-btn">{{ $t['submit'] }}</button>
    </form>

    <p class="nap-footer">
        {{ $t['register_prompt'] }}
        <a class="nap-link" href="{{ route('nativephp-auth.register.show') }}">{{ $t['register_link'] }}</a>
    </p>
@endsection
