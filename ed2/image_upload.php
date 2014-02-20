<?php
// Настройка
$imagick = true; // Если картинки не закачиваются - можно попробовать установить в false
$folder =  '../img/'; //директория в которую будет загружен файл

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

$_FILES['file']['name'] = basename($_FILES['file']['name']);

if (!empty($_FILES['file']['name'])) {
  $type = str_replace("image/","",strtolower($_FILES['file']['type']));
  if ($type == 'png' || $type == 'jpg' || $type == 'gif' || $type == 'jpeg') {
    $foto =  md5(date('YmdHis')).'.'.$type;
    if (is_uploaded_file($_FILES['file']['tmp_name'])) {
      if (move_uploaded_file($_FILES['file']['tmp_name'], $folder.$foto)) {
        // Обработка фото (при наличии библиотеки Imagick)
        if (extension_loaded('imagick') && class_exists("Imagick") && $imagick == true && $type != 'gif') {
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
      $array = array('filelink' => str_replace("..","",$folder).$foto);
      //if (is_image($folder.$foto)) 
        echo stripslashes(json_encode($array)); // 
    }
  }
}

// проверка изображения на безопасность
function is_image($image_path) {
    if (!$f = fopen($image_path, 'rb')) {
        return false;
    }
    $data = fread($f, 8);
    fclose($f);
    // signature checking
    $unpacked = unpack("H12", $data);
    if (array_pop($unpacked) == '474946383961' || array_pop($unpacked) == '474946383761') return "gif";
    $unpacked = unpack("H4", $data);
    if (array_pop($unpacked) == 'ffd8') return "jpg";
    $unpacked = unpack("H16", $data);
    if (array_pop($unpacked) == '89504e470d0a1a0a') return "png";
    return false;
}
?>