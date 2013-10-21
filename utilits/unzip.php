<?php
// Распаковка закачанного на сервер архива с CMS
if(!function_exists('exec')) die('На этом сервере не найдена функция «exec». Облом.');
if(!$unzip_command = exec('/usr/bin/which unzip')) die();
if(!$dir_handle = opendir(getcwd())) die ('Не могу открыть папку');
while(false != ($files = readdir($dir_handle))){
    if($files != '.' && $files != '..'){
	if(preg_match('/.\.zip/',$files)){
          exec("$unzip_command $files",$output);
	  echo '<b>Распаковка ',$files,' </b><br>';
	  foreach($output as $unzipped_files)
	      echo "$unzipped_files",'<span style="color: green"> Готово.</span><br>';
        }
     }
}
?>