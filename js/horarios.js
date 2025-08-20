// horarios.js: Funcionalidad exclusiva para la página de horarios

document.addEventListener('DOMContentLoaded', () => {
    
    // --- Funcionalidad para agregar al horario ---
    const addScheduleBtn = document.getElementById('addSchedule');
    const scheduleList = document.getElementById('scheduleList');
    const activityInput = document.getElementById('activityInput');
    const startTimeInput = document.getElementById('startTime');
    const endTimeInput = document.getElementById('endTime');

    if (addScheduleBtn) {
        addScheduleBtn.addEventListener('click', () => {
            const activity = activityInput.value;
            const startTime = startTimeInput.value;
            const endTime = endTimeInput.value;

            if (activity && startTime && endTime) {
                const scheduleItem = document.createElement('div');
                scheduleItem.className = 'flex items-center justify-between p-3 bg-purple-50 rounded-lg border-l-4 border-purple-500';
                scheduleItem.innerHTML = `
                    <div>
                        <p class="font-semibold text-gray-800">${activity}</p>
                        <p class="text-sm text-gray-600">${startTime} - ${endTime}</p>
                    </div>
                    <span class="text-2xl">📝</span>
                `;
                scheduleList.appendChild(scheduleItem);

                // Limpiar campos
                activityInput.value = '';
                startTimeInput.value = '';
                endTimeInput.value = '';

                // Mensaje de éxito
                alert('¡Actividad agregada al horario! 🎉');
            } else {
                alert('Por favor completa todos los campos');
            }
        });
    }
});