// Referencias a los elementos de la página
const container = document.getElementById("container");
const loginToggle = document.getElementById("login-toggle");
const registerToggle = document.getElementById("register-toggle");
const loginForm = document.getElementById("login-form");
const registerForm = document.getElementById("register-form");

// Alternar entre formularios
loginToggle.addEventListener("click", () => {
    container.classList.remove("active"); // Mostrar formulario de inicio de sesión
});

registerToggle.addEventListener("click", () => {
    container.classList.add("active"); // Mostrar formulario de registro
});

// Manejo del formulario de inicio de sesión
loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    // Obtener valores del formulario
    const email = document.getElementById("login-email").value;
    const password = document.getElementById("login-password").value;

    try {
        // Enviar datos al Backend
        const response = await fetch("./Backend/login.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email, password }),
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            window.location.href = "./Frontend/web/inicio.html"; // Redirigir a la página principal
        } else {
            document.getElementById("login-error").innerText = data.message;
        }
    } catch (error) {
        console.error("Error al iniciar sesión:", error);
        document.getElementById("login-error").innerText = "Ocurrió un error. Inténtalo nuevamente.";
    }
});

// Manejo del formulario de registro
registerForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    // Obtener valores del formulario
    const nombre = document.getElementById("register-name").value;
    const email = document.getElementById("register-email").value;
    const password = document.getElementById("register-password").value;

    try {
        // Enviar datos al Backend
        const response = await fetch("./Backend/register.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ nombre, email, password }),
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            registerForm.reset(); // Reiniciar formulario de registro
            container.classList.remove("active"); // Cambiar al formulario de inicio de sesión
        } else {
            document.getElementById("register-error").innerText = data.message;
        }
    } catch (error) {
        console.error("Error al registrarse:", error);
        document.getElementById("register-error").innerText = "Ocurrió un error. Inténtalo nuevamente.";
    }
});
