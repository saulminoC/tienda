document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById("container");
    const loginToggle = document.getElementById("login-toggle");
    const registerToggle = document.getElementById("register-toggle");
    const loginForm = document.getElementById("login-form");
    const registerForm = document.getElementById("register-form");
    const loginError = document.getElementById("login-error");
    const registerError = document.getElementById("register-error");

    document.getElementById("login-form").addEventListener("submit", async (e) => {
        e.preventDefault();
        
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;
        const datos = { email, password };
    
        try {
            const response = await fetch("/tienda/Backend/login.php", {
                method: "POST",
                body: JSON.stringify(datos),
                headers: { "Content-Type": "application/json" },
            });
            const resultado = await response.json();
    
            if (resultado.exito) {
                alert(resultado.mensaje);
                window.location.href = resultado.redirect; // Redirigir a la URL proporcionada
            } else {
                alert(resultado.mensaje); // Mostrar mensaje de error
            }
        } catch (error) {
            console.error("Error al iniciar sesión:", error);
        }
    });
    

    // Alternar entre formularios
    loginToggle.addEventListener("click", () => {
        container.classList.remove("active");
    });

    registerToggle.addEventListener("click", () => {
        container.classList.add("active");
    });

    // Manejo del formulario de inicio de sesión
    loginForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const email = document.getElementById("login-email").value;
        const password = document.getElementById("login-password").value;

        try {
            const response = await fetch("/tienda/Backend/login.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (data.success) {
                alert(data.message);
                localStorage.setItem("user_name", data.user_name); // Guardar el nombre del usuario
                window.location.href = "/tienda/Frontend/web/inicio.html"; // Redirigir al inicio
            } else {
                loginError.textContent = data.message;
                loginError.style.color = "red";
            }
        } catch (error) {
            console.error("Error al iniciar sesión:", error);
            loginError.textContent = "Error al iniciar sesión. Inténtalo más tarde.";
            loginError.style.color = "red";
        }
    });

    // Manejo del formulario de registro
    registerForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const nombre = document.getElementById("register-name").value;
        const email = document.getElementById("register-email").value;
        const password = document.getElementById("register-password").value;

        try {
            const response = await fetch("/tienda/Backend/register.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ nombre, email, password })
            });

            const data = await response.json();

            if (data.success) {
                alert(data.message);
                registerForm.reset(); // Limpiar formulario de registro
                container.classList.remove("active"); // Cambiar a formulario de inicio de sesión
            } else {
                registerError.textContent = data.message;
                registerError.style.color = "red";
            }
        } catch (error) {
            console.error("Error al registrarse:", error);
            registerError.textContent = "Error al registrarse. Inténtalo más tarde.";
            registerError.style.color = "red";
        }
    });
});
