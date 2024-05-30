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
                                    echo "<li>" . htmlspecialchars($profesor['nom']) . " " . htmlspecialchars($profesor['cognoms']) . "</li>";
                                }
                            } else {
                                echo "<li>No hi ha professors de guàrdia per a aquesta hora.</li>";
                            }
                            ?>
                        </ul>
                    </td>
                    <td>
                        <ul class="faltados-list" data-hora="<?php echo htmlspecialchars($hora); ?>">
                            <?php
                            $diaSemanaActual = date('N');
                            $profesoresAusentes = $horario->obtenerProfesoresAusentes($hora);

                            if ($profesoresAusentes) {
                                foreach ($profesoresAusentes as $profesor) {
                                    echo "<li>" . htmlspecialchars($profesor['nom'] . ' ' . $profesor['cognoms']) . "</li>";
                                }
                            } else {

                            }
                            ?>
                        </ul>
                    </td>
                    <td>
                        <button class="move-left-btn" data-hora="<?php echo htmlspecialchars($hora); ?>"><img
                                src="views/img/arrow-left.svg" height="50"></button>
                        <button class="move-right-btn" data-hora="<?php echo htmlspecialchars($hora); ?>"><img
                                src="views/img/arrow-right.svg" height="50"></button>
                    </td>
                    <td>
                        <select class="profesores-clase-select" data-hora="<?php echo htmlspecialchars($hora); ?>">
                            <?php
                            if (isset($profesoresPorHora[$hora])) {
                                $profesoresConClase = $profesoresPorHora[$hora];
                                if ($profesoresConClase) {
                                    foreach ($profesoresConClase as $profesor) {
                                        echo '<option value="' . htmlspecialchars($profesor['codi_professor']) . '">';
                                        echo htmlspecialchars($profesor['nom'] . ' ' . $profesor['cognoms']) . ' - Aula: ' . htmlspecialchars($profesor['aula']);
                                        echo '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hi ha professors disponibles</option>';
                                }
                            } else {
                                echo '<option value="">No hi ha professors disponibles</option>';
                            }
                            ?>
                        </select>
                    </td>




                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button id="guardarButton">Guardar</button>
    <button id="pdfButton">Generar PDF</button>
    <button id="eliminarTodasButton">Esborrar Absències</button>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('pdfButton').addEventListener('click', function () {
            window.location.href = 'generarPDF.php';
        });
        const pdfButton = document.getElementById('pdfButton');
        pdfButton.addEventListener('click', function () {
            const tableHTML = document.querySelector('.taulaGestioGuardiesAdmin').outerHTML;

            fetch('index.php?controller=Admin&action=generarPDF', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ html: tableHTML })
            })
                .then(response => response.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = 'gestioGuardies.pdf';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                })
                .catch(error => console.error('Error:', error));
        });

        document.getElementById('eliminarTodasButton').addEventListener('click', function () {
            if (confirm('Estàs segur de voler eliminar totes les ausències?')) {
                fetch('views/admin/eliminarAbsencies.php', {
                    method: 'POST'
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Totes les ausències han estat eliminades.');
                            location.reload();
                        } else {
                            alert('Error al eliminar les ausències: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al eliminar les ausències.');
                    });
            }
        });
    });
</script>