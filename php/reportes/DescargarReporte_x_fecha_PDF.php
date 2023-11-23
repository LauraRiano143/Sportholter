<?php
date_default_timezone_set('America/Bogota');
require_once('tcpdf/tcpdf.php'); // Asegúrate de que la ruta a tcpdf.php sea correcta.
require_once '../conexion.php';

// Iniciar la sesión para acceder a las variables de sesión.
session_start();

// Supongamos que $_SESSION['documento'] contiene el número de documento del paciente.
$documento_paciente = isset($_SESSION['documento']) ? $_SESSION['documento'] : '';

// Realizar la consulta para obtener el num_documento del especialista
$pdoConsulta = $conexion->prepare('SELECT documento_especialista FROM consulta WHERE documento_paciente = ?');
$pdoConsulta->bindValue(1, $documento_paciente);
$pdoConsulta->execute();

// Obtener el resultado de la consulta
$resultadoConsulta = $pdoConsulta->fetch(PDO::FETCH_ASSOC);

// Verificar si se encontró el especialista en la consulta
if ($resultadoConsulta !== false) {
    // Obtener el num_documento del especialista
    $num_documento_especialista = $resultadoConsulta['documento_especialista'];

    // Realizar la consulta para obtener los detalles del especialista
    $pdoEspecialista = $conexion->prepare('SELECT primer_nombre, segundo_nombre, primer_apellido, segundo_apellido FROM usuarios WHERE num_documento = ?');
    $pdoEspecialista->bindValue(1, $num_documento_especialista);
    $pdoEspecialista->execute();

    // Obtener el resultado de la consulta del especialista
    $resultadoEspecialista = $pdoEspecialista->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró el especialista y obtener el nombre y apellido
    if ($resultadoEspecialista !== false) {
        $primer_nombre_especialista = ucwords($resultadoEspecialista['primer_nombre']);
        $segundo_nombre_especialista = ucwords($resultadoEspecialista['segundo_nombre']);
        $primer_apellido_especialista = ucwords($resultadoEspecialista['primer_apellido']);
        $segundo_apellido_especialista = ucwords($resultadoEspecialista['segundo_apellido']);

        // Puedes concatenar los nombres y apellidos según tu formato deseado
        $nombreEspecialista = $primer_nombre_especialista . ' ' . $segundo_nombre_especialista . ' ' . $primer_apellido_especialista . ' ' . $segundo_apellido_especialista;
    } else {
        $nombreEspecialista = 'Nombre no disponible';
    }
} else {
    // Si no se encuentra información en la consulta, establecer un valor predeterminado
    $nombreEspecialista = 'Nombre no disponible';
}

