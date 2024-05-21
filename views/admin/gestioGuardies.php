<?php
// Obtener la fecha actual
$fechaActual = date('d-m-Y');
?>
<div class="taulaGestioGuardiesAdmin">
    <!-- Mostrar la fecha actual -->
    <div class="data-actual">
        <h2>Data: <?php echo $fechaActual; ?></h2>
    </div>
    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th>Professors de Guardia</th>
                <th>Comentaris</th>
                <th>Professors Absents</th>
                <th>Fletxes d'acció</th>
                <th>Tots els professors</th>
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
                                echo "<li>No hay profesores de guardia para esta hora.</li>";
                            }
                            ?>
                        </ul>
                    </td>
                    <td>
                        <button class="add-comment-btn" data-hora="<?php echo htmlspecialchars($hora); ?>">Afegir
                            Comentari</button>
                    </td>
                    <td>
                        <ul class="faltados-list" data-hora="<?php echo htmlspecialchars($hora); ?>"></ul>
                    </td>
                    <td>
                        <button class="move-left-btn" data-hora="<?php echo htmlspecialchars($hora); ?>"><img
                                src="views/img/arrow-left.svg" height="50"></button>
                        <button class="move-right-btn" data-hora="<?php echo htmlspecialchars($hora); ?>"><img
                                src="views/img/arrow-right.svg" height="50"></button>
                    </td>
                    <td>
                        <select class="profesores-clase-select" data-hora="<?php echo htmlspecialchars($hora); ?>">
                            <?php foreach ($todosProfesores as $profesor): ?>
                                <option value="<?php echo htmlspecialchars($profesor['codi_professor']); ?>">
                                    <?php echo htmlspecialchars($profesor['nom'] . ' ' . $profesor['cognoms']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button id="guardarButton">Guardar</button>
</div>