<?php
require_once('tcpdf/tcpdf.php'); //Llamando a la Libreria TCPDF

date_default_timezone_set('America/Bogota');

ob_end_clean(); //limpiar la memoria

class MYPDF extends TCPDF{
      
    public function Header() {
        $bMargin = $this->getBreakMargin();
        $auto_page_break = $this->AutoPageBreak;
        $this->SetAutoPageBreak(false, 0);
        $img_file = dirname(__FILE__) . '../../media/logo-p.png';
        $this->Image($img_file, 85, 8, 20, 25, '', '', '', false, 30, '', false, false, 0);
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        $this->setPageMark();
    }
}

//Iniciando un nuevo pdf
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, 'mm', 'Letter', true, 'UTF-8', false);

//Establecer margenes del PDF
$pdf->SetMargins(20, 35, 25);
$pdf->SetHeaderMargin(20);
$pdf->setPrintFooter(false);
$pdf->setPrintHeader(true); //Eliminar la linea superior del PDF por defecto
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM); //Activa o desactiva el modo de salto de página automático

//Informacion del PDF
$pdf->SetCreator('SPORTHOLTER');
$pdf->SetAuthor('SPORTHOLTER');
$pdf->SetTitle('Informe de Pacientes');

/** Eje de Coordenadas
 *          Y
 *          -
 *          - 
 *          -
 *  X ------------- X
 *          -
 *          -
 *          -
 *          Y
 * 
 * $pdf->SetXY(X, Y);
 */

//Agregando la primera página
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 10); //Tipo de fuente y tamaño de letra
$pdf->SetXY(150, 20);
$pdf->Write(0, 'Código: 0014ABC');
$pdf->SetXY(150, 25);
$pdf->Write(0, 'Fecha: ' . date('d-m-Y'));
$pdf->SetXY(150, 30);
$pdf->Write(0, 'Hora: ' . date('h:i A'));
$pdf->SetXY(150, 35);
$pdf->Write(0, 'Ciudad: Bogota.D.C');

$deporte = 'Baloncesto';
$pdf->SetFont('helvetica', 'B', 10); //Tipo de fuente y tamaño de letra
$pdf->SetXY(15, 20); //Margen en X y en Y
$pdf->SetTextColor(204, 0, 0);
$pdf->Write(0, 'Especialista: Laura R');
$pdf->SetTextColor(0, 0, 0); //Color Negrita
$pdf->SetXY(15, 25);
$pdf->Write(0, 'SPORTHOLTER');

$pdf->Ln(35); //Salto de Linea
$pdf->Cell(40, 26, '', 0, 0, 'C');
$pdf->SetTextColor(34, 68, 136);
$pdf->SetFont('helvetica', 'B', 15);
$pdf->Cell(100, 6, 'INFORME MEDICO-DEPORTIVO', 0, 0, 'C');

$pdf->Ln(10); //Salto de Linea
$pdf->SetTextColor(0, 0, 0);

//Almando la cabecera de la Tabla
$pdf->SetFillColor(232, 232, 232);
$pdf->SetFont('helvetica', 'B', 12); //La B es para letras en Negritas
$pdf->Cell(40, 6, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(60, 6, 'Email', 1, 0, 'C', 1);
$pdf->Cell(35, 6, 'Deporte', 1, 0, 'C', 1);
$pdf->Cell(35, 6, 'Numero Documento', 1, 1, 'C', 1); 
/*El 1 despues de  Fecha Ingreso indica que hasta alli 
llega la linea */

$pdf->SetFont('helvetica', '', 10);

require_once('../conexion.php');

$host = 'localhost';
$port = '3306';
$dbname = 'sportholter';
$username = 'root';
$password = '';

try {
    $conexion = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch(PDOException $error) {
    echo "Error en la conexión: " . $error->getMessage();
    die();
}

try {

    $sqlPacientes = 'SELECT primer_nombre, correo, ciudad_expedicion, num_documento FROM usuarios WHERE num_documento = :documento AND id_tipo = 3';
    $stmt = $conexion->prepare($sqlPacientes);
    $stmt->bindParam(':documento', $documento);
    $stmt->execute();
    
    while ($dataRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (isset($dataRow['primer_nombre'])) {
            $pdf->Cell(40, 6, $dataRow['primer_nombre'], 1, 0, 'C');
        } else {
            $pdf->Cell(40, 6, '', 1, 0, 'C');
        }
    
        if (isset($dataRow['correo'])) {
            $pdf->Cell(60, 6, $dataRow['correo'], 1, 0, 'C');
        } else {
            $pdf->Cell(60, 6, '', 1, 0, 'C');
        }
    
        if (isset($dataRow['ciudad_expedicion'])) {
            $pdf->Cell(35, 6, $dataRow['ciudad_expedicion'], 1, 0, 'C');
        } else {
            $pdf->Cell(35, 6, '', 1, 0, 'C');
        }
    
        if (isset($dataRow['num_documento'])) {
            $pdf->Cell(35, 6, $dataRow['num_documento'], 1, 1, 'C');
        } else {
            $pdf->Cell(35, 6, '', 1, 1, 'C');
        }
    }
    
} catch (PDOException $e) {
    echo 'Error de conexión a la base de datos: ' . $e->getMessage();
}

$pdf->Output('Resumen_Pedido_' . date('d_m_y') . '.pdf', 'I');
// Output funcion que recibe 2 parameros, el nombre del archivo, ver archivo o descargar,
// La D es para Forzar una descarga
?>
