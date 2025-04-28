<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toast').forEach((toastEl) => {
            const toast = new bootstrap.Toast(toastEl, { delay: 5000 })
            toast.show()
        })
    })
</script>