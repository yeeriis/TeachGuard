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
                    <td class="hores"><?php echo htmlspecialchars($hora); ?></td>
                    <td>
                        <ul>
                            <?php
                            $horario = new Horario();
                            $profesoresGuardia = $horario->obtenerProfesoresGuardia();

                            if ($profesoresGuardia) {
                                foreach ($profesoresGuardia as $profesor) {
                                    echo "<p>" . htmlspecialchars($profesor['nom']) . " " . htmlspecialchars($profesor['cognoms']) . "</p>";
                                }
                            } else {
                                echo "<p>No hi ha professors de guàrdia per a aquesta hora.</p>";
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
                                        echo "El/La professor/a " . htmlspecialchars($ausencia['nom']) . " " . htmlspecialchars($ausencia['cognoms']) . " no té un professor de guàrdia assignat.";
                                        echo "<br>";
                                        break;
                                    }
                                }
                            }

                            if ($asignaciones) {
                                foreach ($asignaciones as $asignacion) {
                                    echo "<p>$asignacion</p>";
                                    echo "<br>";
                                }
                            } else {
                                echo "<p>No hi ha assignacions per a aquesta hora.</p>";
                            }
                            ?>
                        </ul>
                    </td>
                    <td>
                        <ul class="faltados-list" data-hora="<?php echo htmlspecialchars($hora); ?>">
                            <?php
                            $diaActual = date('N');
                            $profesoresAusentes = $horario->obtenerProfesoresAusentes($hora);

                            if ($profesoresAusentes) {
                                foreach ($profesoresAusentes as $profesor) {
                                    echo "<p>" . htmlspecialchars($profesor['nom'] . ' ' . $profesor['cognoms']) . " - Aula: " . htmlspecialchars($profesor['aula']) . "</p>";
                                    echo "<br>";
                                }
                            } else {
                                echo "<p>No hi ha professors absents per a aquesta hora.</p>";
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