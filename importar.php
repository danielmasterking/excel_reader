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

$str='';
for ($row = 2; $row <= $highestRow; $row++){
    $celular=$sheet->getCell("C".$row)->getValue();
   $sql='SELECT * FROM celulares WHERE celular="'.$celular.'" ';
    $query=$conn->query($sql) ;
    $result=$query->fetch_assoc();
    //print_r($result);
    $total=$sheet->getCell("K".$row)->getValue()+$sheet->getCell("AR".$row)->getValue();

    if ($result['total_plan']<$total && $query->num_rows>0) {
       $diferencia=($total-$result['total_plan']);

       $str.='El total del plan del celular '.$result['celular'].' presenta una diferencia de $/'.$diferencia.'<br>';
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