@php $t = config('nativephp-auth.text.otp'); @endphp

<native:column class="w-full h-full p-6 gap-4 bg-theme-background justify-center">

    <native:text class="text-2xl font-bold text-center text-theme-on-background">{{ $t['title'] }}</native:text>
    <native:text class="text-sm text-center text-theme-on-surface-variant">
        {{ str_replace(':identifier', $email, $t['subtitle']) }}
    </native:text>

    @if ($status)
        <native:text class="text-sm text-center text-theme-primary">{{ $status }}</native:text>
    @endif

    @if ($error)
        <native:text class="text-sm text-center text-theme-destructive">{{ $error }}</native:text>
    @endif

    <native:outlined-text-input
        label="{{ $t['code_label'] }}"
        native:model="code"
        keyboard="number"
        :disabled="$loading"
    />

    <native:button
        label="{{ $t['submit'] }}"
        variant="primary"
        :loading="$loading"
        @press="verify"
    />

    <native:row class="justify-center gap-1">
        <native:text class="text-sm text-theme-on-surface-variant">{{ $t['resend_prompt'] }}</native:text>
        <native:pressable @press="resend">
            <native:text class="text-sm font-semibold text-theme-primary">{{ $t['resend_link'] }}</native:text>
        </native:pressable>
    </native:row>

</native:column>
