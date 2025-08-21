// Variable global para la lógica de los juegos
let gameData = {};

// Sopa de letras - Datos de los juegos
const wordSearchGames = {
  '1': {
    title: 'Ciencias Exactas',
    words: ['MATEMATICAS', 'FISICA', 'QUIMICA', 'BIOLOGIA', 'ASTRONOMIA', 'GEOMETRIA', 'ALGEBRA', 'CALCULO', 'ESTADISTICA', 'ECOLOGIA'],
  },
  '2': {
    title: 'Literatura Universal',
    words: ['SHAKESPEARE', 'CERVANTES', 'GABRIEL', 'GARCIA', 'MARQUEZ', 'POESIA', 'NOVELA', 'DRAMA', 'VERSO', 'PROSA', 'CATEDRAL'],
  },
  '3': {
    title: 'Tecnología y Programación',
    words: ['ALGORITMO', 'SOFTWARE', 'HARDWARE', 'PROGRAMACION', 'INTERNET', 'CODIGO', 'DATOS', 'CIBERSEGURIDAD', 'REDES', 'INTELIGENCIA', 'ARTIFICIAL'],
  },
  '4': {
    title: 'Historia Antigua',
    words: ['EGIPTO', 'ROMA', 'GRECIA', 'MESOPOTAMIA', 'CIVILIZACION', 'FARAON', 'EMPERADOR', 'CONQUISTA', 'IMPERIO', 'CULTURA', 'REYNA', 'SOCRATES', 'PLATON'],
  },
  '5': {
    title: 'Deportes Extremos',
    words: ['PARACAIDISMO', 'SKATEBOARDING', 'SNOWBOARD', 'ESCALADA', 'BUNGGEE', 'JUMPING', 'RAFTING', 'MOTOCROSS', 'SURFING', 'WINDSURFING'],
  },
};

// Función para agregar una nueva notificación
function showNotification(title, message) {
    const notificationList = document.getElementById('notification-list');
    const newNotification = document.createElement('div');
    newNotification.className = 'notification bg-blue-50 border-l-4 border-blue-500 p-4 rounded flex justify-between items-start';
    
    newNotification.innerHTML = `
        <div>
            <p class="font-semibold text-blue-800">${title}</p>
            <p class="text-sm text-blue-600">${message}</p>
            <p class="text-xs text-gray-500">Ahora</p>
        </div>
        <button class="text-xl text-gray-500 hover:text-red-500 delete-notification-btn">&times;</button>
    `;

    const deleteBtn = newNotification.querySelector('.delete-notification-btn');
    deleteBtn.addEventListener('click', () => {
        newNotification.remove();
        updateNotificationBadge(-1);
    });
    
    notificationList.prepend(newNotification);
    updateNotificationBadge(1);
}

function updateNotificationBadge(change) {
    const badge = document.getElementById('notificationBadge');
    let count = parseInt(badge.textContent);
    count = Math.max(0, count + change);
    badge.textContent = count;
}


