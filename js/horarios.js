// horarios.js: Funcionalidad exclusiva para la página de horarios

document.addEventListener('DOMContentLoaded', () => {
    const addScheduleBtn = document.getElementById('addSchedule');
    const scheduleList = document.getElementById('scheduleList');
    const categorySelect = document.getElementById('categorySelect');
    const activitySelect = document.getElementById('activitySelect');
    const customActivityContainer = document.getElementById('customActivityContainer');
    const customActivityInput = document.getElementById('customActivityInput');
    const startTimeInput = document.getElementById('startTime');
    const endTimeInput = document.getElementById('endTime');
    const successModal = document.getElementById('successModal');
    const closeSuccessModal = document.getElementById('closeSuccessModal');

    const modal = document.getElementById('modal');
    const modalText = document.getElementById('modal-text');
    const modalSub = document.getElementById('modal-sub');

    let audioActivo = null;
    let mostrados = new Set();
    let horarios = JSON.parse(localStorage.getItem("horarios")) || [];

    // --- Opciones de actividades por categoría ---
    const actividades = {
        creativas: ["Pintar", "Escribir historias", "Tocar un instrumento", "Diseño gráfico", "Manualidades", "Fotografía"],
        sociales: ["Llamar a un amigo", "Salir a caminar", "Reunión familiar", "Voluntariado", "Juego de mesa", "Redes sociales (controlado)"],
        fisicas: ["Correr", "Hacer yoga", "Entrenar en el gym", "Bailar", "Natación", "Ciclismo"],
        relajacion: ["Meditación", "Escuchar música", "Leer un libro", "Respiración profunda", "Tomar una siesta", "Ver una película"],
        estudio: ["Matemáticas", "Programación", "Historia", "Idiomas", "Lectura académica", "Investigación"]
    };

    // --- Cargar actividades según categoría ---
    categorySelect.addEventListener('change', () => {
        const categoria = categorySelect.value;
        activitySelect.innerHTML = '<option value="">-- Selecciona una actividad --</option>';

        if (actividades[categoria]) {
            actividades[categoria].forEach(act => {
                const opt = document.createElement('option');
                opt.value = act;
                opt.textContent = act;
                activitySelect.appendChild(opt);
            });
            // Opción otro
            const optOtro = document.createElement('option');
            optOtro.value = "otro";
            optOtro.textContent = "Otro";
            activitySelect.appendChild(optOtro);
            customActivityContainer.classList.add("hidden");
        } else if (categoria === "otro") {
            customActivityContainer.classList.remove("hidden");
        } else {
            customActivityContainer.classList.add("hidden");
        }
    });

    // --- Mostrar input si se elige "otro" ---
    activitySelect.addEventListener('change', () => {
        if (activitySelect.value === "otro") {
            customActivityContainer.classList.remove("hidden");
        } else {
            customActivityContainer.classList.add("hidden");
        }
    });

    // --- Agregar horario ---
    addScheduleBtn.addEventListener('click', () => {
        let activity = activitySelect.value;
        if (activity === "otro" || categorySelect.value === "otro") {
            activity = customActivityInput.value.trim();
        }

        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;

        if (activity && startTime && endTime) {
            horarios.push({ activity, startTime, endTime });
            guardarHorarios();
            renderHorarios();

            // 🔄 Limpiar inputs
            categorySelect.value = "";
            activitySelect.innerHTML = '<option value="">-- Selecciona una actividad --</option>';
            customActivityInput.value = "";
            customActivityContainer.classList.add("hidden");
            startTimeInput.value = "";
            endTimeInput.value = "";

            // Modal éxito
            successModal.classList.remove('hidden');
        } else {
            mostrarError("⚠️ Por favor completa todos los campos antes de agregar un horario.");
        }
    });

    // --- Mostrar error bonito ---
    function mostrarError(mensaje) {
        let errorBox = document.getElementById("errorBox");

        if (!errorBox) {
            errorBox = document.createElement("div");
            errorBox.id = "errorBox";
            errorBox.className = "mt-2 p-2 text-sm text-red-700 bg-red-100 border border-red-400 rounded-lg transition";
            addScheduleBtn.parentNode.insertBefore(errorBox, addScheduleBtn); // Insertar arriba del botón
        }

        errorBox.textContent = mensaje;
        errorBox.classList.remove("hidden");

        // Ocultar después de 3 segundos
        setTimeout(() => {
            errorBox.classList.add("hidden");
        }, 3000);
    }

    // --- Guardar en localStorage ---
    function guardarHorarios() {
        localStorage.setItem("horarios", JSON.stringify(horarios));
    }

    // --- Cerrar modal de éxito ---
    closeSuccessModal.addEventListener('click', () => {
        successModal.classList.add('hidden');
    });

    // --- Renderizar horarios ---
    function renderHorarios() {
        scheduleList.innerHTML = '';
        horarios.forEach((h, i) => {
            const scheduleItem = document.createElement('div');
            scheduleItem.className = 'flex items-center justify-between p-3 bg-purple-50 rounded-lg border-l-4 border-purple-500';
            scheduleItem.innerHTML = `
                <div>
                    <p class="font-semibold text-gray-800">${h.activity}</p>
                    <p class="text-sm text-gray-600">${h.startTime} - ${h.endTime}</p>
                </div>
                <div class="space-x-2">
                    <button onclick="eliminarHorario(${i})" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">🗑️</button>
                </div>
            `;
            scheduleList.appendChild(scheduleItem);
        });
    }

    // --- Eliminar ---
    window.eliminarHorario = (index) => {
        horarios.splice(index, 1);
        guardarHorarios();
        renderHorarios();
    };

    // --- Modal motivador ---
    function mostrarModal(mensaje, motivacion, esFin = false) {
        modalText.innerText = mensaje;
        modalSub.innerText = motivacion;
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        if (esFin) {
            reproducirAudio("audioFin", 30);
        } else {
            reproducirAudio("audioInicio", 15);
        }
    }

    window.cerrarModal = () => {
        modal.classList.add('hidden');
        detenerAudio();
    };

    function reproducirAudio(id, duracion) {
        detenerAudio();
        audioActivo = document.getElementById(id);
        audioActivo.currentTime = 0;
        audioActivo.play();
        setTimeout(() => detenerAudio(), duracion * 1000);
    }

    function detenerAudio() {
        if (audioActivo) {
            audioActivo.pause();
            audioActivo.currentTime = 0;
            audioActivo = null;
        }
    }

    // --- Revisar horarios ---
    setInterval(() => {
        const ahora = new Date();
        const hora = ahora.toTimeString().slice(0,5);

        horarios.forEach(h => {
            if (hora === h.startTime && !mostrados.has(h.startTime + "-ini")) {
                mostrarModal(`¡Hora de ${h.activity}! 🚀`, "Tú puedes con esto, da tu 100%. 💪");
                mostrados.add(h.startTime + "-ini");
            }
            if (hora === h.endTime && !mostrados.has(h.endTime + "-fin")) {
                mostrarModal(`¡Terminaste ${h.activity}! 🎉`, "Gran trabajo, sigue avanzando paso a paso. 👏", true);
                mostrados.add(h.endTime + "-fin");
            }
        });
    }, 20000);

    // --- Inicializar ---
    renderHorarios();
});
