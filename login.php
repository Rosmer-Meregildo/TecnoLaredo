<?php
session_start();
require 'conexion.php'; // Incluye el archivo de conexión

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form_type'])) {
    if ($_POST['form_type'] == 'login') {
        // Lógica para el inicio de sesión
        $correo = $_POST['correo_login'];
        $contrasena = $_POST['contrasena_login'];

        if (empty($correo) || empty($contrasena)) {
            $error_message = "Todos los campos son obligatorios.";
        } else {
            // Consulta preparada para evitar Inyección SQL
            // Se ha añadido 'nombre' a la consulta para recuperar el nombre del usuario.
            $stmt = $con->prepare("SELECT id, nombre, contrasena FROM usuarios WHERE correo = ?");
            if ($stmt) {
                $stmt->bind_param("s", $correo);
                $stmt->execute();
                $stmt->store_result();
                // Se ha añadido una variable para enlazar el nombre.
                $stmt->bind_result($id, $nombre, $hashed_password);
                $stmt->fetch();

                if ($stmt->num_rows > 0) {
                    // Verifica la contraseña cifrada
                    if (password_verify($contrasena, $hashed_password)) {
                        // Inicio de sesión exitoso
                        $_SESSION['usuario_id'] = $id;
                        // Se guarda el nombre del usuario en la sesión.
                        $_SESSION['usuario_nombre'] = $nombre; 
                        $success_message = "¡Inicio de sesión exitoso! Redirigiendo...";
                        header("Location: index.php"); // Redirige a la página principal
                        exit();
                    } else {
                        $error_message = "Contraseña incorrecta.";
                    }
                } else {
                    $error_message = "Correo electrónico no encontrado.";
                }
                $stmt->close();
            } else {
                $error_message = "Error en la preparación de la consulta: " . $con->error;
            }
        }
    } elseif ($_POST['form_type'] == 'register') {
        // Lógica para el registro de usuario
        $nombre = $_POST['nombre_reg'];
        $apellidos = $_POST['apellidos_reg'];
        $correo = $_POST['correo_reg'];
        $nivel_educacion = $_POST['nivel_reg'];
        $contrasena = $_POST['contrasena_reg'];

        if (empty($nombre) || empty($apellidos) || empty($correo) || empty($nivel_educacion) || empty($contrasena)) {
            $error_message = "Todos los campos son obligatorios.";
        } else {
            // Cifrar la contraseña antes de guardarla
            $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

            // Consulta preparada para insertar datos
            $stmt = $con->prepare("INSERT INTO usuarios (nombre, apellidos, correo, nivel_educacion, contrasena) VALUES (?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sssss", $nombre, $apellidos, $correo, $nivel_educacion, $hashed_password);
                if ($stmt->execute()) {
                    $success_message = "¡Registro exitoso! Ya puedes iniciar sesión.";
                } else {
                    $error_message = "Error al registrar el usuario: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error_message = "Error en la preparación de la consulta: " . $con->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Registro</title>
    <style>
        /* Estilos CSS proporcionados por el usuario */
        :root {
            --primary: #2c3e50;
            --accent: #2980b9;
            --background: #ecf0f1;
            --white: #fff;
            --success: #2ecc71; /* Color verde para éxito */
            --error: #e74c3c;
            --shadow: 0 4px 16px rgba(44,62,80,0.08);
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: var(--background);
            font-family: 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: var(--white);
            box-shadow: var(--shadow);
            border-radius: 16px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 400px;
            margin: 0;
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        h2 {
            color: var(--primary);
            text-align: center;
            margin: 0 0 1.5rem 0;
            font-weight: 700;
            font-size: 1.7rem;
            letter-spacing: 0.5px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }
        label {
            color: var(--primary);
            font-size: 1rem;
            margin-bottom: 0.2rem;
            font-weight: 500;
        }
        input, select {
            padding: 0.8rem;
            border: 1px solid #bdc3c7;
            border-radius: 8px;
            font-size: 1rem;
            background: var(--background);
            transition: border-color 0.2s;
            margin: 0;
        }
        input:focus, select:focus {
            border-color: var(--accent);
            outline: none;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.5rem;
            gap: 1rem;
        }
        button {
            background: var(--accent);
            color: var(--white);
            border: none;
            border-radius: 8px;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.2s;
        }
        button:hover {
            background: var(--primary);
        }
        .link {
            background: none;
            color: var(--accent);
            border: none;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: underline;
            padding: 0;
            font-weight: 500;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.98rem;
            margin: 0;
        }
        .checkbox-group input[type="checkbox"] {
            accent-color: var(--accent);
            width: 18px;
            height: 18px;
        }
        .message {
            position: absolute;
            top: -60px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--success);
            color: var(--white);
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            box-shadow: var(--shadow);
            font-size: 1rem;
            font-weight: 500;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
            z-index: 10;
            min-width: 220px;
            text-align: center;
        }
        .message.show {
            opacity: 1;
            pointer-events: auto;
        }
        @media (max-width: 500px) {
            .container {
                padding: 1.2rem 0.5rem;
                max-width: 98vw;
                border-radius: 10px;
            }
            h2 {
                font-size: 1.2rem;
            }
            .message {
                min-width: 140px;
                font-size: 0.95rem;
                top: -45px;
            }
        }
    </style>
</head>
<body>
    <div class="container" id="login-container">
        <h2>Iniciar Sesión</h2>
        <form id="login-form" method="post" action="">
            <input type="hidden" name="form_type" value="login">
            <div class="form-group">
                <label for="correo_login">Correo electrónico</label>
                <input type="email" id="correo_login" name="correo_login" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="contrasena_login">Contraseña</label>
                <input type="password" id="contrasena_login" name="contrasena_login" required autocomplete="current-password">
            </div>
            <div class="actions">
                <button type="submit">Entrar</button>
                <button type="button" class="link" onclick="showRegister()">Registrarse</button>
            </div>
        </form>
        <div class="message" id="login-message"></div>
    </div>

    <div class="container" id="register-container" style="display:none;">
        <h2>Registro</h2>
        <form id="register-form" method="post" action="">
            <input type="hidden" name="form_type" value="register">
            <div class="form-group">
                <label for="nombre_reg">Nombre</label>
                <input type="text" id="nombre_reg" name="nombre_reg" required>
            </div>
            <div class="form-group">
                <label for="apellidos_reg">Apellidos</label>
                <input type="text" id="apellidos_reg" name="apellidos_reg" required>
            </div>
            <div class="form-group">
                <label for="correo_reg">Correo electrónico</label>
                <input type="email" id="correo_reg" name="correo_reg" required>
            </div>
            <div class="form-group">
                <label for="nivel_reg">Nivel de educación</label>
                <select id="nivel_reg" name="nivel_reg" required>
                    <option value="">Seleccione...</option>
                    <option value="Primaria">Primaria</option>
                    <option value="Secundaria">Secundaria</option>
                    <option value="Preparatoria">Preparatoria</option>
                    <option value="Universidad">Universidad</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
            <div class="form-group">
                <label for="contrasena_reg">Contraseña</label>
                <input type="password" id="contrasena_reg" name="contrasena_reg" required>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="reg-terminos" required>
                <label for="reg-terminos">Acepto términos y condiciones</label>
            </div>
            <div class="actions">
                <button type="submit">Registrar</button>
                <button type="button" class="link" onclick="showLogin()">Volver</button>
            </div>
        </form>
        <div class="message" id="register-message"></div>
    </div>

    <script>
        // Funciones para mostrar/ocultar formularios y mensajes
        function showRegister() {
            document.getElementById('login-container').style.display = 'none';
            document.getElementById('register-container').style.display = 'flex';
            hideMessage('login-message');
        }

        function showLogin() {
            document.getElementById('register-container').style.display = 'none';
            document.getElementById('login-container').style.display = 'flex';
            hideMessage('register-message');
        }

        function showMessage(id, text, type) {
            const msg = document.getElementById(id);
            msg.textContent = text;
            if (type === 'success') {
                msg.style.background = 'var(--success)';
            } else {
                msg.style.background = 'var(--error)';
            }
            msg.classList.add('show');
            setTimeout(() => {
                msg.classList.remove('show');
            }, 3000);
        }

        function hideMessage(id) {
            document.getElementById(id).classList.remove('show');
        }

        // Mostrar mensajes de PHP si existen
        document.addEventListener('DOMContentLoaded', (event) => {
            const successMessage = "<?php echo $success_message; ?>";
            const errorMessage = "<?php echo $error_message; ?>";

            if (successMessage) {
                showMessage('login-message', successMessage, 'success');
                // Si el mensaje es de registro, muestra el formulario de login después de un retraso.
                if (successMessage.includes('Registro')) {
                    setTimeout(showLogin, 3100);
                } else {
                    // Si el login es exitoso, redirige con JavaScript.
                    setTimeout(function() {
                        window.location.href = 'index.html';
                    }, 1500);
                }
            }
            if (errorMessage) {
                showMessage('login-message', errorMessage, 'error');
            }
        });
    </script>
</body>
</html>
