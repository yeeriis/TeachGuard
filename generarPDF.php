<?php
require 'vendor/autoload.php';
require 'models/horario.php';  // Asegúrate de incluir tu clase Horario o la fuente de los datos

// Crear nueva instancia de TCPDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Establecer la información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nombre del Autor');
$pdf->SetTitle('Gestión de Guardias');
$pdf->SetSubject('Tabla de Guardias');
$pdf->SetKeywords('TCPDF, PDF, guardias, ejemplo');

// Establecer los márgenes
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Establecer auto ajustes de salto de página
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Añadir una página
$pdf->AddPage();

// Obtener los datos de la tabla
$horario = new Horario();
$horas = $horario->obtenirHores();
$fechaActual = date('d-m-Y');
$diaActual = date('N');

// Construir el contenido HTML de la tabla
$html = '<h2>Data: ' . $fechaActual . '</h2>';
$html .= '<table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Professors de Guardia</th>
                    <th>Asignacions de les Guàrdies</th>
                    <th>Professors Absents</th>
                </tr>
            </thead>
            <tbody>';

foreach ($horas as $hora) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($hora) . '</td>';
    $html .= '<td><ul>';

    $profesoresGuardia = $horario->obtenerProfesoresGuardia($hora);
    if ($profesoresGuardia) {
        foreach ($profesoresGuardia as $profesor) {
            $html .= '<li>' . htmlspecialchars($profesor['nom']) . ' ' . htmlspecialchars($profesor['cognoms']) . '</li>';
        }
    } else {
        $html .= '<li>No hay profesores de guardia para esta hora.</li>';
    }

    $html .= '</ul></td>';
    $html .= '<td><ul>';

    $asignaciones = [];
    $ausencias = $horario->obtenerProfesoresAusentes($hora);

    if ($profesoresGuardia && $ausencias) {
        $numProfesoresGuardia = count($profesoresGuardia);
        $numAusencias = count($ausencias);
        $i = 0;

        foreach ($ausencias as $ausencia) {
            if ($i < $numProfesoresGuardia) {
                $profesorGuardia = $profesoresGuardia[$i];
                $asignaciones[] = htmlspecialchars($profesorGuardia['nom']) . ' ' . htmlspecialchars($profesorGuardia['cognoms']) . ' -> ' . htmlspecialchars($ausencia['nom']) . ' ' . htmlspecialchars($ausencia['cognoms']);
                $i++;
            } else {
                $html .= '<li>El/La professor/a ' . htmlspecialchars($ausencia['nom']) . ' ' . htmlspecialchars($ausencia['cognoms']) . ' no té un professor de guàrdia assignat.</li>';
                break;
            }
        }
    }

    if ($asignaciones) {
        foreach ($asignaciones as $asignacion) {
            $html .= '<li>' . $asignacion . '</li>';
        }
    } else {
        $html .= '<li>No hay asignaciones para esta hora.</li>';
    }

    $html .= '</ul></td>';
    $html .= '<td><ul>';

    $profesoresAusentes = $horario->obtenerProfesoresAusentes($hora);
    if ($profesoresAusentes) {
        foreach ($profesoresAusentes as $profesor) {
            $aula = $horario->obtenerAulaProfesorAusente($profesor['id_profesor'], $hora, $diaActual);
            $html .= '<li>' . htmlspecialchars($profesor['nom']) . ' ' . htmlspecialchars($profesor['cognoms']) . ' - Aula: ' . htmlspecialchars($aula) . '</li>';
        }
    } else {
        $html .= '<li>No hay profesores ausentes para esta hora.</li>';
    }

    $html .= '</ul></td>';
    $html .= '</tr>';
}

$html .= '</tbody></table>';

// Establecer el contenido del PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Cerrar y generar el PDF
$pdf->Output('guardias.pdf', 'I');
?>
