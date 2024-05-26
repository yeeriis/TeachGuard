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
            </tr>
        </thead>
        <tbody>
            <?php foreach ($horas as $hora): ?>
                <tr>
                    <td><?php echo htmlspecialchars($hora); ?></td>
                    <td>
                        <ul>
                            <?php
                            $horario = new Horario();
                            $profesoresGuardia = $horario->obtenerProfesoresGuardia($hora);

                            if ($profesoresGuardia) {
                                foreach ($profesoresGuardia as $profesor) {
                                    echo "<li>" . htmlspecialchars($profesor['nom']) . htmlspecialchars($profesor['cognoms']) . "</li>";
                                    echo "<br>";
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

                            if ($profesoresGuardia && $ausencias) {
                                $numProfesoresGuardia = count($profesoresGuardia);
                                $numAusencias = count($ausencias);
                                $i = 0;
                        
                                foreach ($ausencias as $ausencia) {
                                    if ($i < $numProfesoresGuardia) {
                                        $profesorGuardia = $profesoresGuardia[$i];
                                        $asignaciones[] = htmlspecialchars($profesorGuardia['nom']) . " " . htmlspecialchars($profesorGuardia['cognoms']) . " -> " . htmlspecialchars($ausencia['nom']) . " " . htmlspecialchars($ausencia['cognoms']);
                                        $i++;
                                    } else {
                                        echo "El/La professor/a " . htmlspecialchars($ausencia['nom']) . " " . htmlspecialchars($ausencia['cognoms']) . " no té un profesor de guàrdia assignat.";
                                        echo "<br>";
                                        break;
                                    }
                                }
                            }

                            if ($asignaciones) {
                                foreach ($asignaciones as $asignacion) {
                                    echo "<li>$asignacion</li>";
                                    echo "<br>";
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

                            if ($profesoresAusentes) {
                                foreach ($profesoresAusentes as $profesor) {
                                    echo "<li>" . htmlspecialchars($profesor['nom'] . ' ' . $profesor['cognoms']) . "</li>";
                                    echo "<br>";
                                }
                            } else {
                                echo "<li>No hi ha professors absents per a aquesta hora.</li>";
                            }
                            ?>
                        </ul>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function updateHoraActual() {
            const now = new Date();
            const formattedTime = now.toLocaleTimeString();
            document.getElementById('hora-actual').textContent = formattedTime;
        }

        setInterval(updateHoraActual, 1000);
        updateHoraActual();
    });
</script>