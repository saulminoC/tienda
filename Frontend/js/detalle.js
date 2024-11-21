// Obtener el ID del producto desde la URL
const params = new URLSearchParams(window.location.search);
const productId = params.get('id'); // ID del producto que viene en la URL

// Referencia al contenedor de detalles
const contenedorDetalle = document.getElementById('detalle-producto');

// Función para cargar los detalles del producto
async function cargarDetalleProducto() {
    if (!productId) {
        contenedorDetalle.innerHTML = '<p>Error: Producto no encontrado.</p>';
        return;
    }

    try {
        // Solicitud al backend para obtener los detalles del producto
        const response = await fetch(`/tienda/Backend/detalle_producto.php?id=${productId}`);
        const producto = await response.json();

        if (producto.error) {
            // Si hay un error en la respuesta del backend
            contenedorDetalle.innerHTML = `<p>${producto.error}</p>`;
        } else {
            // Actualizar el contenido de la página con los detalles del producto
            contenedorDetalle.innerHTML = `
                <img src="${producto.imagen}" alt="${producto.nombre}" class="producto-imagen">
                <h1>${producto.nombre}</h1>
                <p>${producto.descripcion}</p>
                <p class="precio">$${producto.precio}</p>
                <button onclick="agregarAlCarrito(${producto.id}, '${producto.nombre}', ${producto.precio})">Agregar al carrito</button>
            `;
        }
    } catch (error) {
        console.error('Error al cargar el detalle del producto:', error);
        contenedorDetalle.innerHTML = '<p>Error al cargar los detalles del producto. Inténtalo nuevamente.</p>';
    }
}

// Función para agregar al carrito
function agregarAlCarrito(id, nombre, precio) {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const productoExistente = carrito.find(item => item.id === id);

    if (productoExistente) {
        productoExistente.cantidad++;
    } else {
        carrito.push({ id, nombre, precio, cantidad: 1 });
    }

    localStorage.setItem('carrito', JSON.stringify(carrito));
    alert(`${nombre} se ha agregado al carrito.`);
}

function irAInicio() {
    window.location.href = '/tienda/Frontend/web/inicio.html';
}


// Llamar a la función para cargar los detalles del producto
document.addEventListener('DOMContentLoaded', cargarDetalleProducto);
