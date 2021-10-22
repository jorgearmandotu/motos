<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';
//$pdf = new FPDF('P', 'mm', 'letter');
$pdf = new FPDF('P', 'mm', array(80, 130));
$pdf->AddPage();
$pdf->SetMargins(3, 0, 3);
$pdf->SetAutoPageBreak(true,0);
$pdf->SetTitle("Ventas");
//$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFont('Arial', 'B', 10);
$id = $_GET['v'];
$idcliente = $_GET['cl'];
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
$clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
$datosC = mysqli_fetch_assoc($clientes);
$ventas = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_venta = $id");
$pdf->Cell(60, 5, utf8_decode($datos['nombre']), 0, 1, 'C');//195 nombre negocio
//$pdf->Image("../../assets/img/logo2.png", 180, 10, 30, 30, 'PNG');
//$pdf->Cell($pdf->Image("../../assets/img/logo2.png", 10, 15, 30, 30, 'PNG'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60, 5, $datos['telefono'], 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, utf8_decode("NIT: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60, 5, $datos['nit'], 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, utf8_decode("Dirección: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60, 5, utf8_decode($datos['direccion']), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, "Correo: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60, 5, utf8_decode($datos['email']), 0, 1, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(75, 5, "Datos del cliente", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(15, 5, utf8_decode('Nombre:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60, 5, utf8_decode($datosC['nombre']), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, utf8_decode('Teléfono:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60, 5, utf8_decode($datosC['telefono']), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, utf8_decode('Dirección:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60, 5, utf8_decode($datosC['direccion']), 0, 1, 'L');

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(75, 5, "Detalle de Producto", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(5, 5, utf8_decode('N°'), 0, 0, 'C');
$pdf->Cell(33, 5, utf8_decode('Descripción'), 0, 0, 'L');
$pdf->Cell(7, 5, 'Cant', 0, 0, 'C');
$pdf->Cell(15, 5, 'Vl.Unit', 0, 0, 'C');
$pdf->Cell(15, 5, 'Sub Total.', 0, 1, 'C');
$pdf->SetFont('Arial', '', 7);
$contador = 1;
$x = $pdf->GetX();
$y = $pdf->GetY();
$total = 0;
while ($row = mysqli_fetch_assoc($ventas)) {
    $pdf->Cell(5, 5, $contador, 0, 0, 'R');
    //$pdf->MultiCell(27, 5, $row['descripcion'], 1, 'L', false);
    $pdf->Cell(33, 5, substr($row['descripcion'],0 , 25),0, 0, 'L');
    $pdf->Cell(7, 5, $row['cantidad'], 0, 0, 'R');
    $pdf->Cell(15, 5, number_format($row['precio'], 0, '.', ','), 0, 0, 'R');
    $pdf->Cell(15, 5, number_format($row['cantidad'] * $row['precio'], 0, '.', ','), 0, 1, 'R');


    /*$pdf->MultiCell(5, 5, $contador, 1, 'R', false);
    $x+=5;
    $pdf->SetXY($x,$y);
    $pdf->MultiCell(27, 5, $row['descripcion'], 1, 'L', false);
    $x+=27;
    $pdf->SetXY($x,$y);
    $pdf->MultiCell(8, 5, $row['cantidad'],1 , 'R', false);
    $x+=8;
    $pdf->SetXY($x,$y);
    $pdf->MultiCell(15, 5, number_format($row['precio'], 0, ',', '.'),1 , 'R', false);
    $x+=15;
    $pdf->SetXY($x,$y);
    $pdf->MultiCell(20, 5, number_format($row['cantidad'] * $row['precio'], 0, ',', '.'),1 , 'R', false);*/
    $contador++;
    //$pdf->Ln();
    $x=$pdf->GetX();
    $y+=5;
    $total+=$row['cantidad']*$row['precio'];
}
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(53, 5, 'TOTAL:', 0, 0, 'R');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(22, 5, '$ '.number_format($total, 0, ',', '.'), 0, 0, 'R');
$pdf->Output("ventas.pdf", "I");

?>
