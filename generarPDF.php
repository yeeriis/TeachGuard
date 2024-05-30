<?php
require 'vendor/autoload.php';
require 'models/horario.php';

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nombre del Autor');
$pdf->SetTitle('Gestión de Guardias');
$pdf->SetSubject('Tabla de Guardias');
$pdf->SetKeywords('TCPDF, PDF, guardias, ejemplo');

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();

$horario = new Horario();
$horas = $horario->obtenirHores();
$fechaActual = date('d-m-Y');
$diaActual = date('N');

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

    $profesoresGuardia = $horario->obtenerProfesoresGuardia();
    if ($profesoresGuardia) {
        foreach ($profesoresGuardia as $profesor) {
            $html .= '<li>' . htmlspecialchars($profesor['nom']) . ' ' . htmlspecialchars($profesor['cognoms']) . '</li>';
        }
    } else {
        $html .= '<li>No hi ha professors de guardia per a aquesta hora.</li>';
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
        $html .= '<li>No hi ha assignacions per a aquesta hora.</li>';
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
        $html .= '<li>No hi ha professors absents per a aquesta hora.</li>';
    }

    $html .= '</ul></td>';
    $html .= '</tr>';
}

$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('guardias.pdf', 'I');
?>