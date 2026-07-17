@props([
    'inputId'
])

<button 
    type="button" 
    class="position-absolute end-0 top-50 translate-middle-y btn p-2 text-secondary border-0" 
    style="z-index: 10; height: 100%; display: flex; align-items: center; justify-content: center; min-height: 44px; width: 44px;" 
    onclick="togglePasswordVisibility('{{ $inputId }}', this)"
    aria-label="Tampilkan atau sembunyikan kata sandi"
>
    <i class="bi bi-eye fs-5"></i>
</button>

<script>
    if (typeof togglePasswordVisibility === 'undefined') {
        window.togglePasswordVisibility = function(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash fs-5';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye fs-5';
            }
        };
    }
</script>
