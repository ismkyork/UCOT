const grid = document.getElementById('calendarGrid');

        // Crear las etiquetas de las 24 horas automáticamente
        for (let h = 0; h < 24; h++) {
            const label = document.createElement('div');
            label.className = 'time-label';
            label.style.gridRow = h + 2; // +2 porque la fila 1 es la de los días
            label.textContent = h === 12 ? "12 PM" : h > 12 ? (h-12) + " PM" : h === 0 ? "12 AM" : h + " AM";
            grid.appendChild(label);
        }

        // Lógica del formulario
        document.getElementById('eventForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const title = document.getElementById('title').value;
            const dayCol = document.getElementById('daySelect').value;
            const startRow = parseInt(document.getElementById('startHour').value) + 2;
            const duration = parseInt(document.getElementById('duration').value);

            const event = document.createElement('div');
            event.className = 'event-card';
            event.style.gridColumn = dayCol;
            event.style.gridRow = `${startRow} / span ${duration}`;
            event.innerHTML = `<b>${title}</b><br>${startRow-2}:00`;

            grid.appendChild(event);
            this.reset();
        });