<?

    use setasign\Fpdi\Fpdi;
    use setasign\Fpdi\PdfReader;
    require_once('./fpdf182/fpdf.php');
    require_once('./fpdi/autoload.php');
    $pdf = $pdf = new FPDI();
    initialize($pdf);

    $date = date('2019-01-01');
    $tmp = DateTime::createFromFormat("Y-m-d", $date);

    $yPosition = 77;
    $count = 0;
    while(1){
        $tmp = DateTime::createFromFormat("Y-m-d", $date);
        if($tmp->format('Y') != 2019) break;
        if($count == 21){
            initialize($pdf);
            $count = 0;
            $yPosition = 77;
        }
        $pdf->SetXY(115, $yPosition);
        $pdf->Write(0, $date);
        $yPosition+=4.85;
        $count++;
        $plusOne = strtotime( '+1 weekday', strtotime($date));
        $date = date( 'Y-m-d', $plusOne );
    }
    $pdf->Output('testtttt.pdf', 'D');

    function initialize($pdf){
        $pdf->AddPage();
        $pdf->setSourceFile('testBlank.pdf');
        $tplIdx = $pdf->importPage(1);
        $pdf->useTemplate($tplIdx);

        $pdf->SetFont('Arial', '', '10');
        $pdf->SetTextColor(0,0,0);
    }
?>