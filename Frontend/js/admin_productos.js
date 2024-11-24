document.addEventListener('DOMContentLoaded', () => {
    cargarProductos();

    const form = document.getElementById('producto-form');
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(form);

        fetch('/tienda/Backend/gestionar_productos.php', {
            method: 'POST',
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('mensaje').textContent = "Producto guardado con éxito.";
                form.reset();
                cargarProductos();
            } else {
                document.getElementById('mensaje').textContent = "Error al guardar el producto.";
            }
        });
    });
});

function cargarProductos() {
    fetch('/tienda/Backend/gestionar_productos.php')
        .then(res => res.json())
        .then(productos => {
            const lista = document.getElementById('lista-productos');
            lista.innerHTML = '';
            productos.forEach(producto => {
                lista.innerHTML += `
                    <div class="producto-item">
                        <h4>${producto.nombre}</h4>
                        <p>${producto.descripcion}</p>
                        <p>Precio: $${producto.precio}</p>
                        <p>Stock: ${producto.stock}</p>
                        <button onclick="eliminarProducto(${producto.id})">Eliminar</button>
                    </div>
                `;
            });
        });
}

function eliminarProducto(id) {
    fetch(`/tienda/Backend/gestionar_productos.php`, {
        method: 'DELETE',
        body: JSON.stringify({ id }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            cargarProductos();
        }
    });
}
