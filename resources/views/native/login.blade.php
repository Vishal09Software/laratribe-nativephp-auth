@php $t = config('nativephp-auth.text.login'); @endphp

<native:column class="w-full h-full p-6 gap-4 bg-theme-background justify-center">

    @if ($logo = config('nativephp-auth.branding.logo'))
        <native:image src="{{ $logo }}" class="w-16 h-16 rounded-2xl mx-auto mb-2" />
    @endif

    <native:text class="text-2xl font-bold text-center text-theme-on-background">{{ $t['title'] }}</native:text>
    <native:text class="text-sm text-center text-theme-on-surface-variant">{{ $t['subtitle'] }}</native:text>

    @if ($error)
        <native:text class="text-sm text-center text-theme-destructive">{{ $error }}</native:text>
    @endif

    <native:outlined-text-input
        label="{{ $t['email_label'] }}"
        native:model="email"
        keyboard="email"
        leading-icon="email"
        :disabled="$loading"
    />

    <native:outlined-text-input
        label="{{ $t['password_label'] }}"
        native:model="password"
        secure
        leading-icon="lock"
        :disabled="$loading"
    />

    <native:button
        label="{{ $t['submit'] }}"
        variant="primary"
        :loading="$loading"
        @press="login"
    />

    <native:pressable @navigate="{{ $this->route('nativephp-auth.native.forgot-password') }}" class="mt-1">
        <native:text class="text-sm text-center font-semibold text-theme-primary">{{ $t['forgot_link'] }}</native:text>
    </native:pressable>

    <native:row class="justify-center gap-1">
        <native:text class="text-sm text-theme-on-surface-variant">{{ $t['register_prompt'] }}</native:text>
        <native:pressable @navigate="{{ $this->route('nativephp-auth.native.register') }}">
            <native:text class="text-sm font-semibold text-theme-primary">{{ $t['register_link'] }}</native:text>
        </native:pressable>
    </native:row>

</native:column>
