<?php
// Настройки
$imagick = true; // Если картинки не закачиваются - можно попробовать установить в false

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
if ($type == 'png' || $type == 'jpg' || $type == 'gif' || $type == 'jpeg') {
  $foto =  md5(date('YmdHis')).'.'.$type;
  if (is_uploaded_file($_FILES['file']['tmp_name'])) {
    if (move_uploaded_file($_FILES['file']['tmp_name'], $folder.$foto)) {
      if (extension_loaded('imagick') && class_exists("Imagick") && $imagick == true && $type != 'gif') {
        $image = new Imagick($folder.$foto);
        // сжатие
        list($width, $height, $type, $attr) = getimagesize($folder.$foto);
        if ($width > 1000) $image->thumbnailImage(1000,0); // по горизонтали до 1000 пикселей
        else if ($height > 1200) $image->thumbnailImage(0,1200); // по вертикали до 1200 пикселей
        // наводим резкость, если превью мелкое
        if ($width < 300) $image->sharpenImage(4, 1);
        //закругляем углы
        $image->roundCorners(5, 5);
          /*
        $orientation = exif_read_data($folder.$foto);
        if ($orientation['Orientation'] !== 0 && $orientation['Orientation'] !== 1 && $orientation['Orientation'] != "") {
            $degres = ($orientation['Orientation']- 1) * 90; 
            $image->rotateImage('', $degres);
        }
        */
        $image->writeImage();
        $image->destroy();
        // ориентация фото
        orient_image($folder.$foto);
      }
    }
    echo '/img/'.$foto;
  }
}

function orient_image($file_path) {
    if (!function_exists('exif_read_data')) {
        return false;
    }
    $exif = @exif_read_data($file_path);
    if ($exif === false) {
        return false;
    }
    $orientation = intval(@$exif['Orientation']);
    if (!in_array($orientation, array(3, 6, 8))) {
        return false;
    }
    $image = @imagecreatefromjpeg($file_path);
    switch ($orientation) {
        case 3:
            $image = @imagerotate($image, 180, 0);
            break;
        case 6:
            $image = @imagerotate($image, 270, 0);
            break;
        case 8:
            $image = @imagerotate($image, 90, 0);
            break;
        default:
            return false;
    }
    $success = imagejpeg($image, $file_path);
    // Free up memory (imagedestroy does not delete files):
    @imagedestroy($image);
    return $success;
}
?>