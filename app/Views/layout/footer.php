</div> <!-- Penutup .container -->

    <!-- JAVASCRIPT yang Disesuaikan untuk TAILWIND -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah ada elemen modal di halaman ini
            const modal = document.getElementById('confirmationModal');
            if (modal) {
                const modalContent = modal.querySelector('div:first-child');
                const confirmBtnYa = document.getElementById('confirmBtnYa');
                const confirmBtnTidak = document.getElementById('confirmBtnTidak');
                const updateButtons = document.querySelectorAll('.update-btn');

                let formToSubmit = null;

                function showModal() {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    setTimeout(() => {
                        modalContent.classList.remove('scale-95', 'opacity-0');
                        modalContent.classList.add('scale-100', 'opacity-100');
                    }, 10);
                }

                function hideModal() {
                    modalContent.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                        formToSubmit = null;
                    }, 300); // Sesuaikan durasi dengan transisi di CSS
                }

                updateButtons.forEach(button => {
                    button.addEventListener('click', function(event) {
                        formToSubmit = event.target.closest('form');
                        showModal();
                    });
                });

                confirmBtnYa.addEventListener('click', function() {
                    if (formToSubmit) {
                        formToSubmit.submit();
                    }
                });

                confirmBtnTidak.addEventListener('click', hideModal);

                modal.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        hideModal();
                    }
                });
            }
        });
    </script>
</body>
</html>