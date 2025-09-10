{{-- resources/views/components/flash.blade.php --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@foreach (['success', 'error', 'warning', 'info'] as $msg)
    @if(session($msg))
        <script>
            Swal.fire({
                icon: '{{ $msg }}', // success, error, warning, info
                text: '{{ session($msg) }}',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok'
            });
        </script>
    @endif
@endforeach
