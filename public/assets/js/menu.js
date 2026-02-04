document.addEventListener('DOMContentLoaded', () => {
    // 1. Selectores principales
    const btnColapsar = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const contentArea = document.querySelector('.content-area-ucot'); // Clase exacta de tu CSS
    const iconoFlecha = document.querySelector('#toggleSidebar i');

    // 2. Lógica del Sidebar (Colapso)
    if (btnColapsar) {
        btnColapsar.addEventListener('click', () => {
            // Usamos la clase que tienes en el CSS: .sidebar-compacto
            sidebar.classList.toggle('sidebar-compacto');
            
            // Usamos la clase que tienes en el CSS: .expandido para el contenido
            if (contentArea) {
                contentArea.classList.toggle('expandido');
            }

            // Cambiamos la dirección de la flecha
            if (iconoFlecha) {
                if (sidebar.classList.contains('sidebar-compacto')) {
                    iconoFlecha.classList.replace('fa-chevron-left', 'fa-chevron-right');
                } else {
                    iconoFlecha.classList.replace('fa-chevron-right', 'fa-chevron-left');
                }
            }
        });
    }

    // 3. Lógica del Perfil (Dropdown)
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');

    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            // Alternamos visibilidad
            const isHidden = profileMenu.style.display === 'none' || profileMenu.style.display === '';
            profileMenu.style.display = isHidden ? 'block' : 'none';
            
            // Rotación de flecha del perfil
            const arrow = profileBtn.querySelector('.arrow-icon');
            if (arrow) arrow.classList.toggle('fa-rotate-180');
        });

        // Cerrar al hacer clic fuera
        window.addEventListener('click', (e) => {
            if (!profileBtn.contains(e.target)) {
                profileMenu.style.display = 'none';
                const arrow = profileBtn.querySelector('.arrow-icon');
                if (arrow) arrow.classList.remove('fa-rotate-180');
            }
        });
    }
});