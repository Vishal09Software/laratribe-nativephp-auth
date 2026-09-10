@php $t = config('nativephp-auth.text.forgot_password'); @endphp

<native:column class="w-full h-full p-6 gap-4 bg-theme-background justify-center">

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

    <native:button
        label="{{ $t['submit'] }}"
        variant="primary"
        :loading="$loading"
        @press="send"
    />

    <native:pressable @press="goBack" class="mt-1">
        <native:text class="text-sm text-center font-semibold text-theme-primary">{{ $t['back_to_login'] }}</native:text>
    </native:pressable>

</native:column>
