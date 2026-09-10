@php $t = config('nativephp-auth.text.register'); @endphp

<native:column class="w-full h-full p-6 gap-4 bg-theme-background justify-center">

    <native:text class="text-2xl font-bold text-center text-theme-on-background">{{ $t['title'] }}</native:text>
    <native:text class="text-sm text-center text-theme-on-surface-variant">{{ $t['subtitle'] }}</native:text>

    @if ($error)
        <native:text class="text-sm text-center text-theme-destructive">{{ $error }}</native:text>
    @endif

    <native:outlined-text-input
        label="{{ $t['name_label'] }}"
        native:model="name"
        :disabled="$loading"
    />

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

    <native:outlined-text-input
        label="{{ $t['confirm_password_label'] }}"
        native:model="passwordConfirmation"
        secure
        leading-icon="lock"
        :disabled="$loading"
    />

    <native:button
        label="{{ $t['submit'] }}"
        variant="primary"
        :loading="$loading"
        @press="register"
    />

    <native:row class="justify-center gap-1">
        <native:text class="text-sm text-theme-on-surface-variant">{{ $t['login_prompt'] }}</native:text>
        <native:pressable @press="goBack">
            <native:text class="text-sm font-semibold text-theme-primary">{{ $t['login_link'] }}</native:text>
        </native:pressable>
    </native:row>

</native:column>
