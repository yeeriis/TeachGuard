<?php
$fechaActual = date('d-m-Y');
?>
<div class="taulaGestioGuardiesAdmin">
    <div class="data-actual">
        <h2>Data: <?php echo $fechaActual; ?></h2>
    </div>
    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th class="hidden-mobile">Professors de Guardia</th>
                <!-- <th>Comentaris</th> -->
                <th>Professors Absents</th>
                <th>Fletxes d'acció</th>
                <th>Tots els professors</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($horas as $hora): ?>
                <tr>
                    <td class="hora"><?php echo htmlspecialchars($hora); ?></td>
                    <td class="hidden-mobile">
                        <ul>
                            <?php
                            $horario = new Horario(); 
                            $profesoresGuardia = $horario->obtenerProfesoresGuardia($hora);

                            if ($profesoresGuardia) {
                                foreach ($profesoresGuardia as $profesor) {
                                    echo "<li>" . htmlspecialchars($profesor['nom']) . " " .htmlspecialchars($profesor['cognoms']) . "</li>";
                                }
                            } else {
                                echo "<li>No hay profesores de guardia para esta hora.</li>";
                            }
                            ?>
                        </ul>
                    </td>
                    <!-- <td>
                        <button class="add-comment-btn" data-hora="<?php echo htmlspecialchars($hora); ?>">Afegir
                            Comentari</button>
                    </td> -->
                    <td>
                        <li class="faltados-list" data-hora="<?php echo htmlspecialchars($hora); ?>"></li>
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