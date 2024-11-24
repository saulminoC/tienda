document.addEventListener('DOMContentLoaded', async () => {
    cargarResumen();
    cargarGraficos();
    cargarPedidosRecientes();
    cargarProductosBajoStock();
});

// Función para cargar resumen
async function cargarResumen() {
    try {
        const response = await fetch('/tienda/Backend/dashboard_resumen.php');
        const datos = await response.json();

        document.getElementById('ventas-totales').textContent = `$${datos.ventasTotales}`;
        document.getElementById('pedidos-totales').textContent = datos.pedidosTotales;
        document.getElementById('usuarios-registrados').textContent = datos.usuariosRegistrados;
    } catch (error) {
        console.error('Error al cargar el resumen:', error);
    }
}

// Función para cargar gráficos
async function cargarGraficos() {
    try {
        const response = await fetch('/tienda/Backend/dashboard_graficos.php');
        const datos = await response.json();

        const ventasMensuales = datos.ventasMensuales;
        const productosMasVendidos = datos.productosMasVendidos;

        new Chart(document.getElementById('ventasMensuales'), {
            type: 'bar',
            data: {
                labels: ventasMensuales.labels,
                datasets: [{
                    label: 'Ventas Mensuales',
                    data: ventasMensuales.data,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            }
        });

        new Chart(document.getElementById('productosMasVendidos'), {
            type: 'pie',
            data: {
                labels: productosMasVendidos.labels,
                datasets: [{
                    data: productosMasVendidos.data,
                    backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56']
                }]
            }
        });
    } catch (error) {
        console.error('Error al cargar los gráficos:', error);
    }
}

// Función para cargar pedidos recientes
async function cargarPedidosRecientes() {
    try {
        const response = await fetch('/tienda/Backend/pedidos_recientes.php');
        const pedidos = await response.json();

        const contenedor = document.getElementById('pedidos-recientes');
        contenedor.innerHTML = pedidos.map(pedido => `
            <div><strong>${pedido.fecha}</strong>: Pedido #${pedido.id}, Total: $${pedido.total}</div>
        `).join('');
    } catch (error) {
        console.error('Error al cargar pedidos recientes:', error);
    }
}

// Función para cargar productos en bajo stock
async function cargarProductosBajoStock() {
    try {
        const response = await fetch('/tienda/Backend/productos_bajo_stock.php');
        const productos = await response.json();

        const contenedor = document.getElementById('productos-bajo-stock');
        contenedor.innerHTML = productos.map(producto => `
            <div>${producto.nombre} (Stock: ${producto.stock})</div>
        `).join('');
    } catch (error) {
        console.error('Error al cargar productos en bajo stock:', error);
    }
}