// Lógica de la sopa de letras
const wordSearch = {
  state: {
    grid: [],
    words: [],
    foundWords: new Set(),
    startCell: null,
    endCell: null,
    selecting: false,
    score: 0,
    size: 0
  },

  init: function() {
    this.renderRandomGame();
    document.getElementById('newGame').addEventListener('click', () => this.renderRandomGame());
  },

  renderRandomGame: function() {
    const gameIds = Object.keys(wordSearchGames);
    const randomId = gameIds[Math.floor(Math.random() * gameIds.length)];
    this.newGame(randomId);
  },

  newGame: function(gameId) {
    const game = wordSearchGames[gameId];
    this.state.words = game.words.map(w => w.toUpperCase());
    this.state.foundWords = new Set();
    this.state.score = 0;
    this.state.size = this.calculateGridSize();

    document.getElementById('word-search-title').textContent = game.title;
    document.getElementById('wordsFound').textContent = this.state.foundWords.size;
    document.getElementById('totalWords').textContent = this.state.words.length;
    document.getElementById('gameScore').textContent = this.state.score;
    document.getElementById('word-search-completion-message').textContent = '';

    const wordListEl = document.getElementById('wordList');
    wordListEl.innerHTML = this.state.words.map(word => `<span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm" data-word="${word}">${word}</span>`).join('');

    this.state.grid = this.generateGrid(this.state.size, this.state.words);
    this.renderGrid();
    this.addEventListeners();
  },
  
  calculateGridSize: function() {
      const longestWord = Math.max(...this.state.words.map(word => word.length));
      return Math.max(longestWord + 2, 12);
  },

  generateGrid: function(size, words) {
    const grid = Array.from({ length: size }, () => Array(size).fill(''));
    const sortedWords = [...words].sort((a, b) => b.length - a.length);

    sortedWords.forEach(word => {
      let placed = false;
      let attempts = 0;
      while (!placed && attempts < 100) {
        const direction = Math.floor(Math.random() * 8);
        const startX = Math.floor(Math.random() * size);
        const startY = Math.floor(Math.random() * size);

        if (this.canPlaceWord(grid, word, startX, startY, direction)) {
          this.placeWord(grid, word, startX, startY, direction);
          placed = true;
        }
        attempts++;
      }
    });

    this.fillBlanks(grid);
    return grid;
  },

  canPlaceWord: function(grid, word, x, y, direction) {
    const size = grid.length;
    for (let i = 0; i < word.length; i++) {
      let newX = x;
      let newY = y;
      
      switch (direction) {
        case 0: newY += i; break;
        case 1: newY += i; newX += i; break;
        case 2: newX += i; break;
        case 3: newY -= i; newX += i; break;
        case 4: newY -= i; break;
        case 5: newY -= i; newX -= i; break;
        case 6: newX -= i; break;
        case 7: newY += i; newX -= i; break;
      }

      if (newX < 0 || newX >= size || newY < 0 || newY >= size) return false;
      if (grid[newY][newX] !== '' && grid[newY][newX] !== word[i]) return false;
    }
    return true;
  },

  placeWord: function(grid, word, x, y, direction) {
    for (let i = 0; i < word.length; i++) {
      let newX = x;
      let newY = y;
      switch (direction) {
        case 0: newY += i; break;
        case 1: newY += i; newX += i; break;
        case 2: newX += i; break;
        case 3: newY -= i; newX += i; break;
        case 4: newY -= i; break;
        case 5: newY -= i; newX -= i; break;
        case 6: newX -= i; break;
        case 7: newY += i; newX -= i; break;
      }
      grid[newY][newX] = word[i];
    }
  },

  fillBlanks: function(grid) {
    const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    for (let y = 0; y < grid.length; y++) {
      for (let x = 0; x < grid.length; x++) {
        if (grid[y][x] === '') {
          grid[y][x] = letters[Math.floor(Math.random() * letters.length)];
        }
      }
    }
  },

  renderGrid: function() {
    const gridEl = document.getElementById('wordGrid');
    gridEl.style.gridTemplateColumns = `repeat(${this.state.size}, 1fr)`;
    gridEl.innerHTML = '';
    
    this.state.grid.flat().forEach((letter, index) => {
      const cell = document.createElement('div');
      cell.className = 'word-cell';
      cell.textContent = letter;
      cell.dataset.index = index;
      gridEl.appendChild(cell);
    });
  },

  addEventListeners: function() {
    const gridEl = document.getElementById('wordGrid');
    if (!gridEl) return;

    gridEl.addEventListener('mousedown', this.handleMouseDown.bind(this));
    gridEl.addEventListener('mousemove', this.handleMouseMove.bind(this));
    gridEl.addEventListener('mouseup', this.handleMouseUp.bind(this));
    gridEl.addEventListener('mouseleave', this.handleMouseUp.bind(this));
  },

  handleMouseDown: function(e) {
    if (!e.target.classList.contains('word-cell')) return;
    this.state.selecting = true;
    this.state.startCell = e.target;
    this.state.endCell = e.target;
    this.highlightCells();
  },

  handleMouseMove: function(e) {
    if (!this.state.selecting || !e.target.classList.contains('word-cell')) return;
    this.state.endCell = e.target;
    this.highlightCells();
  },

  handleMouseUp: function() {
    if (!this.state.selecting) return;
    this.state.selecting = false;
    this.checkWord();
    this.clearHighlight();
    this.state.startCell = null;
    this.state.endCell = null;
  },
  
  clearHighlight: function() {
      document.querySelectorAll('.word-cell.highlighted').forEach(cell => {
          cell.classList.remove('highlighted');
      });
  },

  highlightCells: function() {
    this.clearHighlight();
    if (!this.state.startCell || !this.state.endCell) return;
    
    const size = this.state.size;
    const startIndex = parseInt(this.state.startCell.dataset.index);
    const endIndex = parseInt(this.state.endCell.dataset.index);
    
    const startY = Math.floor(startIndex / size);
    const startX = startIndex % size;
    const endY = Math.floor(endIndex / size);
    const endX = endIndex % size;

    const dx = endX - startX;
    const dy = endY - startY;

    if (dx === 0 || dy === 0 || Math.abs(dx) === Math.abs(dy)) {
        const stepX = dx === 0 ? 0 : dx > 0 ? 1 : -1;
        const stepY = dy === 0 ? 0 : dy > 0 ? 1 : -1;

        let currentX = startX;
        let currentY = startY;
        while (currentX !== endX + stepX || currentY !== endY + stepY) {
            const cell = document.querySelector(`.word-cell[data-index='${currentY * size + currentX}']`);
            if (cell) cell.classList.add('highlighted');
            currentX += stepX;
            currentY += stepY;
        }
    }
  },

  checkWord: function() {
    if (!this.state.startCell || !this.state.endCell) return;
    
    const size = this.state.size;
    const startIndex = parseInt(this.state.startCell.dataset.index);
    const endIndex = parseInt(this.state.endCell.dataset.index);
    
    const startY = Math.floor(startIndex / size);
    const startX = startIndex % size;
    const endY = Math.floor(endIndex / size);
    const endX = endIndex % size;

    const dx = endX - startX;
    const dy = endY - startY;

    if (dx === 0 || dy === 0 || Math.abs(dx) === Math.abs(dy)) {
        const stepX = dx === 0 ? 0 : dx > 0 ? 1 : -1;
        const stepY = dy === 0 ? 0 : dy > 0 ? 1 : -1;
        
        let word = '';
        let currentX = startX;
        let currentY = startY;
        
        while (currentX !== endX + stepX || currentY !== endY + stepY) {
            word += this.state.grid[currentY][currentX];
            currentX += stepX;
            currentY += stepY;
        }

        const reversedWord = word.split('').reverse().join('');
        
        if (this.state.words.includes(word) && !this.state.foundWords.has(word)) {
            this.state.foundWords.add(word);
            this.updateUI(word);
        } else if (this.state.words.includes(reversedWord) && !this.state.foundWords.has(reversedWord)) {
            this.state.foundWords.add(reversedWord);
            this.updateUI(reversedWord);
        }
    }
  },
  
  updateUI: function(foundWord) {
      document.getElementById('wordsFound').textContent = this.state.foundWords.size;
      const wordEl = document.querySelector(`[data-word='${foundWord}']`);
      if (wordEl) {
          wordEl.classList.add('line-through', 'text-gray-400');
          wordEl.classList.remove('bg-blue-100', 'text-blue-800');
      }
      
      this.state.score += foundWord.length;
      document.getElementById('gameScore').textContent = this.state.score;
      
      document.querySelectorAll('.word-cell.highlighted').forEach(cell => {
          cell.classList.add('found');
          cell.classList.remove('highlighted');
      });

      // Lógica para el final del juego
      if (this.state.foundWords.size === this.state.words.length) {
          setTimeout(() => {
              document.getElementById('word-search-completion-message').textContent = '¡Felicidades, completaste este nivel!';
              showNotification('🏆 Juego Completado', '¡Excelente! Has encontrado todas las palabras en la sopa de letras.');
          }, 500);
      }
  }
};

