document.addEventListener('DOMContentLoaded', () => {
    // ====== Inicialización del canvas ======
    const canvas = document.getElementById('simulationCanvas');
    const ctx = canvas.getContext('2d');
    const { width: canvasWidth, height: canvasHeight } = canvas;
    let file;
    // Botones de control
    const iniciarSimulacion = document.getElementById('iniciarSimulacion');
    const detenerSimulacion = document.getElementById('detenerSimulacion');
    const cargarArchivoInput = document.getElementById('cargarArchivo');
    const seleccionarSemaforo = document.getElementById('seleccionarSemaforo');
   // const habilitarModificacion = document.getElementById('habilitarModificacion');
    const rojoModificar = document.getElementById('rojoModificar');
    const amarilloModificar = document.getElementById('amarilloModificar');
    const verdeModificar = document.getElementById('verdeModificar');
    const guardarTiempo = document.getElementById('guardarTiempo');
    let initialVehiclesData = null;
    let initialSemaphoresData = null;
    let idSimulacion = null;
    
    // Variables de control de la animación
    let running = false;
    let animationFrameId;
    let vehicles = [];
    let trafficLightsConfig = [];

    const colors = {
        road: '#555', laneDivider: '#fff',
        semaphore: { red: '#f00', yellow: '#ff0', green: '#0f0' },
        background: '#f0f0f0'
    };
    const roadWidth = 200;
    const laneWidth = roadWidth / 4;
    const horizontalCenter = canvasWidth / 2;
    const verticalCenter = canvasHeight / 2;
    const semaphoreRadius = 10;
    const vehicleSize = 30;
    const decelerationZone = 100;
    const safeDistance = 40;

    const vehicleTypes = {
        car:         { color: '#337ab7', speed: 1.5, imageSrc: '/Proyectos/simulacionTrafico/public/img/coche.png' },
        bus:         { color: '#ff5733', speed: 1.4, imageSrc: '/Proyectos/simulacionTrafico/public/img/autobus.png' },
        truck:       { color: '#33cc33', speed: 1.1, imageSrc: '/Proyectos/simulacionTrafico/public/img/camionn.png' },
        motorcycle:  { color: '#9933ff', speed: 2.0, imageSrc: '/Proyectos/simulacionTrafico/public/img/motocicleta.png' },
        bike:        { color: '#ffcc00', speed: 0.6, imageSrc: '/Proyectos/simulacionTrafico/public/img/bicicleta.png' },
        camioneta:   { color: '#ffcc00', speed: 1.2, imageSrc: '/Proyectos/simulacionTrafico/public/img/camioneta.png' },
        contenedor: { color: '#ffcc00', speed: 1.0,  imageSrc: '/Proyectos/simulacionTrafico/public/img/contenedor.png' },
    };

    const loadImages = () => {
        return Promise.all(Object.values(vehicleTypes).map(type => {
            return new Promise(resolve => {
                const img = new Image();
                img.onload = () => {
                    type.image = img;
                    resolve();
                };
                img.src = type.imageSrc;
            });
        }));
    };
    // Al cambiar la opción del select, se actualizan los inputs con los tiempos actuales (convertidos a segundos)
    seleccionarSemaforo.addEventListener('change', () => {
        const selectedId = seleccionarSemaforo.value;
        // Buscamos el semáforo en la configuración (asumiendo que trafficLightsConfig se cargó)
        const semaforo = trafficLightsConfig.find(light => light.id === selectedId);
        if (semaforo) {
            rojoModificar.value = semaforo.timings.red / 1000;
            amarilloModificar.value = semaforo.timings.yellow / 1000;
            verdeModificar.value = semaforo.timings.green / 1000;
        }
    });

    // Al hacer click en "Guardar", se actualizan los tiempos (convirtiendo a milisegundos)
    guardarTiempo.addEventListener('click',  async() => {
        const selectedId = seleccionarSemaforo.value;
        const semaforo = trafficLightsConfig.find(light => light.id === selectedId);
        const comentario= document.getElementById('comentario').value;
        if (semaforo) {

            semaforo.timings.red = Number(rojoModificar.value) * 1000;
            semaforo.timings.yellow = Number(amarilloModificar.value) * 1000;
            semaforo.timings.green = Number(verdeModificar.value) * 1000;
            // Reiniciamos el startTime para que se apliquen los cambios de inmediato
            semaforo.startTime = Date.now();
            const semaforosData = trafficLightsConfig.map(light => ({
                id: light.id,
                red: light.timings.red,
                yellow: light.timings.yellow,
                green: light.timings.green
            }));

            try {
                const response = await fetch('../Controllers/Monitor/IterationSave.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        simulacion_id: idSimulacion,  // Asegúrate de tener este ID definido
                        semaforos: semaforosData,
                        comentario: comentario
                    })
                });
    
                const result = await response.json();
                console.log("La respuesta del servidor es: ",result); 
                if (result.success) {
                    document.getElementById('comentario').value = '';
                    alert("Iteración guardada correctamente.");
                } else {
                    alert("Error al guardar la iteración.");
                }
            } catch (error) {
                console.error("Error en la comunicación con el servidor:", error);
            }
            alert("Tiempo del semáforo actualizado.");
        }
    });
    const resetVehicles = (initialVehicles) => {
        vehicles = initialVehicles.map(config => ({
            ...config,
            // Si en el JSON se define la velocidad, se utiliza; si no, se usa la velocidad por defecto del tipo
            speed: config.speed !== undefined ? config.speed : vehicleTypes[config.type].speed,
            hasPassedSemaphore: false
        }));
    };
    const initializeTrafficLights = (semaphoreData) => {
        if (semaphoreData.length !== 4) {
            console.error("Error: El archivo JSON debe contener exactamente 4 semáforos.");
            trafficLightsConfig = [];
            return false;
        }

        const initialColors = {
            horizontal1: 'red',
            horizontal2: 'red',
            vertical1: 'green',
            vertical2: 'green'
        };

        trafficLightsConfig = semaphoreData.map(data => {
            if (!data.id || !data.timings || !initialColors.hasOwnProperty(data.id)) {
                console.error("Error: Formato incorrecto en la configuración del semáforo en el JSON.");
                return null;
            }
            return {
                id: data.id,
                orientation: data.id.startsWith('horizontal') ? 'horizontal' : 'vertical',
                timings: data.timings,
                currentColor: initialColors[data.id],
                startTime: Date.now()
            };
        }).filter(light => light !== null);

        return trafficLightsConfig.length === 4;
    };

    const updateTrafficLight = (light) => {
        const elapsed = Date.now() - light.startTime;
        const currentDuration = light.timings[light.currentColor];
        if (elapsed >= currentDuration) {
            const states = ['red', 'green', 'yellow'];
            const currentIndex = states.indexOf(light.currentColor);
            light.currentColor = states[(currentIndex + 1) % states.length];
            light.startTime = Date.now();
        }
    };

    const drawSemaphore = (x, y, color, orientation, id) => {
        const isHorizontal = orientation === 'horizontal';
        const supportWidth = isHorizontal ? 60 : 30;
        const supportHeight = isHorizontal ? 30 : 60;
        const supportX = isHorizontal ? x : x + 10;
        const supportY = isHorizontal ? y - 15 : y - 61;
        ctx.fillStyle = '#333';
        ctx.fillRect(supportX, supportY, supportWidth, supportHeight);

        ['red', 'yellow', 'green'].forEach((light, index) => {
            ctx.beginPath();
            const arcX = isHorizontal ? x + 10 + (index * 19) : x + 25;
            const arcY = isHorizontal ? y : y - 50 + (index * 19);
            ctx.arc(arcX, arcY, semaphoreRadius, 0, Math.PI * 2);
            ctx.fillStyle = (color === light) ? colors.semaphore[light] : '#222';
            ctx.fill();
        });

        ctx.fillStyle = '#fff';
        ctx.font = 'bold 14px Arial';
        ctx.textAlign = 'center';
        const textX = isHorizontal ? x + 30 : x + 25;
        const textY = isHorizontal ? y + 25 : y - 71;
        ctx.fillText(id, textX, textY);
        ctx.textAlign = 'start';
    };

    const drawVehicle = (vehicle) => {
        const { image, color } = vehicleTypes[vehicle.type];
        image.complete ? ctx.drawImage(image, vehicle.x, vehicle.y, vehicleSize, vehicleSize) :
                         ctx.fillRect(vehicle.x, vehicle.y, vehicleSize, vehicleSize * 0.9);
    };

    const drawIntersectionBackground = () => {
        ctx.fillStyle = colors.background;
        ctx.fillRect(0, 0, canvasWidth, canvasHeight);

        ctx.fillStyle = colors.road;
        ctx.fillRect(0, verticalCenter - roadWidth / 2, canvasWidth, roadWidth);
        ctx.fillRect(horizontalCenter - roadWidth / 2, 0, roadWidth, canvasHeight);

        ctx.strokeStyle = colors.laneDivider;
        ctx.lineWidth = 2;
        ctx.setLineDash([10, 10]);
        for (const offset of [-laneWidth, 0, laneWidth]) {
            ctx.beginPath();
            ctx.moveTo(0, verticalCenter + offset);
            ctx.lineTo(canvasWidth, verticalCenter + offset);
            ctx.moveTo(horizontalCenter + offset, 0);
            ctx.lineTo(horizontalCenter + offset, canvasHeight);
            ctx.stroke();
        }
        ctx.setLineDash([]);

        ctx.fillStyle = '#000';
        ctx.font = '16px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Avenida Principal', horizontalCenter, 30);
        ctx.fillText('Avenida Principal', horizontalCenter, canvasHeight - 20);
        ctx.fillText('Calle Central', 30, verticalCenter);
        ctx.fillText('Calle Central', canvasWidth - 30, verticalCenter);
        ctx.textAlign = 'start';
    };

    const drawSemaphores = () => {
        trafficLightsConfig.forEach(light => {
            let x, y;
            if (light.id === 'horizontal1') { x = horizontalCenter - laneWidth / 2 - 30; y = verticalCenter - roadWidth / 2 - 10; }
            else if (light.id === 'horizontal2') { x = horizontalCenter + laneWidth / 2 - 30; y = verticalCenter + roadWidth / 2 + 10; }
            else if (light.id === 'vertical1') { x = horizontalCenter + roadWidth / 2 + 40; y = verticalCenter - laneWidth / 2 + 30; }
            else if (light.id === 'vertical2') { x = horizontalCenter - roadWidth / 2 - 40; y = verticalCenter + laneWidth / 2 + 30; }
            if (x !== undefined && y !== undefined) {
                drawSemaphore(x, y, light.currentColor, light.orientation, light.id.replace(/horizontal|vertical/, ''));
            }
        });
    };

    const checkCollision = (ax, ay, bx, by) => (ax < bx + vehicleSize && ax + vehicleSize > bx && ay < by + vehicleSize && ay + vehicleSize > by);

    const getSemaphoreForDirection = (direction) => {
        if (direction === 'down') return trafficLightsConfig.find(light => light.id === 'horizontal1');
        if (direction === 'up') return trafficLightsConfig.find(light => light.id === 'horizontal2');
        if (direction === 'right') return trafficLightsConfig.find(light => light.id === 'vertical2');
        if (direction === 'left') return trafficLightsConfig.find(light => light.id === 'vertical1');
        return null;
    };

    const getStopLine = (direction) => {
        if (direction === 'down') return verticalCenter - roadWidth / 2 - 20;
        if (direction === 'up') return verticalCenter + roadWidth / 2 + 20;
        if (direction === 'right') return horizontalCenter - roadWidth / 2 - 20;
        if (direction === 'left') return horizontalCenter + roadWidth / 2 + 20;
        return -Infinity;
    };

    const adjustSpeedForTrafficLight = (vehicle) => {
        const relevantLight = getSemaphoreForDirection(vehicle.direction);
        if (!relevantLight || relevantLight.currentColor === 'green' || vehicle.hasPassedSemaphore) {
            return vehicle.speed;
        }

        if (relevantLight.currentColor === 'red' || relevantLight.currentColor === 'yellow') {
            const stopLine = getStopLine(vehicle.direction);
            let distance;

            if (vehicle.direction === 'down') {
                distance = stopLine - (vehicle.y + vehicleSize);
                if (distance <= 0) return vehicle.speed;
                if (distance < decelerationZone) return vehicle.speed * (distance / decelerationZone);
                return vehicle.speed;
            } else if (vehicle.direction === 'up') {
                distance = (vehicle.y) - stopLine;
                if (distance <= 0) return vehicle.speed;
                if (distance < decelerationZone) return vehicle.speed * (distance / decelerationZone);
                return vehicle.speed;
            } else if (vehicle.direction === 'right') {
                distance = stopLine - (vehicle.x + vehicleSize);
                if (distance <= 0) return vehicle.speed;
                if (distance < decelerationZone) return vehicle.speed * (distance / decelerationZone);
                return vehicle.speed;
            } else if (vehicle.direction === 'left') {
                distance = (vehicle.x) - stopLine;
                if (distance <= 0) return vehicle.speed;
                if (distance < decelerationZone) return vehicle.speed * (distance / decelerationZone);
                return vehicle.speed;
            }
        }

        return vehicle.speed;
    };

    const isSameFlow = (v1, v2) => (v1.direction === 'down' && v2.direction === 'down' && Math.abs(v1.x - v2.x) < laneWidth) ||
                                 (v1.direction === 'up' && v2.direction === 'up' && Math.abs(v1.x - v2.x) < laneWidth) ||
                                 (v1.direction === 'right' && v2.direction === 'right' && Math.abs(v1.y - v2.y) < laneWidth) ||
                                 (v1.direction === 'left' && v2.direction === 'left' && Math.abs(v1.y - v2.y) < laneWidth);

    const adjustSpeedForFollowing = (vehicle, baseSpeed) => {
        let gap = Infinity;
        vehicles.forEach(other => {
            if (other === vehicle || !isSameFlow(vehicle, other)) return;

            const getGap = () => {
                if (vehicle.direction === 'down') return other.y - (vehicle.y + vehicleSize);
                if (vehicle.direction === 'up') return vehicle.y - (other.y + vehicleSize);
                if (vehicle.direction === 'right') return other.x - (vehicle.x + vehicleSize);
                if (vehicle.direction === 'left') return vehicle.x - (other.x + vehicleSize);
                return Infinity;
            };

            const currentGap = getGap();
            if (currentGap > 0) {
                gap = Math.min(gap, currentGap);
            }
        });

        if (gap < safeDistance) {
            baseSpeed = Math.min(baseSpeed, vehicle.speed * (gap / safeDistance));
        }
        return baseSpeed;
    };

    const getNextPosition = (vehicle, moveSpeed) => {
        let { x, y } = vehicle;
        const resetPosition = (offset = 0) => -vehicleSize - offset;
        const resetOpposite = (offset = 0) => canvasWidth + offset;
        const resetOffset = 50;

        switch (vehicle.direction) {
            case 'down': y += moveSpeed; if (y > canvasHeight + resetOffset) y = resetPosition(resetOffset); vehicle.hasPassedSemaphore = false; break;
            case 'up': y -= moveSpeed; if (y < resetPosition(resetOffset)) y = resetOpposite(resetOffset); vehicle.hasPassedSemaphore = false; break;
            case 'right': x += moveSpeed; if (x > canvasWidth + resetOffset) x = resetPosition(resetOffset); vehicle.hasPassedSemaphore = false; break;
            case 'left': x -= moveSpeed; if (x < resetPosition(resetOffset)) x = resetOpposite(resetOffset); vehicle.hasPassedSemaphore = false; break;
        }

        const stopLine = getStopLine(vehicle.direction);
        if (!vehicle.hasPassedSemaphore) {
            if ((vehicle.direction === 'down' && vehicle.y > stopLine) ||
                (vehicle.direction === 'up' && vehicle.y < stopLine) ||
                (vehicle.direction === 'right' && vehicle.x > stopLine) ||
                (vehicle.direction === 'left' && vehicle.x < stopLine)) {
                vehicle.hasPassedSemaphore = true;
            }
        }

        return { nextX: x, nextY: y };
    };

    const animate = () => {
        ctx.clearRect(0, 0, canvasWidth, canvasHeight);
        drawIntersectionBackground();

        vehicles.forEach(vehicle => {
            let adjustedSpeed = adjustSpeedForTrafficLight(vehicle);
            adjustedSpeed = adjustSpeedForFollowing(vehicle, adjustedSpeed);
            const { nextX, nextY } = getNextPosition(vehicle, adjustedSpeed);

            const collisionDetected = vehicles.some(other => other !== vehicle && checkCollision(nextX, nextY, other.x, other.y));

            if (!collisionDetected) {
                vehicle.x = nextX;
                vehicle.y = nextY;
            }
            drawVehicle(vehicle);
        });

        trafficLightsConfig.forEach(updateTrafficLight);
        drawSemaphores();

        if (running) {
            animationFrameId = requestAnimationFrame(animate);
        }
    };
    //guardamos datos de siulacion
    const saveSimulationData = async () => {
        const simulationData = {
            vehicles: initialVehiclesData,
            semaphores: initialSemaphoresData
        };
    
        try {
            const response = await fetch('../Controllers/Monitor/saveSimulation.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(simulationData)
            });
            const result = await response.json();
            if (result.success) {
                idSimulacion= result.simulation_id;
                console.log("Simulación guardada con ID:", result.simulation_id);
            } else {
                console.error("Error al guardar simulación:", result.error);
            }
        } catch (error) {
            console.error("Error en la comunicación con el servidor:", error);
        }
    };

    const startSimulation = () => {
        if(!file){
            //aqui se podria colocar el random
        }
        resetVehicles(initialVehiclesData);
        initializeTrafficLights(initialSemaphoresData);
        
        if (!running && trafficLightsConfig.length === 4 && vehicles.length > 0) {
            saveSimulationData();
            running = true;
            detenerSimulacion.disabled = false;
            animate();
            
        } else if (trafficLightsConfig.length !== 4) {
            console.error("Error: La configuración de los semáforos no se cargó correctamente (deben ser 4).");
        } else if (vehicles.length === 0) {
            console.error("Error: No se cargaron vehículos para la simulación.");
        }
    };

    const stopSimulation = () => {
        running = false;
        detenerSimulacion.disabled = true;
        cancelAnimationFrame(animationFrameId);
    };

    const handleFileChange = (event) => {
         file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                try {
                    const data = JSON.parse(e.target.result);
                    if (data.vehicles) {
                        initialVehiclesData = data.vehicles;
                        resetVehicles(data.vehicles);
                    } else {
                        console.error("Error: El archivo JSON no contiene la propiedad 'vehicles'.");
                        return;
                    }
                    if (data.semaphores) {
                        initialSemaphoresData = data.semaphores;
                        if (initializeTrafficLights(data.semaphores)) {
                            // Inicializar la visual
                            drawIntersectionBackground();
                            drawSemaphores();
                        }
                    } else {
                        console.error("Error: El archivo JSON no contiene la propiedad 'semaphores'.");
                        return;
                    }
                } catch (error) {
                    console.error("Error al leer o parsear el archivo JSON:", error);
                }
            };
            reader.readAsText(file);
        }
    };

    // Inicialización: dibujar la carretera vacía y cargar imágenes
    drawIntersectionBackground();
    loadImages().then(() => {
        // Event listener para cargar el archivo JSON
        cargarArchivoInput.addEventListener('change', handleFileChange);
        // Event listeners para los botones de control
        iniciarSimulacion.addEventListener('click', startSimulation);
        detenerSimulacion.addEventListener('click', stopSimulation);
    });
});