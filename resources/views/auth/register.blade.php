@extends('nativephp-auth::layouts.app', ['title' => config('nativephp-auth.text.register.title')])
@php $t = config('nativephp-auth.text.register'); @endphp

@section('content')
    <h1 class="nap-title">{{ $t['title'] }}</h1>
    <p class="nap-subtitle">{{ $t['subtitle'] }}</p>

    @if (session('status'))
        <div class="nap-status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('nativephp-auth.register') }}">
        @csrf

        <div class="nap-field">
            <label class="nap-label">{{ $t['name_label'] }}</label>
            <input class="nap-input" type="text" name="name" value="{{ old('name') }}" autocomplete="name" required autofocus>
            @error('name') <div class="nap-error">{{ $message }}</div> @enderror
        </div>

        <div class="nap-field">
            <label class="nap-label">{{ $t['email_label'] }}</label>
            <input class="nap-input" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
            @error('email') <div class="nap-error">{{ $message }}</div> @enderror
        </div>

        <div class="nap-field">
            <label class="nap-label">{{ $t['password_label'] }}</label>
            <input class="nap-input" type="password" name="password" autocomplete="new-password" required>
            @error('password') <div class="nap-error">{{ $message }}</div> @enderror
        </div>

        <div class="nap-field">
            <label class="nap-label">{{ $t['confirm_password_label'] }}</label>
            <input class="nap-input" type="password" name="password_confirmation" autocomplete="new-password" required>
        </div>

        <button type="submit" class="nap-btn">{{ $t['submit'] }}</button>
    </form>

    <p class="nap-footer">
        {{ $t['login_prompt'] }}
        <a class="nap-link" href="{{ route('nativephp-auth.login.show') }}">{{ $t['login_link'] }}</a>
    </p>
@endsection
