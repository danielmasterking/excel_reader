<?php
require_once 'conexion.php';
// get details of the uploaded file
$fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
$fileName = $_FILES['uploadedFile']['name'];
$fileSize = $_FILES['uploadedFile']['size'];
$fileType = $_FILES['uploadedFile']['type'];
$fileNameCmps = explode(".", $fileName);
$fileExtension = strtolower(end($fileNameCmps));

$newFileName = md5(time() . $fileName) . '.' . $fileExtension;

// directory in which the uploaded file will be moved
$uploadFileDir = './uploaded_files/';
$dest_path = $uploadFileDir . $newFileName;
 
if(move_uploaded_file($fileTmpPath, $dest_path))
{
  $message ='File is successfully uploaded.';
}
else
{
  $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
}
require_once 'PHPExcel/Classes/PHPExcel.php';
$archivo = $dest_path;
$inputFileType = PHPExcel_IOFactory::identify($archivo);
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($archivo);
$sheet = $objPHPExcel->getSheet(0); 
$highestRow = $sheet->getHighestRow(); 
$highestColumn = $sheet->getHighestColumn();
//print_r($sheet);
$str='';
$index_total='';
$index_celular='';

//echo $highestRow;
$abc_array=[
    'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
    'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
    'BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ',
    'CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ',
    'DA','DB','DC','DD','DE','DF','DG','DH','DI','DJ','DK','DL','DM','DN','DO','DP','DQ','DR','DS','DT','DU','DV','DW','DX','DY','DZ',
    'EA','EB','EC','ED','EE','EF','EG','EH','EI','EJ','EK','EL','EM','EN','EO','EP','EQ','ER','ES','ET','EU','EV','EW','EX','EY','EZ'
    
];
for ($row = 1; $row <= $highestRow; $row++){
    if ($row==1) {
        foreach ($abc_array as $key => $value) {
            //echo $value.$row."=".$sheet->getCell($value.$row)->getValue()."<br>";
            $valor_excel=$sheet->getCell($value.$row)->getValue();
            if (trim($valor_excel)=='Celular') {
                $index_celular=$value;
            }

            if (trim($valor_excel)=='Total') {
                $index_total=$value;
            }
        }

       // echo $index_celular."<br>".$index_total;
        
    }else{

        //echo "Celular:".$sheet->getCell($index_celular.$row)->getValue()."-Total:".$sheet->getCell($index_total.$row)->getValue()."<br>";
        $explode=explode(':',$sheet->getCell($index_total.$row)->getValue());

        $parte1=str_replace("=SUM(","",$explode[0]);
        $parte2=str_replace(")","",$explode[1]);

        //echo  $sheet->getCell($parte2)->getValue()."<br>";
        //echo $parte2."<br>";
        //print_r($explode);
        $total=$sheet->getCell($parte1)->getValue()+$sheet->getCell($parte2)->getValue();
        $celular=$sheet->getCell($index_celular.$row)->getValue();
        //echo "total=".$total."<br>";
        
        $sql='SELECT * FROM celulares WHERE celular="'.$celular.'" ';
        $query=$conn->query($sql) ;
        $result=$query->fetch_assoc();
        
        if ($result['total_plan']<$total && $query->num_rows>0) {
        $diferencia=($total-$result['total_plan']);

        $str.='El total del plan del celular '.$result['celular'].' presenta una diferencia de $/'.$diferencia.'<br>';
        }
    }
    
}
unlink($dest_path);
?>
<html>
    <head>
        <title>Importa documento</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"> 
    </head>
    <body>
        <div class="container" style="padding-top:15px;">
            <?php if($str!=''):?>
                <div class="alert alert-danger" role="alert"><?php echo $str ?></div>
            <?php else:?>
                <div class="alert alert-success" role="alert">No se presento novedad</div>
            <?php endif;?>
        </div>
        
    </body>
</html>