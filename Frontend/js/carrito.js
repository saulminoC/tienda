document.addEventListener('DOMContentLoaded', () => {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const listaCarrito = document.getElementById('lista-carrito');
    const totalCarrito = document.getElementById('total-carrito');
    const botonFinalizarCompra = document.getElementById('finalizar-compra');

    let total = 0;

    // Mostrar productos en el carrito
    carrito.forEach(item => {
        const productoDiv = document.createElement('div');
        productoDiv.className = 'carrito-item';
        productoDiv.innerHTML = `
            <p>${item.nombre} - $${item.precio} x ${item.cantidad}</p>
        `;
        listaCarrito.appendChild(productoDiv);
        total += item.precio * item.cantidad;
    });

    // Mostrar total
    totalCarrito.innerHTML = `<h3>Total: $${total}</h3>`;

    // Finalizar compra
    botonFinalizarCompra.addEventListener('click', async () => {
        if (carrito.length === 0) {
            alert('El carrito está vacío.');
            return;
        }

        try {
            const response = await fetch('/tienda/Backend/finalizar_compra.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ carrito })
            });

            const data = await response.json();

            if (data.success) {
                alert('Compra finalizada con éxito.');
                localStorage.removeItem('carrito'); // Vaciar el carrito
                window.location.href = '/tienda/Frontend/web/confirmacion.html'; // Redirigir a confirmación
            } else {
                alert(`Error al finalizar la compra: ${data.message}`);
            }
        } catch (error) {
            console.error('Error al finalizar la compra:', error);
            alert('Ocurrió un error al procesar tu compra. Inténtalo de nuevo.');
        }
    });
});
