// Carrito almacenado en localStorage
let carritoItems = JSON.parse(localStorage.getItem('carrito')) || [];

// Función para cargar los productos desde el Backend
async function cargarProductos() {
    try {
        const response = await fetch('/tienda/Backend/productos.php');
        const productos = await response.json();

        console.log(productos); // Verifica si los productos se cargan correctamente

        const contenedor = document.getElementById('productos');
        contenedor.innerHTML = ''; // Limpia el contenedor antes de agregar productos

        productos.forEach(producto => {
            const card = document.createElement('div');
            card.className = 'producto-card';
            card.innerHTML = `
                <img src="${producto.imagen}" alt="${producto.nombre}" class="producto-imagen">
                <h3>${producto.nombre}</h3>
                <p>${producto.descripcion}</p>
                <p>$${producto.precio}</p>
                <button onclick="verDetalle(${producto.id})">Ver Detalles</button>
                <button onclick="agregarAlCarrito(${producto.id}, '${producto.nombre}', ${producto.precio})">Agregar al carrito</button>
            `;
            contenedor.appendChild(card);
        });
    } catch (error) {
        console.error('Error al cargar productos:', error);
    }
}

// Función para redirigir a detalle.html con el ID del producto
function verDetalle(id) {
    // Redirige a detalle.html pasando el ID del producto como parámetro en la URL
    window.location.href = `/tienda/Frontend/web/detalle.html?id=${id}`;
}

// Función para agregar un producto al carrito
function agregarAlCarrito(id, nombre, precio) {
    // Buscar si el producto ya está en el carrito
    const productoExistente = carritoItems.find(item => item.id === id);

    if (productoExistente) {
        productoExistente.cantidad++; // Incrementar la cantidad
    } else {
        carritoItems.push({ id, nombre, precio, cantidad: 1 }); // Agregar nuevo producto
    }

    guardarCarrito(); // Guardar en localStorage
    mostrarCarrito(); // Actualizar visualización
    alert(`${nombre} ha sido agregado al carrito.`);
}

// Función para eliminar un producto del carrito
function eliminarDelCarrito(id) {
    carritoItems = carritoItems.filter(item => item.id !== id);
    guardarCarrito(); // Guardar cambios
    mostrarCarrito(); // Actualizar visualización
}

// Función para mostrar el carrito
function mostrarCarrito() {
    const contenedorCarrito = document.getElementById('carrito-items');
    contenedorCarrito.innerHTML = '';

    if (carritoItems.length === 0) {
        contenedorCarrito.innerHTML = '<p>El carrito está vacío</p>';
        return;
    }

    carritoItems.forEach(producto => {
        const item = document.createElement('div');
        item.className = 'carrito-item';
        item.innerHTML = `
            <p>${producto.nombre} - $${producto.precio} x ${producto.cantidad} = $${producto.precio * producto.cantidad}</p>
            <button onclick="eliminarDelCarrito(${producto.id})">Eliminar</button>
        `;
        contenedorCarrito.appendChild(item);
    });

    // Mostrar el total del carrito
    const total = carritoItems.reduce((acc, producto) => acc + producto.precio * producto.cantidad, 0);
    const totalDiv = document.createElement('div');
    totalDiv.className = 'carrito-total';
    totalDiv.innerHTML = `<h3>Total: $${total}</h3>`;
    contenedorCarrito.appendChild(totalDiv);
}

// Función para guardar el carrito en localStorage
function guardarCarrito() {
    localStorage.setItem('carrito', JSON.stringify(carritoItems));
}

// Referencias al botón y contenedor del carrito
document.addEventListener('DOMContentLoaded', () => {
    const toggleCarritoButton = document.getElementById('toggle-carrito');
    const carritoContainer = document.getElementById('carrito');

    if (!toggleCarritoButton || !carritoContainer) {
        console.error("No se encontraron los elementos del carrito en el DOM.");
        return;
    }

    // Función para alternar el estado del carrito
    toggleCarritoButton.addEventListener('click', () => {
        carritoContainer.classList.toggle('abierto'); // Abre o cierra el carrito
        carritoContainer.classList.toggle('cerrado'); // Cierra o abre el carrito
    });

    cargarProductos(); // Cargar productos desde el backend
    mostrarCarrito(); // Mostrar carrito al cargar la página
});