<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid p-4">
                    <h1>CALENDARIO</h1>

    <div class="calendar-container">
            <aside class="sidebar">
            <h1 class="h3 mb-4 text-gray-800" style="color: var(--ucot-blue);">Reservar Cita</h1>        
                <form id="eventForm">
                    <input type="text" id="title" placeholder="Ejemplo: Clase de Arte" required>
                    <select id="daySelect">
                        <option value="2">Lunes</option>
                        <option value="3">Martes</option>
                        <option value="4">Miércoles</option>
                        <option value="5">Jueves</option>
                        <option value="6">Viernes</option>
                    </select>
                    <input type="number" id="startHour" placeholder="Hora inicio (0-23)" min="0" max="23" required>
                    <input type="number" id="duration" placeholder="Duración (horas)" min="1" required>
                    <button type="submit">Agendar</button>
                </form>
            </aside>

            <div class="calendar-grid" id="calendarGrid">
                <div class="time-label" style="grid-column: 1; grid-row: 1;">GMT-04</div>
                <div class="day-header" style="grid-column: 2; grid-row: 1;">DOM </div>
                <div class="day-header active" style="grid-column: 3; grid-row: 1;">LUN 2</div>
                <div class="day-header" style="grid-column: 4; grid-row: 1;">MAR </div>
                <div class="day-header" style="grid-column: 5; grid-row: 1;">MIÉ </div>
                <div class="day-header" style="grid-column: 6; grid-row: 1;">JUE </div>
                <div class="day-header" style="grid-column: 7; grid-row: 1;">VIE </div>
                <div class="day-header" style="grid-column: 8; grid-row: 1;">SÁB </div>
            </div>
        </div>

<?= $this->endSection() ?>