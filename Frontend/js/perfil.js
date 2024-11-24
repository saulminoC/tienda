document.addEventListener('DOMContentLoaded', async () => {
    const perfilForm = document.getElementById('perfil-form');
    const mensaje = document.getElementById('mensaje');

    // Cargar datos del perfil al cargar la página
    async function cargarPerfil() {
        try {
            const response = await fetch('/tienda/Backend/obtener_perfil.php');
            const data = await response.json();

            if (data.success) {
                document.getElementById('nombre').value = data.nombre;
                document.getElementById('email').value = data.email;
            } else {
                mensaje.textContent = data.message || 'Error al cargar el perfil.';
                mensaje.style.color = 'red';
            }
        } catch (error) {
            console.error('Error al cargar el perfil:', error);
            mensaje.textContent = 'Error al cargar el perfil.';
        }
    }

    // Actualizar perfil
    perfilForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const nombre = document.getElementById('nombre').value;
        const password = document.getElementById('password').value;

        try {
            const response = await fetch('/tienda/Backend/actualizar_perfil.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nombre, password })
            });

            const data = await response.json();

            if (data.success) {
                mensaje.textContent = 'Perfil actualizado correctamente.';
                mensaje.style.color = 'green';
            } else {
                mensaje.textContent = data.message || 'Error al actualizar el perfil.';
                mensaje.style.color = 'red';
            }
        } catch (error) {
            console.error('Error al actualizar el perfil:', error);
            mensaje.textContent = 'Error al actualizar el perfil.';
        }
    });

    // Llamar a cargarPerfil al cargar la página
    cargarPerfil();
});
