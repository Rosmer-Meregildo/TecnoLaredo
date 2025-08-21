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
        .progress-bar-container {
            width: 100%;
            background-color: #e2e8f0;
            border-radius: 9999px;
            height: 1.5rem;
            overflow: hidden;
        }
        .progress-bar {
            background-image: linear-gradient(to right, #48bb78, #38a169);
            height: 100%;
            transition: width 0.3s ease-in-out;
            border-radius: 9999px;
        }
        .task-list li {
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
        }
        .task-list li:last-child {
            border-bottom: none;
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
                <button id="notificationBtn" class="relative p-2 bg-white bg-opacity-20 rounded-full hover:bg-opacity-30 transition-all duration-200">
                    <span class="text-xl">🔔</span>
                    <span id="notificationBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                </button>
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
                    <p class="text-sm">💡 Tip del día: <span id="daily-tip"></span></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="card-hover bg-white rounded-xl p-4 shadow-md border-l-4 border-blue-500">
                    <div class="text-3xl mb-2">📈</div>
                    <h3 class="font-semibold text-gray-800">Progreso</h3>
                    <div class="progress-bar-container mt-2">
                        <div id="progress-bar" class="progress-bar" style="width: 0%;"></div>
                    </div>
                    <p id="progress-value" class="text-xl font-bold text-blue-600 mt-2">0%</p>
                </div>
                <div class="card-hover bg-white rounded-xl p-4 shadow-md border-l-4 border-green-500">
                    <div class="text-3xl mb-2">⭐</div>
                    <h3 class="font-semibold text-gray-800">Puntos</h3>
                    <p id="puntos-valor" class="text-2xl font-bold text-green-600">0</p>
                </div>
                <div class="card-hover bg-white rounded-xl p-4 shadow-md border-l-4 border-yellow-500">
                    <div class="text-3xl mb-2">🔥</div>
                    <h3 class="font-semibold text-gray-800">Racha</h3>
                    <p id="racha-valor" class="text-2xl font-bold text-yellow-600">0 días</p>
                </div>
                <div class="card-hover bg-white rounded-xl p-4 shadow-md border-l-4 border-purple-500">
                    <div class="text-3xl mb-2">🎯</div>
                    <h3 class="font-semibold text-gray-800">Metas</h3>
                    <p id="metas-valor" class="text-2xl font-bold text-purple-600">0/0</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-md mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Tareas Diarias</h3>
                    <button id="reset-daily-btn" class="bg-red-500 text-white font-semibold py-1 px-3 rounded-full text-xs shadow-md hover:bg-red-600 transition-colors duration-200">
                        Reiniciar
                    </button>
                </div>
                <ul id="daily-tasks-list" class="task-list">
                    </ul>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Metas Semanales</h3>
                </div>
                <div class="flex gap-2 mb-4">
                    <input type="text" id="goal-name-input" placeholder="Nombre de la nueva meta semanal" class="flex-grow p-2 border rounded-md">
                    <button id="add-goal-btn" class="bg-green-500 text-white font-semibold py-2 px-4 rounded-md hover:bg-green-600">Agregar</button>
                </div>
                <ul id="weekly-goals-list" class="task-list">
                    </ul>
            </div>

        </section>
    </main>

    <div id="notificationPanel" class="fixed top-0 right-0 w-80 h-full bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-50">
        <div class="p-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold">Notificaciones</h3>
                <button id="closeNotifications" class="text-2xl">&times;</button>
            </div>
        </div>
        <div id="notifications-content" class="p-4 space-y-4">
            </div>
    </div>

    <div id="delete-confirmation-panel" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-xl text-center max-w-sm w-full">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">¿Quieres eliminar tu meta semanal?</h3>
            <div class="flex justify-center space-x-4">
                <button id="confirm-delete-yes" class="bg-red-500 text-white font-semibold py-2 px-4 rounded-md hover:bg-red-600 transition-colors">Sí</button>
                <button id="confirm-delete-no" class="bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-md hover:bg-gray-400 transition-colors">No</button>
            </div>
        </div>
    </div>
    
    <script>
        // Funciones para manejar notificaciones
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationPanel = document.getElementById('notificationPanel');
        const closeNotificationsBtn = document.getElementById('closeNotifications');

        if (notificationBtn && notificationPanel && closeNotificationsBtn) {
            notificationBtn.addEventListener('click', () => {
                notificationPanel.classList.remove('translate-x-full');
                // Al abrir el panel, se restablece el contador de notificaciones
                NOTIFICATION_BADGE.textContent = '0';
                saveState();
            });
            closeNotificationsBtn.addEventListener('click', () => {
                notificationPanel.classList.add('translate-x-full');
            });
        }

        // Lógica principal para la gamificación
        document.addEventListener("DOMContentLoaded", () => {
            const progressBar = document.getElementById("progress-bar");
            const progressValue = document.getElementById("progress-value");
            const puntosValor = document.getElementById("puntos-valor");
            const rachaValor = document.getElementById("racha-valor");
            const metasValor = document.getElementById("metas-valor");
            const dailyTasksList = document.getElementById("daily-tasks-list");
            const weeklyGoalsList = document.getElementById("weekly-goals-list");
            const resetDailyBtn = document.getElementById("reset-daily-btn");
            const addGoalBtn = document.getElementById("add-goal-btn");
            const goalNameInput = document.getElementById("goal-name-input");
            const deleteConfirmationPanel = document.getElementById("delete-confirmation-panel");
            const confirmDeleteYes = document.getElementById("confirm-delete-yes");
            const confirmDeleteNo = document.getElementById("confirm-delete-no");
            const dailyTipElement = document.getElementById("daily-tip");
            const notificationsContent = document.getElementById("notifications-content");

            const NOTIFICATION_BADGE = document.getElementById("notificationBadge");

            let goalToDeleteIndex = null;

            // Lista de 7 tips para estudiantes
            const STUDY_TIPS = [
                "Usa la técnica Pomodoro para estudiar más eficientemente.",
                "Crea un horario de estudio y apégate a él.",
                "Haz un resumen de tus apuntes al final del día.",
                "Enseña lo que has aprendido a alguien para reforzar el conocimiento.",
                "Toma descansos cortos y activos para refrescar tu mente.",
                "Cambia de lugar de estudio para evitar la monotonía.",
                "Organiza tus materiales y área de estudio antes de empezar."
            ];

            // Lista de 35 tareas para estudiantes
            const ALL_DAILY_TASKS = [
                "Terminar 30 minutos de lectura de un libro académico",
                "Resolver 5 ejercicios de matemáticas o lógica",
                "Revisar apuntes de clase por 15 minutos",
                "Hacer 20 minutos de ejercicio físico",
                "Planificar el horario del día siguiente",
                "Preparar la mochila para el día de mañana",
                "Aprender 5 palabras nuevas de un idioma",
                "Meditar por 10 minutos",
                "Lavar los platos después de cenar",
                "Organizar el escritorio de estudio",
                "Tomar 1.5 litros de agua",
                "Escribir en un diario por 10 minutos",
                "Leer las noticias del día",
                "Limpiar tu habitación",
                "Ayudar en una tarea del hogar",
                "Revisar el correo electrónico y responder uno",
                "Llamar a un familiar o amigo",
                "Ver un documental corto sobre un tema de interés",
                "Practicar un instrumento musical por 15 minutos",
                "Escribir un resumen de una lección",
                "Completar una lección en una aplicación de aprendizaje",
                "Preparar un almuerzo saludable",
                "Caminar 30 minutos al aire libre",
                "Hacer una lista de 3 cosas por las que estás agradecido",
                "Investigar sobre una carrera universitaria",
                "Aprender una habilidad nueva en línea (ej. un tutorial de código)",
                "Estudiar un concepto difícil por 25 minutos (Técnica Pomodoro)",
                "Hacer una copia de seguridad de tus archivos importantes",
                "Revisar tu presupuesto o finanzas personales",
                "Dedicar 10 minutos a un hobby creativo",
                "Hacer 5 estiramientos de cuerpo completo",
                "Escribir una idea para un proyecto personal",
                "Visualizar tus metas por 5 minutos",
                "Desconectar de las redes sociales por 1 hora",
                "Comer una porción de fruta o verdura"
            ];

            // Función para obtener 5 tareas aleatorias
            const getDailyTasks = () => {
                const shuffled = [...ALL_DAILY_TASKS].sort(() => 0.5 - Math.random());
                return shuffled.slice(0, 5).map(name => ({ name, completed: false, points: 20 }));
            };

            // Función para obtener un tip aleatorio
            const getDailyTip = () => {
                const tipIndex = Math.floor(Math.random() * STUDY_TIPS.length);
                return STUDY_TIPS[tipIndex];
            };

            // Estado de la aplicación, guardado en localStorage
            let state = {
                puntos: parseInt(localStorage.getItem("puntos") || 0),
                racha: parseInt(localStorage.getItem("racha") || 0),
                lastCheckDate: localStorage.getItem("lastCheckDate") || new Date().toISOString().slice(0, 10),
                dailyTasks: JSON.parse(localStorage.getItem("dailyTasks")) || getDailyTasks(),
                weeklyGoals: JSON.parse(localStorage.getItem("weeklyGoals")) || [],
                dailyTip: localStorage.getItem("dailyTip") || getDailyTip(),
                notifications: JSON.parse(localStorage.getItem("notifications") || '[]')
            };

            // Función para guardar el estado en localStorage
            const saveState = () => {
                localStorage.setItem("puntos", state.puntos);
                localStorage.setItem("racha", state.racha);
                localStorage.setItem("lastCheckDate", state.lastCheckDate);
                localStorage.setItem("dailyTasks", JSON.stringify(state.dailyTasks));
                localStorage.setItem("weeklyGoals", JSON.stringify(state.weeklyGoals));
                localStorage.setItem("dailyTip", state.dailyTip);
                localStorage.setItem("notifications", JSON.stringify(state.notifications));
            };

            // Función para manejar el reinicio diario y semanal
            const handleDailyAndWeeklyReset = () => {
                const today = new Date();
                const todayDate = today.toISOString().slice(0, 10);
                const dayOfWeek = today.getDay(); // 0 = Domingo, 6 = Sábado

                // Reinicio de tareas, progreso y tips si es un nuevo día
                if (todayDate !== state.lastCheckDate) {
                    const completedYesterday = state.dailyTasks.every(task => task.completed);
                    if (completedYesterday) {
                        state.racha++;
                        addNotification("¡Racha diaria!", "¡Completaste todas las tareas de ayer! Tu racha ahora es de " + state.racha + " días.");
                    } else {
                        state.racha = 0; // Reinicia la racha si no se completó el día anterior
                    }
                    state.dailyTasks = getDailyTasks(); // Obtener nuevas tareas
                    state.dailyTip = getDailyTip(); // Obtener un nuevo tip
                    state.lastCheckDate = todayDate;
                }

                // Reinicio de metas semanales si es sábado
                if (dayOfWeek === 6 && todayDate !== state.lastCheckDate) {
                    state.weeklyGoals = []; // Vaciar las metas
                    addNotification("¡Metas semanales reiniciadas!", "Es sábado, tus metas semanales se han reiniciado. ¡Prepárate para la próxima semana!");
                }
                saveState();
            };

            // Función para renderizar el progreso
            const renderProgress = () => {
                const completedTasks = state.dailyTasks.filter(task => task.completed).length;
                const totalTasks = state.dailyTasks.length;
                const progress = (totalTasks > 0) ? (completedTasks / totalTasks) * 100 : 0;
                progressBar.style.width = `${progress}%`;
                progressValue.textContent = `${Math.round(progress)}%`;
            };

            // Función para renderizar los puntos
            const renderPuntos = () => {
                puntosValor.textContent = state.puntos;
            };

            // Función para renderizar la racha
            const renderRacha = () => {
                rachaValor.textContent = `${state.racha} días`;
            };

            // Función para renderizar las metas
            const renderMetas = () => {
                const completedGoals = state.weeklyGoals.filter(goal => goal.completed).length;
                const totalGoals = state.weeklyGoals.length;
                metasValor.textContent = `${completedGoals}/${totalGoals}`;
            };
            
            // Función para agregar notificaciones
            const addNotification = (title, message) => {
                const notification = {
                    title: title,
                    message: message,
                    timestamp: Date.now()
                };
                state.notifications.unshift(notification);
                NOTIFICATION_BADGE.textContent = parseInt(NOTIFICATION_BADGE.textContent) + 1;
                saveState();
                renderNotifications();
            };

            // Función para calcular el tiempo transcurrido
            const timeSince = (timestamp) => {
                const seconds = Math.floor((Date.now() - timestamp) / 1000);
                let interval = seconds / 31536000;
                if (interval > 1) return `hace ${Math.floor(interval)} años`;
                interval = seconds / 2592000;
                if (interval > 1) return `hace ${Math.floor(interval)} meses`;
                interval = seconds / 86400;
                if (interval > 1) return `hace ${Math.floor(interval)} días`;
                interval = seconds / 3600;
                if (interval > 1) return `hace ${Math.floor(interval)} horas`;
                interval = seconds / 60;
                if (interval > 1) return `hace ${Math.floor(interval)} minutos`;
                return `hace ${Math.floor(seconds)} segundos`;
            };

            // Función para renderizar las notificaciones
            const renderNotifications = () => {
                notificationsContent.innerHTML = '';
                state.notifications.forEach((notification, index) => {
                    const notificationDiv = document.createElement("div");
                    notificationDiv.classList.add("notification", "bg-blue-50", "border-l-4", "border-blue-500", "p-4", "rounded", "mb-4", "relative");
                    notificationDiv.innerHTML = `
                        <p class="font-semibold text-blue-800">${notification.title}</p>
                        <p class="text-sm text-blue-600">${notification.message}</p>
                        <p class="text-xs text-gray-500">${timeSince(notification.timestamp)}</p>
                        <button class="delete-notification-btn absolute top-2 right-2 text-gray-400 hover:text-red-600 transition-colors" data-index="${index}">
                            &times;
                        </button>
                    `;
                    notificationsContent.appendChild(notificationDiv);
                });
                 // Event listener para eliminar notificaciones
                document.querySelectorAll(".delete-notification-btn").forEach(button => {
                    button.addEventListener("click", (e) => {
                        const index = e.target.dataset.index;
                        state.notifications.splice(index, 1);
                        saveState();
                        renderNotifications();
                    });
                });
            };

            // Función para renderizar las tareas diarias
            const renderDailyTasks = () => {
                dailyTasksList.innerHTML = '';
                state.dailyTasks.forEach((task, index) => {
                    const li = document.createElement("li");
                    li.innerHTML = `
                        <input type="checkbox" id="daily-task-${index}" ${task.completed ? 'checked' : ''} class="mr-3 w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500">
                        <label for="daily-task-${index}" class="flex-grow text-gray-700 ${task.completed ? 'line-through text-gray-400' : ''}">${task.name}</label>
                    `;
                    li.querySelector('input').addEventListener('change', (e) => {
                        state.dailyTasks[index].completed = e.target.checked;
                        if (e.target.checked) {
                            state.puntos += state.dailyTasks[index].points;
                            addNotification("¡Tarea completada!", `"${state.dailyTasks[index].name}" te ha dado ${state.dailyTasks[index].points} puntos.`);
                        } else {
                            state.puntos -= state.dailyTasks[index].points;
                        }
                        saveState();
                        renderAll();
                    });
                    dailyTasksList.appendChild(li);
                });
            };

            // Función para renderizar las metas semanales
            const renderWeeklyGoals = () => {
                weeklyGoalsList.innerHTML = '';
                state.weeklyGoals.forEach((goal, index) => {
                    const li = document.createElement("li");
                    li.classList.add("flex", "justify-between", "items-center", "mb-2");
                    li.innerHTML = `
                        <div class="flex-grow flex items-center">
                            <input type="checkbox" id="weekly-goal-${index}" ${goal.completed ? 'checked' : ''} class="mr-3 w-4 h-4 text-purple-600 bg-gray-100 rounded border-gray-300 focus:ring-purple-500">
                            <label for="weekly-goal-${index}" class="flex-grow text-gray-700 ${goal.completed ? 'line-through text-gray-400' : ''}">${goal.name}</label>
                        </div>
                        <button class="delete-goal-btn text-red-500 hover:text-red-700 transition-colors" data-index="${index}">
                            &times;
                        </button>
                    `;
                    li.querySelector('input').addEventListener('change', (e) => {
                        state.weeklyGoals[index].completed = e.target.checked;
                        if (e.target.checked) {
                            state.puntos += 50; // Puntos extra por completar una meta
                            addNotification("¡Meta completada!", `Has completado la meta "${state.weeklyGoals[index].name}" y ganado 50 puntos extra.`);
                        } else {
                            state.puntos -= 50;
                        }
                        saveState();
                        renderAll();
                    });
                    // Event listener para mostrar la confirmación al hacer clic en eliminar
                    li.querySelector('.delete-goal-btn').addEventListener("click", (e) => {
                        goalToDeleteIndex = e.target.dataset.index;
                        deleteConfirmationPanel.classList.remove('hidden');
                    });
                    weeklyGoalsList.appendChild(li);
                });
            };

            // Event listeners para el panel de confirmación
            confirmDeleteYes.addEventListener('click', () => {
                if (goalToDeleteIndex !== null) {
                    state.weeklyGoals.splice(goalToDeleteIndex, 1);
                    saveState();
                    renderAll();
                    addNotification("¡Meta eliminada!", "La meta ha sido eliminada correctamente.");
                    goalToDeleteIndex = null;
                }
                deleteConfirmationPanel.classList.add('hidden');
            });

            confirmDeleteNo.addEventListener('click', () => {
                goalToDeleteIndex = null;
                deleteConfirmationPanel.classList.add('hidden');
            });

            // Función para renderizar el tip del día
            const renderDailyTip = () => {
                dailyTipElement.textContent = state.dailyTip;
            };

            // Función para renderizar todo
            const renderAll = () => {
                renderProgress();
                renderPuntos();
                renderRacha();
                renderMetas();
                renderDailyTasks();
                renderWeeklyGoals();
                renderDailyTip();
                renderNotifications();
            };

            // Event listener para el botón de reinicio diario (manual, para pruebas)
            resetDailyBtn.addEventListener("click", () => {
                state.dailyTasks.forEach(task => task.completed = false);
                state.lastCheckDate = new Date().toISOString().slice(0, 10);
                state.dailyTasks = getDailyTasks();
                state.dailyTip = getDailyTip();
                saveState();
                renderAll();
                addNotification("¡Tareas reiniciadas!", "Las tareas diarias se han reiniciado manualmente.");
            });

            // Event listener para agregar metas semanales
            addGoalBtn.addEventListener("click", () => {
                const goalName = goalNameInput.value.trim();
                if (goalName !== "") {
                    state.weeklyGoals.push({ name: goalName, completed: false });
                    goalNameInput.value = ""; // Limpia el campo de entrada
                    saveState();
                    renderWeeklyGoals();
                    renderMetas(); // Actualizar el contador de metas
                }
            });

            // Inicializar al cargar la página
            handleDailyAndWeeklyReset();
            renderAll();
        });
    </script>
</body>
</html>