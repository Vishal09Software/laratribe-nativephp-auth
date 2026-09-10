@extends('nativephp-auth::layouts.app', ['title' => config('nativephp-auth.text.forgot_password.title')])
@php $t = config('nativephp-auth.text.forgot_password'); @endphp

@section('content')
    <h1 class="nap-title">{{ $t['title'] }}</h1>
    <p class="nap-subtitle">{{ $t['subtitle'] }}</p>

    @if (session('status'))
        <div class="nap-status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('nativephp-auth.password.forgot.send') }}">
        @csrf

        <div class="nap-field">
            <label class="nap-label">{{ $t['email_label'] }}</label>
            <input class="nap-input" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
            @error('email') <div class="nap-error">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="nap-btn">{{ $t['submit'] }}</button>
    </form>

    <p class="nap-footer">
        <a class="nap-link" href="{{ route('nativephp-auth.login.show') }}">{{ $t['back_to_login'] }}</a>
    </p>
@endsection
