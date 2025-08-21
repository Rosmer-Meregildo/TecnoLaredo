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
            $stmt = $con->prepare("SELECT id, nombre, contrasena FROM usuarios WHERE correo = ?");
            if ($stmt) {
                $stmt->bind_param("s", $correo);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($id, $nombre, $hashed_password);
                $stmt->fetch();

                if ($stmt->num_rows > 0) {
                    if (password_verify($contrasena, $hashed_password)) {
                        $_SESSION['usuario_id'] = $id;
                        $_SESSION['usuario_nombre'] = $nombre; 
                        $success_message = "¡Inicio de sesión exitoso! Redirigiendo...";
                        header("Location: index.php");
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
    <title>Login y Registro - Tecno Laredo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background-image: linear-gradient(to right, #60A5FA, #8B5CF6);
        }
        .form-container-gradient {
            background: linear-gradient(to bottom, rgb(59 130 246 / 36%), #F3F4F6);
        }
        .message {
            position: absolute;
            top: -60px;
            left: 50%;
            transform: translateX(-50%);
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
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
        .message.success {
            background-color: #34D399; /* Verde de Tailwind */
            color: white;
        }
        .message.error {
            background-color: #EF4444; /* Rojo de Tailwind */
            color: white;
        }
        body {
            background: linear-gradient(to right, #60A5FA, #8B5CF6);
        }
    </style>
</head>
<body class="bg-blue-50 flex items-center justify-center min-h-screen p-4 form-container-gradient">

    <div class="rounded-xl p-6 shadow-md w-full max-w-sm bg-white form-container-gradient" id="login-container">
        <div class="flex items-center justify-center mb-6">
            <div class="w-12 h-12 gradient-bg rounded-full flex items-center justify-center mr-3">
                <span class="text-2xl">🏆</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Iniciar Sesión</h2>
        </div>
        
        <form id="login-form" method="post" action="" class="space-y-4">
            <input type="hidden" name="form_type" value="login">
            <div class="space-y-1">
                <label for="correo_login" class="text-sm font-semibold text-gray-700">Correo electrónico</label>
                <input type="email" id="correo_login" name="correo_login" required autocomplete="username"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="space-y-1">
                <label for="contrasena_login" class="text-sm font-semibold text-gray-700">Contraseña</label>
                <input type="password" id="contrasena_login" name="contrasena_login" required autocomplete="current-password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit"
                    class="w-full gradient-bg text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity">
                Entrar
            </button>
        </form>
        <p class="text-center text-sm text-gray-600 mt-4">¿No tienes una cuenta? <button type="button" class="text-blue-600 font-semibold hover:underline" onclick="showRegister()">Regístrate</button></p>

        <div class="message" id="login-message"></div>
    </div>

    <div class="rounded-xl p-6 shadow-md w-full max-w-sm hidden bg-white form-container-gradient" id="register-container">
        <div class="flex items-center justify-center mb-6">
            <div class="w-12 h-12 gradient-bg rounded-full flex items-center justify-center mr-3">
                <span class="text-2xl">📝</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Registro</h2>
        </div>
        
        <form id="register-form" method="post" action="" class="space-y-4">
            <input type="hidden" name="form_type" value="register">
            <div class="space-y-1">
                <label for="nombre_reg" class="text-sm font-semibold text-gray-700">Nombre</label>
                <input type="text" id="nombre_reg" name="nombre_reg" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="space-y-1">
                <label for="apellidos_reg" class="text-sm font-semibold text-gray-700">Apellidos</label>
                <input type="text" id="apellidos_reg" name="apellidos_reg" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="space-y-1">
                <label for="correo_reg" class="text-sm font-semibold text-gray-700">Correo electrónico</label>
                <input type="email" id="correo_reg" name="correo_reg" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="space-y-1">
                <label for="nivel_reg" class="text-sm font-semibold text-gray-700">Nivel de educación</label>
                <select id="nivel_reg" name="nivel_reg" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Seleccione...</option>
                    <option value="Primaria">Primaria</option>
                    <option value="Secundaria">Secundaria</option>
                    <option value="Preparatoria">Preparatoria</option>
                    <option value="Universidad">Universidad</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
            <div class="space-y-1">
                <label for="contrasena_reg" class="text-sm font-semibold text-gray-700">Contraseña</label>
                <input type="password" id="contrasena_reg" name="contrasena_reg" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-center space-x-2">
                <input type="checkbox" id="reg-terminos" required
                       class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500">
                <label for="reg-terminos" class="text-sm text-gray-700">Acepto términos y condiciones</label>
            </div>
            <button type="submit"
                    class="w-full gradient-bg text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity">
                Registrar
            </button>
        </form>
        <p class="text-center text-sm text-gray-600 mt-4">¿Ya tienes una cuenta? <button type="button" class="text-blue-600 font-semibold hover:underline" onclick="showLogin()">Inicia sesión</button></p>

        <div class="message" id="register-message"></div>
    </div>

    <script>
        function showRegister() {
            document.getElementById('login-container').classList.add('hidden');
            document.getElementById('register-container').classList.remove('hidden');
            hideMessage('login-message');
        }

        function showLogin() {
            document.getElementById('register-container').classList.add('hidden');
            document.getElementById('login-container').classList.remove('hidden');
            hideMessage('register-message');
        }

        function showMessage(id, text, type) {
            const msg = document.getElementById(id);
            msg.textContent = text;
            msg.className = `message show ${type}`;
            setTimeout(() => {
                msg.classList.remove('show');
            }, 3000);
        }

        function hideMessage(id) {
            document.getElementById(id).classList.remove('show');
        }

        // Mostrar mensajes de PHP si existen
        document.addEventListener('DOMContentLoaded', (event) => {
            const successMessage = "<?php echo addslashes($success_message); ?>";
            const errorMessage = "<?php echo addslashes($error_message); ?>";

            if (successMessage) {
                showMessage('login-message', successMessage, 'success');
                if (successMessage.includes('Registro')) {
                    setTimeout(showLogin, 3100);
                } else {
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 1500);
                }
            }
            if (errorMessage) {
                // Determina si mostrar el mensaje en el formulario de login o de registro
                const loginContainer = document.getElementById('login-container');
                const registerContainer = document.getElementById('register-container');
                
                if (!loginContainer.classList.contains('hidden')) {
                    showMessage('login-message', errorMessage, 'error');
                } else if (!registerContainer.classList.contains('hidden')) {
                    showMessage('register-message', errorMessage, 'error');
                }
            }
        });
    </script>
</body>
</html>