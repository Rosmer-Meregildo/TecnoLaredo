<?php
// Inicia la sesión para acceder a las variables de sesión
session_start();

// Verifica si la sesión del usuario está activa
if (!isset($_SESSION['usuario_nombre'])) {
    // Si no hay sesión activa, redirige al usuario a la página de login
    header("Location: login.php");
    exit();
}

// Asigna el nombre de usuario de la sesión a una variable
$nombre_usuario = $_SESSION['usuario_nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App para Estudiantes - Inicio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preload" href="styles.css" as="style">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos CSS adicionales */
        .gradient-bg {
            background-image: linear-gradient(to right, #6b46c1, #805ad5);
        }
        .card-hover {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(44, 62, 80, 0.12);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <header class="gradient-bg text-white p-4 shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center">
                    <span class="text-2xl">🏆</span>
                </div>
                <h1 class="text-2xl font-bold">Tecno Laredo</h1>
            </div>
            <div class="flex items-center space-x-2">
                <!-- Botón de notificaciones -->
                <button id="notificationBtn" class="relative p-2 bg-white bg-opacity-20 rounded-full hover:bg-opacity-30 transition-all duration-200">
                    <span class="text-xl">🔔</span>
                    <span id="notificationBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                </button>
                <!-- Botón para cerrar sesión -->
                <a href="logout.php" class="bg-red-500 text-white font-semibold py-2 px-4 rounded-full text-sm shadow-md hover:bg-red-600 transition-colors duration-200">
                    Cerrar Sesión
                </a>
            </div>
        </div>
    </header>

    <nav class="bg-white shadow-md p-4">
        <div class="flex justify-around">
            <a href="index.php" class="nav-btn flex flex-col items-center p-2 rounded-lg bg-blue-100 text-blue-600">
                <span class="text-xl mb-1">🏠</span>
                <span class="text-xs">Inicio</span>
            </a>
            <a href="estudios.html" class="nav-btn flex flex-col items-center p-2 rounded-lg">
                <span class="text-xl mb-1">📚</span>
                <span class="text-xs">Estudio</span>
            </a>
            <a href="horarios.html" class="nav-btn flex flex-col items-center p-2 rounded-lg">
                <span class="text-xl mb-1">📅</span>
                <span class="text-xs">Horarios</span>
            </a>
            <a href="juegos.html" class="nav-btn flex flex-col items-center p-2 rounded-lg">
                <span class="text-xl mb-1">🎮</span>
                <span class="text-xs">Juego</span>
            </a>
        </div>
    </nav>

    <main class="p-4 pb-20">
        <section id="home">
            <div class="bg-gradient-to-r from-pink-400 to-purple-500 rounded-2xl p-6 text-white mb-6">
                <h2 class="text-2xl font-bold mb-2">¡Hola, <?php echo htmlspecialchars($nombre_usuario); ?>! 👋</h2>
                <p class="text-lg opacity-90">Bienvenido a Tecno Laredo, donde vas a aprovechar mejor tu horario de estudios</p>
                <div class="mt-4 bg-white bg-opacity-20 rounded-lg p-3">
                    <p class="text-sm">💡 Tip del día: Usa la técnica Pomodoro para estudiar más eficientemente</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="card-hover bg-white rounded-xl p-4 shadow-md border-l-4 border-blue-500">
                    <div class="text-3xl mb-2">📈</div>
                    <h3 class="font-semibold text-gray-800">Progreso</h3>
                    <p class="text-2xl font-bold text-blue-600">85%</p>
                </div>
                <div class="card-hover bg-white rounded-xl p-4 shadow-md border-l-4 border-green-500">
                    <div class="text-3xl mb-2">⭐</div>
                    <h3 class="font-semibold text-gray-800">Puntos</h3>
                    <p class="text-2xl font-bold text-green-600">1,250</p>
                </div>
                <div class="card-hover bg-white rounded-xl p-4 shadow-md border-l-4 border-yellow-500">
                    <div class="text-3xl mb-2">🔥</div>
                    <h3 class="font-semibold text-gray-800">Racha</h3>
                    <p class="text-2xl font-bold text-yellow-600">7 días</p>
                </div>
                <div class="card-hover bg-white rounded-xl p-4 shadow-md border-l-4 border-purple-500">
                    <div class="text-3xl mb-2">🎯</div>
                    <h3 class="font-semibold text-gray-800">Metas</h3>
                    <p class="text-2xl font-bold text-purple-600">3/5</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Panel de notificaciones y JavaScript -->
    <div id="notificationPanel" class="fixed top-0 right-0 w-80 h-full bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-50">
        <div class="p-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold">Notificaciones</h3>
                <button id="closeNotifications" class="text-2xl">&times;</button>
            </div>
        </div>
        <div class="p-4 space-y-4">
            <div class="notification bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <p class="font-semibold text-blue-800">¡Hora de estudiar! 📚</p>
                <p class="text-sm text-blue-600">Es momento de tu sesión de Matemáticas</p>
                <p class="text-xs text-gray-500">Hace 5 minutos</p>
            </div>
            <div class="notification bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <p class="font-semibold text-green-800">¡Descanso activo! 🏃‍♂️</p>
                <p class="text-sm text-green-600">Tiempo para hacer ejercicio</p>
                <p class="text-xs text-gray-500">Hace 1 hora</p>
            </div>
            <div class="notification bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                <p class="font-semibold text-yellow-800">Nuevo logro desbloqueado! 🏆</p>
                <p class="text-sm text-yellow-600">Has completado 7 días seguidos</p>
                <p class="text-xs text-gray-500">Hace 2 horas</p>
            </div>
        </div>
    </div>

    <!-- Script para abrir/cerrar notificaciones -->
    <script>
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationPanel = document.getElementById('notificationPanel');
        const closeNotificationsBtn = document.getElementById('closeNotifications');

        if (notificationBtn && notificationPanel && closeNotificationsBtn) {
            notificationBtn.addEventListener('click', () => {
                notificationPanel.classList.remove('translate-x-full');
            });

            closeNotificationsBtn.addEventListener('click', () => {
                notificationPanel.classList.add('translate-x-full');
            });
        }
    </script>
</body>
</html>
