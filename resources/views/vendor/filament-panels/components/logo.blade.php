<div>
    <span x-show="$store.theme === 'light'">
        <img src="{{ asset('/images/Logo_VF_Lado_FClaro.png') }}" alt="Logo" class="h-10">
    </span>

    <span x-show="$store.theme === 'dark'">
        <img src="{{ asset('/images/Logo_VF_Lado_FEscuro.png') }}" alt="Logo" class="h-10">
    </span>
</div>
