</div><!-- /.main-content -->
</div><!-- /.container-fluid -->

<!-- Bootstrap Bundle con Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Scripts personalizados -->
<script>
    // Marcar el enlace actual como activo
    $(document).ready(function() {
        const currentPath = window.location.pathname;
        $('.nav-link').each(function() {
            const href = $(this).attr('href');
            if (currentPath.includes(href) && href !== '<?= base_url('/') ?>') {
                $(this).addClass('active');
            } else if (currentPath === '<?= base_url('/') ?>' && href === '<?= base_url('/') ?>') {
                $(this).addClass('active');
            }
        });

        // Manejo del sidebar en dispositivos móviles
        $('#sidebarToggle').click(function() {
            $('.sidebar').toggleClass('active');
            $('.main-content').toggleClass('active');
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar todos los modales manualmente
        var modals = document.querySelectorAll('.modal');
        modals.forEach(function(modal) {
            new bootstrap.Modal(modal);
        });

        // Asegurarse de que los botones para abrir modales funcionen
        var modalTriggers = document.querySelectorAll('[data-bs-toggle="modal"]');
        modalTriggers.forEach(function(trigger) {
            trigger.addEventListener('click', function() {
                var targetId = this.getAttribute('data-bs-target');
                var targetModal = document.querySelector(targetId);
                var modal = bootstrap.Modal.getInstance(targetModal) || new bootstrap.Modal(targetModal);
                modal.show();
            });
        });
    });
</script>

<script>
    // Inicializar dropdown manualmente
    document.querySelectorAll('.dropdown-toggle').forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const parent = this.parentNode;
            const dropdown = parent.querySelector('.dropdown-menu');

            // Toggle the dropdown
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            } else {
                // Close all other dropdowns
                document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                    menu.classList.remove('show');
                });
                dropdown.classList.add('show');
            }
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                menu.classList.remove('show');
            });
        }
    });
</script>
</body>

</html>