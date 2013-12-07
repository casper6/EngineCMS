<?php
echo preg_replace('/images\/[-\w\/\.]*/ie','"data:image/".((substr("\\0",-4)==".png")?"png":"gif").";base64,".base64_encode(file_get_contents("\\0"))',file_get_contents('style.css'));
?>