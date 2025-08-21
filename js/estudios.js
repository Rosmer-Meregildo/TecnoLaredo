// estudios.js: Funcionalidad para la página de métodos de estudio

document.addEventListener('DOMContentLoaded', () => {

    // --- Temporizador Pomodoro ---
    const pomodoroModal = document.getElementById('pomodoroModal');
    if (pomodoroModal) {
        let pomodoroTimer = null;
        let pomodoroTime = 25 * 60;
        let isRunning = false;
        let isBreak = false;

        document.getElementById('pomodoroBtn').addEventListener('click', () => pomodoroModal.classList.add('show'));
        document.getElementById('closePomodoroModal').addEventListener('click', () => pomodoroModal.classList.remove('show'));

        document.getElementById('startTimer').addEventListener('click', () => {
            if (!isRunning) {
                isRunning = true;
                pomodoroTimer = setInterval(updatePomodoroTimer, 1000);
                document.getElementById('timerStatus').textContent = isBreak ? 'Tiempo de descanso' : 'Estudiando...';
            }
        });

        document.getElementById('pauseTimer').addEventListener('click', () => {
            if (isRunning) {
                isRunning = false;
                clearInterval(pomodoroTimer);
                document.getElementById('timerStatus').textContent = 'Pausado';
            }
        });

        document.getElementById('resetTimer').addEventListener('click', () => {
            isRunning = false;
            clearInterval(pomodoroTimer);
            isBreak = false; // Siempre reiniciar al estado de estudio
            pomodoroTime = 25 * 60;
            updatePomodoroDisplay();
            document.getElementById('timerStatus').textContent = 'Listo para estudiar';
        });

        function updatePomodoroTimer() {
            pomodoroTime--;
            updatePomodoroDisplay();
            
            if (pomodoroTime < 0) {
                clearInterval(pomodoroTimer);
                isRunning = false;
                isBreak = !isBreak;
                pomodoroTime = isBreak ? 5 * 60 : 25 * 60;
                // Se eliminó el "alert()" y se actualiza el estado en pantalla
                document.getElementById('timerStatus').textContent = isBreak ? '¡Tiempo de descanso! 😊' : '¡Hora de volver a estudiar! 📚';
                updatePomodoroDisplay();
            }
        }

        function updatePomodoroDisplay() {
            const minutes = Math.floor(pomodoroTime / 60);
            const seconds = pomodoroTime % 60;
            document.getElementById('timerDisplay').textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            // Si tienes un elemento para la barra de progreso, asegúrate de que esté en el HTML
            // const totalTime = isBreak ? 5 * 60 : 25 * 60;
            // const progress = (totalTime - pomodoroTime) / totalTime;
            // const circumference = 2 * Math.PI * 45;
            // document.getElementById('progressCircle').style.strokeDashoffset = circumference - (progress * circumference);
        }
    }

    // --- Funcionalidad de Modales ---
    const modalTriggers = {
        'mindMapBtn': 'mindMapModal',
        'cornellBtn': 'cornellModal',
        'highlightBtn': 'highlightModal',
        'summaryBtn': 'summaryModal',
        'schemaBtn': 'schemaModal',
        'flashcardsBtn': 'flashcardsModal'
    };

    for (const btnId in modalTriggers) {
        const trigger = document.getElementById(btnId);
        const modal = document.getElementById(modalTriggers[btnId]);
        if (trigger && modal) {
            const closeModalBtn = modal.querySelector('[id^="close"]');
            trigger.addEventListener('click', () => modal.classList.add('show'));
            if(closeModalBtn) {
                closeModalBtn.addEventListener('click', () => modal.classList.remove('show'));
            }
        }
    }
    
    // --- Fichas de Estudio (Flashcards) ---
    const flashcardsModal = document.getElementById('flashcardsModal');
    if(flashcardsModal){
        const flashcards = [
            { question: "¿Cuál es la capital de Francia?", answer: "París" },
            { question: "¿Cuántos continentes hay?", answer: "7 continentes" },
            { question: "¿Quién escribió Don Quijote?", answer: "Miguel de Cervantes" }
        ];
        let currentCardIndex = 0;

        document.getElementById('flashcardsBtn').addEventListener('click', showCurrentCard);
        document.getElementById('flipCard').addEventListener('click', () => document.getElementById('flashcard').classList.toggle('flipped'));
        document.getElementById('nextCard').addEventListener('click', () => {
            currentCardIndex = (currentCardIndex + 1) % flashcards.length;
            showCurrentCard();
        });
        document.getElementById('prevCard').addEventListener('click', () => {
            currentCardIndex = (currentCardIndex - 1 + flashcards.length) % flashcards.length;
            showCurrentCard();
        });

        function showCurrentCard() {
            const card = flashcards[currentCardIndex];
            document.getElementById('questionText').textContent = card.question;
            document.getElementById('answerText').textContent = card.answer;
            document.getElementById('cardCounter').textContent = `${currentCardIndex + 1} / ${flashcards.length}`;
            document.getElementById('flashcard').classList.remove('flipped');
        }
    }

    // --- Lógica para el Panel de Notificaciones ---
    const notificationPanel = document.getElementById('notificationPanel');
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationBadge = document.getElementById('notificationBadge');
    const closeNotificationsBtn = document.getElementById('closeNotificationsBtn');

    // Función para mostrar el panel
    const showNotificationPanel = () => {
        notificationPanel.classList.remove('translate-x-full');
        if (notificationBadge) {
            notificationBadge.style.display = 'none';
        }
    };
    
    // Función para ocultar el panel
    const hideNotificationPanel = () => {
        notificationPanel.classList.add('translate-x-full');
    };

    // Abrir el panel de notificaciones
    notificationBtn.addEventListener('click', () => {
        showNotificationPanel();
    });

    // Cerrar el panel de notificaciones
    closeNotificationsBtn.addEventListener('click', () => {
        hideNotificationPanel();
    });

    // Cerrar modales al hacer clic fuera
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('show');
            }
        });
    });
});
