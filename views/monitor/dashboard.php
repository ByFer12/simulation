<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard del Monitor</title>
    <link rel="stylesheet" href="/Proyectos/simulacionTrafico/public/css/dashboard.css">
    <script src="../public/js/simulacionCanvas.js"></script>
</head>

<body>
    <div class="dashboard-container">
        <nav class="dashboard-nav">
            <h3>Tiempo de Sesión<br /> <span id="session-time">00:00:00</span></h3>
            <br />
            <div class="user-info">
                <span class="user-icon">&#128100;</span> <span class="user-name"><?php echo $_SESSION['nombre'] ?? 'Monitor'; ?></span>
            </div>
            <ul>
                <li><a href="#">Inicio</a></li>
            </ul>
            <div class="logout-button">
                <a href="/Proyectos/simulacionTrafico/logout" class="btn-logout">Cerrar Sesión</a>
            </div>
        </nav>

        <main class="dashboard-content">
            <section class="main-area">
                <section class="simulation-area">
                    <h2>Área de Simulación</h2>
                    <div class="simulation-buttons">
                        <button id="iniciarSimulacion" class="btn-simulation active">Iniciar Simulación</button>
                        <button id="detenerSimulacion" class="btn-simulation disabled" disabled>Detener Simulación</button>
                    </div>
                    <div class="simulation-canvas-container">
                        <canvas id="simulationCanvas" width="1150" height="750" style="border: 1px solid #ccc;"></canvas>
                    </div>
                </section>

                <section class="controls-area">
                    <h2>Controles de Simulación</h2>
                    <div class="controls-column">
                        <div class="control-item">
                            <label for="cargarArchivo">Cargar Archivo JSON:</label>
                            <input type="file" id="cargarArchivo" accept=".json">
                        </div>
                        <br>
                        <div class="control-item">
                            <h3>Modificar Semáforo</h3>
                            <div class="semaphore-controls">
                                <label for="seleccionarSemaforo">Seleccionar Semáforo:</label>
                                <select id="seleccionarSemaforo">
                                <option value="horizontal1">-------Seleccionar--------</option>
                                    <option value="horizontal1">Semáforo horizontal1</option>
                                    <option value="horizontal2">Semáforo horizontal2</option>
                                    <option value="vertical1">Semáforo vertical1</option>
                                    <option value="vertical2">Semáforo vertical2</option>
                                </select>
                                <div class="modify-semaphore-inputs">
                                    <label for="habilitarModificacion">
                                        Habilitar Modificación:
                                        <input type="checkbox" id="habilitarModificacion">
                                    </label>
                                    <div class="time-input">
                                        <label for="rojoModificar">Rojo:</label>
                                        <input type="number" id="rojoModificar" value="0" disabled>
                                        <span>segundos</span>
                                    </div>
                                    <div class="time-input">
                                        <label for="amarilloModificar">Amarillo:</label>
                                        <input type="number" id="amarilloModificar" value="0" disabled>
                                        <span>segundos</span>
                                    </div>
                                    <div class="time-input">
                                        <label for="verdeModificar">Verde:</label>
                                        <input type="number" id="verdeModificar" value="0" disabled>
                                        <span>segundos</span>
                                    </div>

                                        <label for="verdeModificar">Comentario:</label>
                                        <textarea type="text" id="comentario" style="height: 150px; width:325px; " placeholder="Escriba un comentraio relacionado" disabled></textarea>
     
                                    <div class="modify-input">
                                        <button type="button" id="guardarTiempo" hidden>Guardar</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </section>
            </section>
        </main>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const seleccionarSemaforo = document.getElementById('seleccionarSemaforo');
            const rojoInput = document.getElementById('rojoModificar');
            const amarilloInput = document.getElementById('amarilloModificar');
            const verdeInput = document.getElementById('verdeModificar');
            const nuevoTiempoInput = document.getElementById('nuevoTiempo');
            const guardarButton = document.getElementById('guardarTiempo');
            const comentario= document.getElementById('comentario');
            const btnIniciar = document.getElementById('iniciarSimulacion');
            const btnDetener = document.getElementById('detenerSimulacion');
            const habilitarCheckbox = document.getElementById('habilitarModificacion');



            habilitarCheckbox.addEventListener('change', function() {

                rojoInput.disabled = !this.checked;

                amarilloInput.disabled = !this.checked;

                verdeInput.disabled = !this.checked;

                comentario.disabled = !this.checked;

               guardarButton.hidden = !this.checked;



            });
            btnIniciar.addEventListener('click', function() {
                btnIniciar.classList.add('disabled');
                btnIniciar.disabled = true;
                btnDetener.classList.remove('disabled');
                btnDetener.disabled = false;
                btnDetener.style.backgroundColor = "#ff4d4d"; // color rojo
                btnDetener.style.color = "#fff"; // texto blanco
                btnDetener.style.borderColor = "#ff4d4d";
                btnDetener.style.opacity = "0.8"; // efecto de opacidad
                btnDetener.style.cursor = "not-allowed";
            });

            btnDetener.addEventListener('click', function() {

                btnDetener.classList.add('disabled');
                btnDetener.disabled = true;
                btnIniciar.classList.remove('disabled');
                btnIniciar.disabled = false;
            });

            seleccionarSemaforo.addEventListener('change', function() {
                const semaforoSeleccionado = this.value;
                // Aquí podrías hacer una llamada AJAX para obtener los tiempos actuales del semáforo seleccionado
                // Por ahora, quemaremos valores basados en la selección para simular
                if (semaforoSeleccionado === '1') {
                    rojoInput.value = 35;
                    amarilloInput.value = 5;
                    verdeInput.value = 30;
                } else if (semaforoSeleccionado === '2') {
                    rojoInput.value = 40;
                    amarilloInput.value = 4;
                    verdeInput.value = 25;
                } else if (semaforoSeleccionado === '3') {
                    rojoInput.value = 30;
                    amarilloInput.value = 6;
                    verdeInput.value = 32;
                } else if (semaforoSeleccionado === '4') {
                    rojoInput.value = 45;
                    amarilloInput.value = 3;
                    verdeInput.value = 20;
                }
                nuevoTiempoInput.disabled = false;
                guardarButton.disabled = false;
            });



            let startTime = localStorage.getItem('sessionStartTime');
            const sessionTimeDisplay = document.getElementById('session-time');

            if (!startTime) {
                startTime = new Date().getTime();
                localStorage.setItem('sessionStartTime', startTime);
            } else {
                startTime = parseInt(startTime, 10);
            }

            function updateSessionTime() {
                const currentTime = new Date().getTime();
                const elapsedTime = Math.floor((currentTime - startTime) / 1000); // Segundos transcurridos

                const hours = Math.floor(elapsedTime / 3600);
                const minutes = Math.floor((elapsedTime % 3600) / 60);
                const seconds = elapsedTime % 60;

                const formattedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                sessionTimeDisplay.textContent = formattedTime;
            }

            setInterval(updateSessionTime, 1000); // Actualizar cada segundo

            window.addEventListener('beforeunload', function() {
                localStorage.removeItem('sessionStartTime');
            });
        });
    </script>

    <footer class="dashboard-footer">
        hecho por byron torres
    </footer>
</body>

</html>