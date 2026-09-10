@php $t = config('nativephp-auth.text.reset_password'); @endphp

<native:column class="w-full h-full p-6 gap-4 bg-theme-background justify-center">

    <native:text class="text-2xl font-bold text-center text-theme-on-background">{{ $t['title'] }}</native:text>
    <native:text class="text-sm text-center text-theme-on-surface-variant">{{ $t['subtitle'] }}</native:text>

    @if ($error)
        <native:text class="text-sm text-center text-theme-destructive">{{ $error }}</native:text>
    @endif

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
        @press="reset"
    />

</native:column>
