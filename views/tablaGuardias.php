<?php
$fechaActual = date('d-m-Y');
?>
<div class="taulaGestioGuardiesAdmin">
    <div class="data-actual">
        <h2>Data: <?php echo $fechaActual; ?> - Hora: <span id="hora-actual"></span></h2>
    </div>
    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th>Professors de Guardia</th>
                <th>Asignacions de les Guàrdies</th>
                <th>Professors Absents</th>
                <!-- <th>Comentaris</th> -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($horas as $hora): ?>
                <tr>
                    <td><?php echo htmlspecialchars($hora); ?></td>
                    <td>
                        <ul>
                            <?php
                            $horario = new Horario(); // Crear una instancia de la clase Horario
                            $profesoresGuardia = $horario->obtenerProfesoresGuardia($hora);

                            // Verificar si se obtuvieron resultados
                            if ($profesoresGuardia) {
                                // Iterar sobre los profesores de guardia y mostrarlos en una lista
                                foreach ($profesoresGuardia as $profesor) {
                                    echo "<li>" . htmlspecialchars($profesor['professor']) . "</li>";
                                }
                            } else {
                                echo "<li>No hi ha professors de guàrdia per a aquesta hora.</li>";
                            }
                            ?>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <?php
                            $asignaciones = [];
                            $ausencias = $horario->obtenerProfesoresAusentes($hora);

                            // Crear asignaciones
                            if ($profesoresGuardia && $ausencias) {
                                $i = 0;
                                foreach ($ausencias as $ausencia) {
                                    if (isset($profesoresGuardia[$i])) {
                                        $asignaciones[] = htmlspecialchars($profesoresGuardia[$i]['professor']) . " ". ' -> ' . htmlspecialchars($ausencia['nom']) . " " . htmlspecialchars($ausencia['cognoms']);
                                        $i++;
                                    }
                                }
                            }

                            // Mostrar asignaciones
                            if ($asignaciones) {
                                foreach ($asignaciones as $asignacion) {
                                    echo "<li>$asignacion</li>";
                                }
                            } else {
                                echo "<li>No hi ha assignacions per a aquesta hora.</li>";
                            }
                            ?>
                        </ul>
                    </td>
                    <td>
                        <ul class="faltados-list" data-hora="<?php echo htmlspecialchars($hora); ?>">
                            <?php
                            $profesoresAusentes = $horario->obtenerProfesoresAusentes($hora);

                            // Verificar si se obtuvieron resultados
                            if ($profesoresAusentes) {
                                // Iterar sobre los profesores ausentes y mostrarlos en una lista
                                foreach ($profesoresAusentes as $profesor) {
                                    echo "<li>" . htmlspecialchars($profesor['nom'] . ' ' . $profesor['cognoms']) . "</li>";
                                }
                            } else {
                                echo "<li>No hi ha professors absents per a aquesta hora.</li>";
                            }
                            ?>
                        </ul>
                    </td>
                    <!-- <td>
                        <button class="add-comment-btn" data-hora="<?php echo htmlspecialchars($hora); ?>">Afegir
                            Comentari</button>
                    </td> -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateHoraActual() {
        const now = new Date();
        const formattedTime = now.toLocaleTimeString();
        document.getElementById('hora-actual').textContent = formattedTime;
    }

    setInterval(updateHoraActual, 1000);
    updateHoraActual(); // Inicializa la hora actual al cargar la página
});
</script>