<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toast').forEach((toastEl) => {
            const toast = new bootstrap.Toast(toastEl, { delay: 5000 })
            toast.show()
        })
    })

    function showToast(message, type = 'danger') {
        const toastContainer = document.querySelector('.toast-container') || createToastContainer();
        
        const toastHTML = `
            <div class="toast align-items-center text-white bg-${type} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;

        const temp = document.createElement('div');
        temp.innerHTML = toastHTML.trim();
        const toastElement = temp.firstChild;
        toastContainer.appendChild(toastElement);

        const toast = new bootstrap.Toast(toastElement, { delay: 5000 });
        toast.show();
    }

    function createToastContainer() {
        const container = document.createElement('div');
        container.classList.add('toast-container', 'bottom-0', 'end-0', 'p-3');
        document.body.appendChild(container);
        return container;
    }

</script>