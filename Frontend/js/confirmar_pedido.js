document.getElementById("confirmar-pedido").addEventListener("click", async () => {
    const total = 1200; // Cambia esto por el total dinámico del carrito
    const productos = [
        { nombre: "Laptop Gamer", cantidad: 1, precio: 1000 },
        { nombre: "Mouse", cantidad: 2, precio: 50 }
    ];

    try {
        const response = await fetch("/tienda/Backend/confirmar_pedido.php", {
            method: "POST",
            body: JSON.stringify({ total, productos }),
            headers: { "Content-Type": "application/json" }
        });

        const resultado = await response.json();

        if (resultado.exito) {
            alert(resultado.mensaje);

            // Generar enlace dinámico para el ticket
            const ticketLink = document.getElementById("ticket-link");
            ticketLink.href = `/tienda/Backend/generar_ticket.php?pedido_id=${resultado.pedido_id}`;
            ticketLink.style.display = "inline-block";
        } else {
            alert(resultado.mensaje);
        }
    } catch (error) {
        console.error("Error al confirmar el pedido:", error);
    }
});
