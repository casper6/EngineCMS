<?php
// Распаковка закачанного на сервер архива с CMS
if(!function_exists('exec')) die('exec function is not available on this server');
if(!$unzip_command = exec('/usr/bin/which unzip')) die();
 
if(!$dir_handle = opendir(getcwd())) die ('Can\'t open dir');
while(false != ($files = readdir($dir_handle))){
    if($files != '.' && $files != '..'){
	if(preg_match('/.\.zip/',$files)){
          exec("$unzip_command $files",$output);
	  echo '<b>Unzipping ',$files,' </b><br>';
	  foreach($output as $unzipped_files)
	      echo "$unzipped_files",'<span style="color: green"> done!</span><br>';
        }
     }
}
?>