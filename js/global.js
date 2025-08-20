// global.js: Funcionalidad común para todas las páginas

document.addEventListener('DOMContentLoaded', () => {

    // --- Panel de Notificaciones ---
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationPanel = document.getElementById('notificationPanel');
    const closeNotifications = document.getElementById('closeNotifications');

    if (notificationBtn && notificationPanel && closeNotifications) {
        notificationBtn.addEventListener('click', () => {
            notificationPanel.classList.remove('translate-x-full');
        });

        closeNotifications.addEventListener('click', () => {
            notificationPanel.classList.add('translate-x-full');
        });
    }

    // --- Simulación de notificaciones periódicas ---
    // (Esto es solo un ejemplo que imprime en la consola)
    setInterval(() => {
        const notifications = [
            "¡Hora de un descanso! 😊",
            "¿Ya revisaste tu horario? 📅",
            "¡Tiempo de actividad física! 🏃‍♂️",
            "¡Nuevo tip de estudio disponible! 💡"
        ];
        
        const randomNotification = notifications[Math.floor(Math.random() * notifications.length)];
        
        // Esto normalmente activaría una notificación real del navegador
        console.log("Notificación simulada:", randomNotification);
    }, 60000); // Cada 60 segundos
});