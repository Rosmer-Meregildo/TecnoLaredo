// juegos.js: Funcionalidad exclusiva para la página del juego

document.addEventListener('DOMContentLoaded', () => {
    
    // --- Juego de Sopa de Letras (Empalabrados) ---
    const wordGridElement = document.getElementById('wordGrid');
    if (wordGridElement) {
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
            wordGridElement.innerHTML = '';
            gameGrid.forEach((row, rowIndex) => {
                row.forEach((letter, colIndex) => {
                    const cell = document.createElement('div');
                    cell.className = 'letter-cell bg-white border border-gray-300 rounded text-sm font-bold';
                    cell.textContent = letter;
                    cell.dataset.row = rowIndex;
                    cell.dataset.col = colIndex;
                    cell.addEventListener('click', () => selectCell(cell, rowIndex, colIndex));
                    wordGridElement.appendChild(cell);
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
            if (words.includes(selectedWord) && !foundWords.includes(selectedWord)) {
                foundWords.push(selectedWord);
                markWordAsFound(selectedWord);
                updateScore();
                clearSelection();
            }
        }

        function markWordAsFound(word) {
            selectedCells.forEach(cell => {
                const cellElement = document.querySelector(`[data-row="${cell.row}"][data-col="${cell.col}"]`);
                cellElement.classList.add('found-word');
            });
            const wordElements = document.querySelectorAll('#wordList span');
            wordElements.forEach(element => {
                if (element.textContent === word) {
                    element.classList.replace('bg-blue-100', 'bg-green-100');
                    element.classList.replace('text-blue-800', 'text-green-800');
                    element.innerHTML = `${word} ✓`;
                }
            });
        }

        function clearSelection() {
            selectedCells = [];
            document.querySelectorAll('.selected').forEach(cell => cell.classList.remove('selected'));
        }

        function updateScore() {
            gameScore += 100;
            document.getElementById('gameScore').textContent = gameScore;
            document.getElementById('wordsFound').textContent = foundWords.length;
            if (foundWords.length === words.length) {
                setTimeout(() => alert('¡Felicidades! Has encontrado todas las palabras 🎉'), 500);
            }
        }

        document.getElementById('newGame').addEventListener('click', () => {
            foundWords = [];
            selectedCells = [];
            gameScore = 0;
            document.getElementById('gameScore').textContent = '0';
            document.getElementById('wordsFound').textContent = '0';
            const wordElements = document.querySelectorAll('#wordList span');
            wordElements.forEach((element, index) => {
                element.className = 'px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm';
                element.textContent = words[index];
            });
            initializeGame();
        });

        initializeGame();
    }
});