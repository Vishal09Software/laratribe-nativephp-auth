@extends('nativephp-auth::layouts.app', ['title' => config('nativephp-auth.text.reset_password.title')])
@php $t = config('nativephp-auth.text.reset_password'); @endphp

@section('content')
    <h1 class="nap-title">{{ $t['title'] }}</h1>
    <p class="nap-subtitle">{{ $t['subtitle'] }}</p>

    @if (session('status'))
        <div class="nap-status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('nativephp-auth.password.reset') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $identifier }}">

        <div class="nap-field">
            <label class="nap-label">{{ $t['password_label'] }}</label>
            <input class="nap-input" type="password" name="password" autocomplete="new-password" required autofocus>
            @error('password') <div class="nap-error">{{ $message }}</div> @enderror
        </div>

        <div class="nap-field">
            <label class="nap-label">{{ $t['confirm_password_label'] }}</label>
            <input class="nap-input" type="password" name="password_confirmation" autocomplete="new-password" required>
        </div>

        <button type="submit" class="nap-btn">{{ $t['submit'] }}</button>
    </form>
@endsection
