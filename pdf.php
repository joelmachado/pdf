<?php 

require_once 'vendor/autoload.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

#......................img...................................

$path = $_SERVER["DOCUMENT_ROOT"].'/img/logo.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
$logo = '<img src="'.$logo.'" width="150" height="150" />';

#.............................................................

$path = $_SERVER["DOCUMENT_ROOT"].'/img/bg.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$bg = 'data:image/' . $type . ';base64,' . base64_encode($data);
//$bg = '<img src="'.$logo.'" width="150" height="150" />';

#....................

$html = '<html>
    <head> 
        <style>
            /** 
            * Set the margins of the PDF to 0
            * so the background image will cover the entire page.
            **/
            @page {
                margin: 0cm 0cm;
                
            }

            /**
            * Define the real margins of the content of your PDF
            * Here you will fix the margins of the header and footer
            * Of your background image.
            **/
            body {
                margin-top:    3.5cm;
                margin-bottom: 1cm;
                margin-left:   1cm;
                margin-right:  1cm;

               

            }

            /** 
            * Define the width, height, margins and position of the watermark.
            **/
            #watermark {
                position: fixed;
                bottom:   0px;
                left:     0px;
                /** The width and height may change 
                    according to the dimensions of your letterhead
                **/
                width:    100%;
                height:   1123px;

                /** Your watermark should be behind every content**/
                z-index:  -1000;
                background-image: url('.$bg.'); background-repeat: no-repeat; background-position: center; 
                background-size: 100%  100%;
            }
        </style>
    </head>
    <body>
        <div id="watermark">
       
            
        </div>

        <main> 
        '.$logo.'
        </main>
    </body>
</html>';

#..............................................................
        
$options = new Options();
$options->set( 'isRemoteEnabled', TRUE );
$options->setIsHtml5ParserEnabled(true);

// instantiate and use the dompdf class
$dompdf = new Dompdf($options);
//$dompdf->set_paper( 'A4', 'portrait' );
//$homepage = file_get_contents('content.html');

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->set_paper( 'A4', 'portrait' );

// Render the HTML as PDF

$dompdf->render();
// Output the generated PDF to Browser


$filename = "cupom";
//$dompdf->stream($filename);
$output = $dompdf->output();
//echo $output;
$file = $output;


// Header content type 
header('Content-type: application/pdf'); 
header('Content-Disposition: inline; filename="' . $filename . '"'); 
header('Content-Transfer-Encoding: binary'); 
header('Accept-Ranges: bytes'); 

echo $file;


?>
