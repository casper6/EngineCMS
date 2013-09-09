<?php
// Настройка
$dir =  '../img/'; //директория в которую будет загружен файл

$contentType = $_POST['contentType'];
$data = base64_decode($_POST['data']);

    $filename =  md5(date('YmdHis')).'.png';
    $file = $dir.$filename;
    file_put_contents($file, $data);

        // Обработка фото (при наличии библиотеки Imagick)
        if (extension_loaded('imagick') && class_exists("Imagick")) {
          $image = new Imagick($dir.$filename);
          // сжатие
          list($width, $height, $type, $attr) = getimagesize($dir.$filename);
          if ($width > 1000) $image->thumbnailImage(1000,0); // по горизонтали до 1000 пикселей
          else if ($height > 1200) $image->thumbnailImage(0,1200); // по вертикали до 1200 пикселей
          // наводим резкость, если превью мелкое
          if ($width < 300) $image->sharpenImage(4, 1);
          // ориентация фото
          $orientation = exif_read_data($dir.$filename);
          if ($orientation['Orientation'] !== 0 && $orientation['Orientation'] !== 1 && $orientation['Orientation'] != "") {
              $degres = ($orientation['Orientation']- 1) * 90; 
              $image->rotateImage('', $degres);
          }
          $image->writeImage();
          $image->destroy();
        }
      
      //if (is_image($dir.$filename)) 

echo stripslashes(json_encode(array('filelink' => str_replace("..","",$dir).$filename)));


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