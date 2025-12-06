@if (session('error'))
    <script>
        window.addEventListener("load", function() {
            Swal.fire({
                title: 'Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'Ok'
            });
        });
    </script>
@elseif (session('success'))
    <script>
        window.addEventListener("load", function() {
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'Ok'
            });
        });
    </script>
@endif
