<?php
// Настройки
$imagick = true; // Если картинки не закачиваются - можно попробовать установить в false
if(!eregi('image/', $_FILES['file']['type'])) exit(0);

foreach ($_FILES['file'] as $secvalue) {
    $secvalue = str_replace("(", "&#040;", str_replace(")", "&#041;", $secvalue));
    if ( (preg_match("/<[^>]*script*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*object*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*iframe*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*applet*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*meta*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*style*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*form*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*onmouseover*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onmouseout*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onmousemove*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onmouseup*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onload*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onreset*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onresize*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*body*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/\"/i", $secvalue)) ) {
        echo 'Ошибка безопасности';
        die;
    }
}
$folder =  '../img/';//директория в которую будет загружен файл
$type = str_replace("image/","",strtolower($_FILES['file']['type']));
if ($type == 'png' || $type == 'jpg' || $type == 'gif' || $type == 'jpeg' || $type == 'pjpeg') {
  $foto =  md5(date('YmdHis')).'.'.$type;
  if (is_uploaded_file($_FILES['file']['tmp_name'])) {
    if (move_uploaded_file($_FILES['file']['tmp_name'], $folder.$foto)) {
      if (extension_loaded('imagick') && class_exists("Imagick") && $imagick == true) {
        $image = new Imagick($folder.$foto);
        // сжатие
        list($width, $height, $type, $attr) = getimagesize($folder.$foto);
        if ($width > 1000) $image->thumbnailImage(1000,0); // по горизонтали до 1000 пикселей
        else if ($height > 1200) $image->thumbnailImage(0,1200); // по вертикали до 1200 пикселей
        // наводим резкость, если превью мелкое
        if ($width < 300) $image->sharpenImage(4, 1);
        //закругляем углы
        //$image->roundCorners(5, 5);
        // ориентация фото
        $orientation = exif_read_data($folder.$foto);
        if ($orientation['Orientation'] !== 0 && $orientation['Orientation'] !== 1 && $orientation['Orientation'] != "") {
            $degres = ($orientation['Orientation']- 1) * 90; 
            $image->rotateImage('', $degres);
        }
        $image->writeImage();
        $image->destroy();
      }
    }
    echo '/img/'.$foto;
  }
}
?>