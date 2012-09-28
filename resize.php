<?php 

// f - имя файла 
// type - способ масштабирования 
// q - качество сжатия 
// src - исходное изображение 
// dest - результирующее изображение 
// w - ширниа изображения 
// ratio - коэффициент пропорциональности 
// str - текстовая строка 
if (!isset($q)) $q = 85;
// тип преобразования, если не указаны размеры 
if ($type == 0) $w = 25;  // квадратная  
if ($type == 1) $w = 72;  // квадратная  
if ($type == 2) {$w = 70; $q = 60;}// пропорциональная шириной 
// качество jpeg по умолчанию

//$f = base64_decode($f);
$tip = explode(".", $f); 
$tip = strtolower($tip[1]);
echo $tip;
#Здесь как обычно проверяются входные параметры. Вы, конечно, можете задать более жёсткие условия проверки. Смотрим дальше. 

// создаём исходное изображение на основе 
// исходного файла и опеределяем его размеры 

if ($tip == "jpg" or $tip == "jpeg") $src = @imagecreatefromjpeg($f) or die("Error: imagecreate");
if ($tip == "png") $src = @imagecreatefrompng($f) or die("Error: imagecreate");
if ($tip == "gif") $src = @imagecreatefromgif($f) or die("Error: imagecreate");

if (!$src) { /* проверить, удачно ли */
$src  = imagecreate (150, 30); /* создать пустое изображение */
$bgc = imagecolorallocate ($src, 255, 255, 255);
$tc  = imagecolorallocate ($src, 0, 0, 0);
imagefilledrectangle ($src, 0, 0, 150, 30, $bgc);
imagestring ($src, 1, 5, 5, "Error loading $f", $tc);
}


$w_src = imagesx($src); 
$h_src = imagesy($src);

if ($tip == "jpg" or $tip == "jpeg") header("Content-type: image/jpeg");
if ($tip == "png") header("Content-type: image/png");
if ($tip == "gif") header("Content-type: image/gif");
/* В этой части программы мы загружаем исходное изображение в переменную $src. Функции imagesx и imagesy определяют размеры исходной картинки и записывают их в соответствующие переменные. Они нам понадобятся для вычисления коэффициента пропорциональности. Здесь же с помощью функции header передаём заголовок Content-type: image/jpeg в браузер пользователя. После этого он ожидает, что следующий поток данных будет jpeg-файлом. 

Следующее условие отвечает за размер выводимой картинки и непосредственно за вывод. Возможно, что исходная картинка уже нужного размера. Если это не так, обработаем её. Для этого поставим следующее условие: */

// если размер исходного изображения 
// отличается от требуемого размера
if ($type==3) {
$dest = imagecreatetruecolor($w_src,$h_src); 
       $str = "$tip"; // имя сайта
       imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_src, $h_src, $w_src, $h_src); 
       // определяем координаты вывода текста 
        $size = 5; // размер шрифта 
        $x_text = $w_src-imagefontwidth($size)*strlen($str)-3; 
        $y_text = $h_src-imagefontheight($size)-3; 
       // определяем каким цветом на каком фоне выводить текст 
        $white = imagecolorallocate($dest, 255, 255, 255); 
        $black = imagecolorallocate($dest, 0, 0, 0); 
        $gray = imagecolorallocate($dest, 127, 127, 127); 
        if (imagecolorat($dest,$x_text,$y_text)>$gray) $color = $black; 
        if (imagecolorat($dest,$x_text,$y_text)<$gray) $color = $white; 
       // выводим текст 
        imagestring($dest, $size, $x_text-1, $y_text-1, $str,$white-$color); 
        imagestring($dest, $size, $x_text+1, $y_text+1, $str,$white-$color); 
        imagestring($dest, $size, $x_text+1, $y_text-1, $str,$white-$color); 
        imagestring($dest, $size, $x_text-1, $y_text+1, $str,$white-$color); 
        imagestring($dest, $size, $x_text-1, $y_text,   $str,$white-$color); 
        imagestring($dest, $size, $x_text+1, $y_text,   $str,$white-$color); 
        imagestring($dest, $size, $x_text,   $y_text-1, $str,$white-$color); 
        imagestring($dest, $size, $x_text,   $y_text+1, $str,$white-$color); 
        imagestring($dest, $size, $x_text,   $y_text,   $str,$color); 
       // вывод картинки и очистка памяти 
	
	
if ($tip == "jpg" or $tip == "jpeg") imagejpeg($dest,'',$q); 
if ($tip == "png") imagepng($dest,''); 
if ($tip == "gif") imagegif($dest,''); 

	imagedestroy($dest); 
	imagedestroy($src); 
} else {
if ($w_src != $w || $h_src != $w) {

// вычисление пропорций
$ratio = $w_src/$w; // width ratio
if ($h_src >= $w_src) { $ratio = $h_src/$w; }


# Следующая часть программы будет подгонять картинку под ширину 218 пикселей (пропорциональное уменьшение) для случая $type=1.

//////////////////////////////////////////////////////////////////////////////

// операции для получения прямоугольного файла 
   if ($type==2) {
       // вычисление пропорций 
       $ratio = $w_src/$w; 
       $w_dest = round($w_src/$ratio); 
       $h_dest = round($h_src/$ratio); 
       // создаём пустую картинку 
       // важно именно truecolor!, иначе будем иметь 8-битный результат 
       $dest = imagecreatetruecolor($w_dest,$h_dest) or die("Error: imagecreatetruecolor");
       imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src) or die("Error: imagecopyresampled"); 
    }
    
//////////////////////////////////////////////////////////////////////////////

#Несколько сложнее выглядит часть кода для получения квадратного фрагмента.

    // операции для получения квадратного файла 
    if (($type==0)||($type==1)) {
         // создаём пустую квадратную картинку 
         // важно именно truecolor!, иначе будем иметь 8-битный результат 
         $dest = imagecreatetruecolor(137,103); 
if ($w_src > $h_src) imagecopyresampled($dest, $src, 0, 0, round((max($w_src,$h_src)-min($w_src,$h_src))/2), 0, 137, 103, min($w_src,$h_src), $h_src); 
else imagecopyresampled($dest, $src, 0, 0, 0, round((max($w_src,$h_src)-min($w_src,$h_src))/2), 137, 103, $w_src, min($w_src,$h_src)); 
#imagecopyresized($dest, $src, 0, 0, 0, 0, $w, $w, $w, $w); 
     }
     
//////////////////////////////////////////////////////////////////////////////

	// вывод картинки и очистка памяти 
if ($tip == "jpg" or $tip == "jpeg") imagejpeg($dest,'',$q); 
if ($tip == "png") imagepng($dest,''); 
if ($tip == "gif") imagegif($dest,''); 
	imagedestroy($dest); 
	imagedestroy($src); 

}

}
