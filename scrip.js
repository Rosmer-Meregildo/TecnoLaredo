// scrip.js
        // Navigation functionality
        const navButtons = document.querySelectorAll('.nav-btn');
        const sections = document.querySelectorAll('.section');

        navButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetSection = button.dataset.section;
                
                // Update active nav button
                navButtons.forEach(btn => {
                    btn.classList.remove('bg-blue-100', 'text-blue-600');
                    btn.classList.add('text-gray-600');
                });
                button.classList.add('bg-blue-100', 'text-blue-600');
                button.classList.remove('text-gray-600');
                
                // Show target section
                sections.forEach(section => {
                    section.classList.add('hidden');
                });
                document.getElementById(targetSection).classList.remove('hidden');
            });
        });

        // Notification panel
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationPanel = document.getElementById('notificationPanel');
        const closeNotifications = document.getElementById('closeNotifications');

        notificationBtn.addEventListener('click', () => {
            notificationPanel.classList.remove('translate-x-full');
        });

        closeNotifications.addEventListener('click', () => {
            notificationPanel.classList.add('translate-x-full');
        });

        // Schedule functionality
        const addScheduleBtn = document.getElementById('addSchedule');
        const scheduleList = document.getElementById('scheduleList');

        addScheduleBtn.addEventListener('click', () => {
            const activity = document.getElementById('activityInput').value;
            const startTime = document.getElementById('startTime').value;
            const endTime = document.getElementById('endTime').value;

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

                // Clear inputs
                document.getElementById('activityInput').value = '';
                document.getElementById('startTime').value = '';
                document.getElementById('endTime').value = '';

                // Show success message
                alert('¡Actividad agregada al horario! 🎉');
            } else {
                alert('Por favor completa todos los campos');
            }
        });

        // Study Methods Functionality
        
        // Pomodoro Timer
        let pomodoroTimer = null;
        let pomodoroTime = 25 * 60; // 25 minutes in seconds
        let isRunning = false;
        let isBreak = false;

        document.getElementById('pomodoroBtn').addEventListener('click', () => {
            document.getElementById('pomodoroModal').classList.add('show');
        });

        document.getElementById('closePomodoroModal').addEventListener('click', () => {
            document.getElementById('pomodoroModal').classList.remove('show');
        });

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
            pomodoroTime = isBreak ? 5 * 60 : 25 * 60;
            updatePomodoroDisplay();
            document.getElementById('timerStatus').textContent = 'Listo para estudiar';
        });

        function updatePomodoroTimer() {
            pomodoroTime--;
            updatePomodoroDisplay();
            
            if (pomodoroTime <= 0) {
                clearInterval(pomodoroTimer);
                isRunning = false;
                isBreak = !isBreak;
                pomodoroTime = isBreak ? 5 * 60 : 25 * 60;
                alert(isBreak ? '¡Tiempo de descanso! 😊' : '¡Hora de estudiar! 📚');
                document.getElementById('timerStatus').textContent = isBreak ? 'Tiempo de descanso' : 'Listo para estudiar';
                updatePomodoroDisplay();
            }
        }

        function updatePomodoroDisplay() {
            const minutes = Math.floor(pomodoroTime / 60);
            const seconds = pomodoroTime % 60;
            document.getElementById('timerDisplay').textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            // Update progress circle
            const totalTime = isBreak ? 5 * 60 : 25 * 60;
            const progress = (totalTime - pomodoroTime) / totalTime;
            const circumference = 2 * Math.PI * 45;
            const offset = circumference - (progress * circumference);
            document.getElementById('progressCircle').style.strokeDashoffset = offset;
        }

        // Mind Map Modal
        document.getElementById('mindMapBtn').addEventListener('click', () => {
            document.getElementById('mindMapModal').classList.add('show');
        });

        document.getElementById('closeMindMapModal').addEventListener('click', () => {
            document.getElementById('mindMapModal').classList.remove('show');
        });

        // Cornell Notes Modal
        document.getElementById('cornellBtn').addEventListener('click', () => {
            document.getElementById('cornellModal').classList.add('show');
        });

        document.getElementById('closeCornellModal').addEventListener('click', () => {
            document.getElementById('cornellModal').classList.remove('show');
        });

        // Highlight Techniques Modal
        document.getElementById('highlightBtn').addEventListener('click', () => {
            document.getElementById('highlightModal').classList.add('show');
        });

        document.getElementById('closeHighlightModal').addEventListener('click', () => {
            document.getElementById('highlightModal').classList.remove('show');
        });

        // Summary Modal
        document.getElementById('summaryBtn').addEventListener('click', () => {
            document.getElementById('summaryModal').classList.add('show');
        });

        document.getElementById('closeSummaryModal').addEventListener('click', () => {
            document.getElementById('summaryModal').classList.remove('show');
        });

        // Schema Modal
        document.getElementById('schemaBtn').addEventListener('click', () => {
            document.getElementById('schemaModal').classList.add('show');
        });

        document.getElementById('closeSchemaModal').addEventListener('click', () => {
            document.getElementById('schemaModal').classList.remove('show');
        });

        // Flashcards functionality
        const flashcards = [
            { question: "¿Cuál es la capital de Francia?", answer: "París" },
            { question: "¿Cuántos continentes hay?", answer: "7 continentes" },
            { question: "¿Quién escribió Don Quijote?", answer: "Miguel de Cervantes" }
        ];

        let currentCardIndex = 0;

        document.getElementById('flashcardsBtn').addEventListener('click', () => {
            document.getElementById('flashcardsModal').classList.add('show');
            showCurrentCard();
        });

        document.getElementById('closeFlashcardsModal').addEventListener('click', () => {
            document.getElementById('flashcardsModal').classList.remove('show');
        });

        document.getElementById('flipCard').addEventListener('click', () => {
            document.getElementById('flashcard').classList.toggle('flipped');
        });

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

        // Close modals when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.remove('show');
                }
            });
        });

        // Word Search Game
        const gameGrid = [
            ['E', 'S', 'T', 'U', 'D', 'I', 'O', 'X'],
            ['X', 'L', 'I', 'B', 'R', 'O', 'Y', 'Z'],
            ['N', 'O', 'T', 'A', 'W', 'Q', 'E', 'R'],
            ['E', 'X', 'A', 'M', 'E', 'N', 'T', 'Y'],
            ['C', 'L', 'A', 'S', 'E', 'U', 'I', 'O'],
            ['P', 'Q', 'R', 'S', 'T', 'V', 'W', 'X'],
            ['Y', 'Z', 'A', 'B', 'C', 'D', 'E', 'F'],
            ['G', 'H', 'I', 'J', 'K', 'L', 'M', 'N']
        ];

        const words = ['ESTUDIO', 'LIBRO', 'NOTA', 'EXAMEN', 'CLASE'];
        let foundWords = [];
        let selectedCells = [];
        let gameScore = 0;

        function initializeGame() {
            const wordGrid = document.getElementById('wordGrid');
            wordGrid.innerHTML = '';
            
            gameGrid.forEach((row, rowIndex) => {
                row.forEach((letter, colIndex) => {
                    const cell = document.createElement('div');
                    cell.className = 'letter-cell bg-white border border-gray-300 rounded text-sm font-bold';
                    cell.textContent = letter;
                    cell.dataset.row = rowIndex;
                    cell.dataset.col = colIndex;
                    
                    cell.addEventListener('click', () => selectCell(cell, rowIndex, colIndex));
                    wordGrid.appendChild(cell);
                });
            });
        }

        function selectCell(cell, row, col) {
            if (cell.classList.contains('selected')) {
                cell.classList.remove('selected');
                selectedCells = selectedCells.filter(c => !(c.row === row && c.col === col));
            } else {
                cell.classList.add('selected');
                selectedCells.push({row, col, letter: gameGrid[row][col]});
            }
            
            checkForWord();
        }

        function checkForWord() {
            if (selectedCells.length < 3) return;
            
            const selectedWord = selectedCells.map(cell => cell.letter).join('');
            const reversedWord = selectedCells.map(cell => cell.letter).reverse().join('');
            
            if (words.includes(selectedWord) && !foundWords.includes(selectedWord)) {
                foundWords.push(selectedWord);
                markWordAsFound(selectedWord);
                updateScore();
                clearSelection();
            } else if (words.includes(reversedWord) && !foundWords.includes(reversedWord)) {
                foundWords.push(reversedWord);
                markWordAsFound(reversedWord);
                updateScore();
                clearSelection();
            }
        }

        function markWordAsFound(word) {
            selectedCells.forEach(cell => {
                const cellElement = document.querySelector(`[data-row="${cell.row}"][data-col="${cell.col}"]`);
                cellElement.classList.remove('selected');
                cellElement.classList.add('found-word');
            });
            
            // Update word list
            const wordElements = document.querySelectorAll('#wordList span');
            wordElements.forEach(element => {
                if (element.textContent === word) {
                    element.classList.remove('bg-blue-100', 'text-blue-800');
                    element.classList.add('bg-green-100', 'text-green-800');
                    element.innerHTML = `${word} ✓`;
                }
            });
        }

        function clearSelection() {
            selectedCells = [];
            document.querySelectorAll('.selected').forEach(cell => {
                cell.classList.remove('selected');
            });
        }

        function updateScore() {
            gameScore += 100;
            document.getElementById('gameScore').textContent = gameScore;
            document.getElementById('wordsFound').textContent = foundWords.length;
            
            if (foundWords.length === words.length) {
                setTimeout(() => {
                    alert('¡Felicidades! Has encontrado todas las palabras 🎉');
                }, 500);
            }
        }

        document.getElementById('newGame').addEventListener('click', () => {
            foundWords = [];
            selectedCells = [];
            gameScore = 0;
            document.getElementById('gameScore').textContent = '0';
            document.getElementById('wordsFound').textContent = '0';
            
            // Reset word list
            const wordElements = document.querySelectorAll('#wordList span');
            wordElements.forEach((element, index) => {
                element.className = 'px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm';
                element.textContent = words[index];
            });
            
            initializeGame();
        });

        // Initialize game on load
        initializeGame();

        // Simulate periodic notifications
        setInterval(() => {
            const notifications = [
                "¡Hora de un descanso! 😊",
                "¿Ya revisaste tu horario? 📅",
                "¡Tiempo de actividad física! 🏃‍♂️",
                "¡Nuevo tip de estudio disponible! 💡"
            ];
            
            const randomNotification = notifications[Math.floor(Math.random() * notifications.length)];
            
            // This would normally trigger a real notification
            console.log("Notificación:", randomNotification);
        }, 30000); // Every 30 seconds