// Realizar la consulta para obtener los datos del paciente
$pdoDatosPaciente = $conexion->prepare('SELECT u.primer_nombre, u.primer_apellido, u.correo, u.num_documento, c.actividad_fisica
                                       FROM usuarios u
                                       INNER JOIN consulta c ON u.num_documento = c.documento_paciente
                                       WHERE u.num_documento = ?');
$pdoDatosPaciente->bindValue(1, $documento_paciente);
$pdoDatosPaciente->execute();

// Obtener el resultado de la consulta del paciente
$resultadoDatosPaciente = $pdoDatosPaciente->fetch(PDO::FETCH_ASSOC);

// Verificar si se encontró el paciente y obtener los datos
if ($resultadoDatosPaciente !== false) {
    $primer_nombre_paciente = ucwords($resultadoDatosPaciente['primer_nombre']);
    $primer_apellido_paciente = ucwords($resultadoDatosPaciente['primer_apellido']);
    $correo_paciente = $resultadoDatosPaciente['correo'];
    $num_documento_paciente = $resultadoDatosPaciente['num_documento'];
    $deporte_paciente = ucwords($resultadoDatosPaciente['actividad_fisica']);
} else {
    // Si no se encuentra información en la consulta, establecer valores predeterminados
    $primer_nombre_paciente = 'Nombre no disponible';
    $primer_apellido_paciente = 'Apellido no disponible';
    $correo_paciente = 'Correo no disponible';
    $num_documento_paciente = 'Documento no disponible';
    $deporte_paciente = 'Deporte no disponible';
}

// Resto del código...

// Extend the TCPDF class to create custom Header
class MYPDF extends TCPDF {
    public function Header() {
        $bMargin = $this->getBreakMargin();
        $auto_page_break = $this->AutoPageBreak;
        $this->SetAutoPageBreak(false, 0);
        $img_file = dirname(__FILE__) . '../../media/logo-dash.png'; // Asegúrate de que la ruta al logo sea correcta.
        $this->Image($img_file, 15, 10, 20, '', '', '', '', false, 300, '', false, false, 0);
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        $this->setPageMark();
    }
}

// Crear nuevo documento PDF
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Establecer información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SPORTHOLTER');
$pdf->SetTitle('Informe de Pacientes');

// Establecer márgenes
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Quitar línea del header por defecto y configurar auto salto de página
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Añadir una página
$pdf->AddPage();

// Configurar la fuente
$pdf->SetFont('helvetica', 'B', 12);

// Encabezado del especialista
$pdf->SetTextColor(255, 0, 0); // Rojo
$pdf->Cell(0, 0, 'SPORTHOLTER', 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0); // Negro
$pdf->Ln(5); // Agregamos un espacio después del encabezado

$pdf->SetTextColor(0, 0, 0); // Rojo
$pdf->Cell(0, 0, 'Especialista: ' . $nombreEspecialista, 0, 1, 'L');

// Información del documento
$pdf->SetTextColor(0, 0, 0); // Negro
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 0, 'Código: 0014ABC', 0, 1, 'R');
$pdf->Cell(0, 0, 'Fecha: ' . date('d-m-Y'), 0, 1, 'R');
$pdf->Cell(0, 0, 'Hora: ' . date('h:i A'), 0, 1, 'R');
$pdf->Cell(0, 0, 'Ciudad: Bogota D.C', 0, 1, 'R');

// Título del informe
$pdf->SetTextColor(0, 0, 255); // Azul
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Ln(10);
$pdf->Cell(0, 0, 'INFORME MEDICO-DEPORTIVO', 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0); // Negro
$pdf->Ln(10);

// Cabecera de la tabla de pacientes
$pdf->SetFillColor(192, 192, 192); // Gris claro
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(40, 10, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(60, 10, 'Email', 1, 0, 'C', 1);
$pdf->Cell(35, 10, 'Deporte', 1, 0, 'C', 1);
$pdf->Cell(35, 10, 'Documento', 1, 1, 'C', 1);

// Restaurar fuente y color para el contenido de la tabla
$pdf->SetFont('helvetica', '', 10);

// Mostrar los datos del paciente en la tabla
$pdf->Cell(40, 10, $primer_nombre_paciente . ' ' . $primer_apellido_paciente, 1, 0, 'C');
$pdf->Cell(60, 10, $correo_paciente, 1, 0, 'C');
$pdf->Cell(35, 10, $deporte_paciente, 1, 0, 'C');
$pdf->Cell(35, 10, $num_documento_paciente, 1, 1, 'C');

// Título "Captura ECG"
$pdf->Ln(10); // Espaciado
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 0, 'Captura ECG', 0, 1, 'L');

// Imagen
$img_ecg = dirname(__FILE__) . '../../../media/señalECG.JPG'; 
//$img_ecg_example = 'https://www.example.com/path/to/your/image.jpg';
$pdf->Image($img_ecg, 15, $pdf->GetY() + 10, 180, '', 'JPEG', '', 'T', false, 300, '', false, false, 0);

// Salida del PDF al navegador
// Crear un nombre de archivo basado en el nombre del paciente y la fecha
$nombreArchivo = date('Ymd') .'_'. $primer_nombre_paciente . '_' . $primer_apellido_paciente . '_'.'.pdf';

// Salida del PDF al navegador con el nombre personalizado
$pdf->Output($nombreArchivo, 'I');

?>