// Lógica del Juego de Memoria
const memoryGame = {
    state: {
        cards: [],
        flippedCards: [],
        matchedPairs: 0,
        attempts: 0,
        canFlip: true,
        difficulty: 'facil'
    },
    
    difficultySettings: {
        'facil': {
            emojis: ['🍎', '🍌', '🍓', '🍇', '🍍', '🍊', '🍉', '🥝'],
            size: 'grid-cols-4'
        },
        'normal': {
            emojis: ['🍎', '🍌', '🍓', '🍇', '🍍', '🍊', '🍉', '🥝', '🍋', '🍐', '🍑', '🍒', '🥭', '🍍', '🥑'],
            size: 'grid-cols-5'
        },
        'dificil': {
            emojis: ['🍎', '🍌', '🍓', '🍇', '🍍', '🍊', '🍉', '🥝', '🍋', '🍐', '🍑', '🍒', '🥭', '🥑', '🥥', '🫐', '🫒', '🌶️'],
            size: 'grid-cols-6'
        }
    },
    
    init: function() {
        document.getElementById('newMemoryGame').addEventListener('click', () => this.newGame(this.state.difficulty));
        
        document.querySelectorAll('#memory-difficulty-buttons .difficulty-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const difficulty = e.target.dataset.difficulty;
                this.state.difficulty = difficulty;
                this.newGame(difficulty);
                
                // Actualiza el estado activo de los botones
                document.querySelectorAll('.difficulty-btn').forEach(btn => btn.classList.remove('active'));
                e.target.classList.add('active');
            });
        });
        
        // Cargar el juego inicial en la dificultad "facil"
        document.querySelector('[data-difficulty="facil"]').click();
    },

    newGame: function(difficulty) {
        this.state.flippedCards = [];
        this.state.matchedPairs = 0;
        this.state.attempts = 0;
        this.state.canFlip = true;
        document.getElementById('memory-completion-message').textContent = '';

        const settings = this.difficultySettings[difficulty];
        let emojis = [...settings.emojis, ...settings.emojis];
        this.state.cards = this.shuffle(emojis);

        const board = document.getElementById('memory-board');
        board.innerHTML = '';
        board.className = `grid gap-2 p-4 rounded-lg shadow-inner justify-center items-center ${settings.size}`;

        this.state.cards.forEach((emoji, index) => {
            const card = document.createElement('div');
            card.className = 'card';
            card.dataset.index = index;
            
            const cardFace = document.createElement('div');
            cardFace.className = 'card-face';
            cardFace.textContent = emoji;

            const cardBack = document.createElement('div');
            cardBack.className = 'card-back';
            cardBack.textContent = '?';

            card.appendChild(cardFace);
            card.appendChild(cardBack);
            
            card.addEventListener('click', () => this.flipCard(card));
            board.appendChild(card);
        });

        this.updateUI();
    },

    shuffle: function(array) {
        return array.sort(() => Math.random() - 0.5);
    },

    flipCard: function(card) {
        if (!this.state.canFlip || card.classList.contains('is-flipped')) {
            return;
        }
        
        card.classList.add('is-flipped');
        this.state.flippedCards.push(card);

        if (this.state.flippedCards.length === 2) {
            this.state.canFlip = false;
            this.state.attempts++;
            setTimeout(() => this.checkMatch(), 1000);
        }
    },

    checkMatch: function() {
        const [card1, card2] = this.state.flippedCards;
        const emoji1 = card1.querySelector('.card-face').textContent;
        const emoji2 = card2.querySelector('.card-face').textContent;

        if (emoji1 === emoji2) {
            card1.style.pointerEvents = 'none';
            card2.style.pointerEvents = 'none';
            card1.classList.add('match');
            card2.classList.add('match');
            this.state.matchedPairs++;
            if (this.state.matchedPairs === this.state.cards.length / 2) {
                setTimeout(() => {
                    document.getElementById('memory-completion-message').textContent = '¡Felicidades, completaste este nivel!';
                    showNotification('🏆 Nivel Completado', `¡Felicidades! Has terminado el nivel ${this.state.difficulty} de Memoria.`);
                }, 500);
            }
        } else {
            card1.classList.remove('is-flipped');
            card2.classList.remove('is-flipped');
        }

        this.state.flippedCards = [];
        this.state.canFlip = true;
        this.updateUI();
    },

    updateUI: function() {
        document.getElementById('memory-attempts').textContent = this.state.attempts;
        document.getElementById('memory-score').textContent = this.state.matchedPairs;
    }
};


// Evento que se ejecuta cuando el DOM está cargado
document.addEventListener('DOMContentLoaded', () => {
  wordSearch.init();
  memoryGame.init();
});