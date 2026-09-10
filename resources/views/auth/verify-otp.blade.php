@extends('nativephp-auth::layouts.app', ['title' => config('nativephp-auth.text.otp.title')])
@php $t = config('nativephp-auth.text.otp'); @endphp

@section('content')
    <h1 class="nap-title">{{ $t['title'] }}</h1>
    <p class="nap-subtitle">{{ str_replace(':identifier', $identifier, $t['subtitle']) }}</p>

    @if (session('status'))
        <div class="nap-status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('nativephp-auth.otp.verify') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $identifier }}">
        <input type="hidden" name="type" value="{{ $type }}">

        <div class="nap-field">
            <label class="nap-label">{{ $t['code_label'] }}</label>
            <input class="nap-input nap-otp-input" type="text" name="code" inputmode="numeric" pattern="[0-9]*" maxlength="8" autocomplete="one-time-code" required autofocus>
            @error('code') <div class="nap-error">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="nap-btn">{{ $t['submit'] }}</button>
    </form>

    <form method="POST" action="{{ route('nativephp-auth.otp.resend') }}" style="margin-top: 14px;">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">
        <p class="nap-footer" style="margin-top:0;">
            {{ $t['resend_prompt'] }}
            <button type="submit" class="nap-link" style="background:none;border:none;padding:0;font-size:inherit;cursor:pointer;">{{ $t['resend_link'] }}</button>
        </p>
    </form>
@endsection
