<?
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "class.file.php");
class manager
{
	var $currentFolderPath;
	var $currentFolderInfo = array(
	'name'=>'',
	'subdir'=>0,
	'file'=>0,
	'ctime'=>'',
	'mtime'=>'',
	'is_readable'=>'',
	'is_writable'=>'',
	'size'=>0,
	'path'=>'',
	'type'=>'folder',
	);
	var $lastVisitedFolderPathIndex = 'ajax_last_visited_folder';
	var $folderPathIndex = "path";
	var $calculateSubdir = true;
	var $fileTypes = array(
	array(array("exe", "com"), "fileExe", "exe", 0),
	array(array("gif", "jpg", "png", "bmp", "tif"), "filePicture", "image", 1),
	array(array("zip", "sit", "rar", "gz", "tar"), "fileZip", "archive", 0),
	array(array("htm", "html", "php", "jsp", "asp"), "fileCode", "html", 0),
	array(array("mov", "ram", "rm", "asx", "dcr", "wmv"), "fileVideo", "video", 1),
	array(array("mpg", "avi", "asf", "mpeg"), "fileVideo", "movie", 0),
	array(array("aif", "aiff", "wav", "mp3", "wma"), "fileMusic", "music", 0),
	array(array("swf"), "fileFlash", "Flash file", 0),
	array(array("ppt"), "filePPT", "powerpoint", 0),
	array(array("rtf"), "fileRTF", "document", 0),
	array(array("doc"), "fileWord", "word", 0),
	array(array("pdf"), "fileAcrobat", "pdf", 0),
	array(array("xls", "csv"), "fileExcel", "excel", 0),
	array(array("txt"), "fileText", "txt", 1),
	array(array("xml", "xsl", "dtd"), "fileXml", "xml", 0)
	);
	
	/**
		 * constructor
		 */		
	function __construct($path = null, $calculateSubdir=true)
	{
		
		$this->calculateSubdir = $calculateSubdir;
		if(!is_null($path))
		{
			$this->currentFolderPath = $path;
		}elseif(isset($_GET[$this->folderPathIndex]) && file_exists($_GET[$this->folderPathIndex]) && !is_file($_GET[$this->folderPathIndex]) )
		{
			$this->currentFolderPath = $_GET[$this->folderPathIndex];
		}
		elseif(isset($_SESSION[$this->lastVisitedFolderPathIndex]) && file_exists($_SESSION[$this->lastVisitedFolderPathIndex]) && !is_file($_SESSION[$this->lastVisitedFolderPathIndex]))
		{
			$this->currentFolderPath = $_SESSION[$this->lastVisitedFolderPathIndex];
		}else
		{
			$this->currentFolderPath = CONFIG_SYS_DEFAULT_PATH;
		}
		$this->currentFolderPath = isUnderRoot(toUnixPath((addTrailingSlash($this->currentFolderPath))))?toUnixPath((addTrailingSlash($this->currentFolderPath))):CONFIG_SYS_DEFAULT_PATH;
		if($this->calculateSubdir)
		{
			$_SESSION[$this->lastVisitedFolderPathIndex] = $this->currentFolderPath;
		}
		
		
		//echo "contructor: " . $this->currentFolderPath . "<br>";
		if(is_dir($this->currentFolderPath))
		{
			$file = new file($this->currentFolderPath);
			$folderInfo = $file->getFileInfo();
			if(sizeof($folderInfo))
			{
				//displayArray($folderInfo);
				$this->currentFolderInfo['name']=basename($this->currentFolderPath);
				$this->currentFolderInfo['subdir']=0;
				$this->currentFolderInfo['file']=0;
				$this->currentFolderInfo['ctime']=$folderInfo['ctime'];
				$this->currentFolderInfo['mtime']=$folderInfo['mtime'];
				$this->currentFolderInfo['is_readable']=$folderInfo['is_readable'];
				$this->currentFolderInfo['is_writable']=$folderInfo['is_writable'];	
				$this->currentFolderInfo['path']  = $this->currentFolderPath;
				$this->currentFolderInfo['type'] = "folder";
				$this->currentFolderInfo['cssClass']='folder';
				//displayArray($currentFolderInfo, "contructor\n");
			}			
		}
		if($calculateSubdir && !file_exists($this->currentFolderPath))
		{
			die(ERR_FOLDER_NOT_FOUND . $this->currentFolderPath);
		}


	
	}
	/**
		 * constructor
		 */
	function manager($path = null, $calculateSubdir=true)
	{
		$this->__construct($path, $calculateSubdir);
	}
	/**
		 * get current folder path
		 * @return  string
		 */
	function getCurrentFolderPath()
	{
		return $this->currentFolderPath;
	}
	/**
		 * get file path
		 *	@return array
		 */
	function getFileList()
	{
		$outputs = array();
		$files = array();
		$folders = array();
		$tem = array();
		//echo "get file List: " . $this->currentFolderPath . "<br>";
		$dirHandler = @opendir($this->currentFolderPath);
		if($dirHandler)
		{
			while(false !== ($file = readdir($dirHandler)))
			{
				//strip out .  and ..
				if($file != '.' && $file != '..')
				{
					$path=$this->currentFolderPath.$file;
					if(is_dir($path) && (!CONFIG_SYS_INC_DIR_PATTERN || preg_match(CONFIG_SYS_INC_DIR_PATTERN, $file)) && (!CONFIG_SYS_EXC_DIR_PATTERN || !preg_match(CONFIG_SYS_EXC_DIR_PATTERN, $file)))
					{
						$this->currentFolderInfo['subdir']++;
						if(!$this->calculateSubdir)
						{			
						}else 
						{
								$folders[$file] = $this->getFolderInfo($path);
								$outputs[$file] = $folders[$file];							
						}

						
					}elseif(is_file($path)&& (!CONFIG_SYS_INC_FILE_PATTERN || preg_match(CONFIG_SYS_INC_FILE_PATTERN, $file)) && (!CONFIG_SYS_EXC_FILE_PATTERN || !preg_match(CONFIG_SYS_EXC_FILE_PATTERN, $file)) )
					{

							$obj = new file($path);
							$tem = $obj->getFileInfo();
							if(sizeof($tem))
							{
								$fileType = getFileType($file);
								foreach($fileType as $k=>$v)
								{
									$tem[$k] = $v;
								}
								$this->currentFolderInfo['size'] += $tem['size'];
								$this->currentFolderInfo['file']++;		
								$tem['path'] = toUnixPath($path);		
								$tem['type'] = "file";
								$files[$file] = $tem;
								$outputs[$file] = $tem;
								$tem = array();
								$obj->close();
								
							}							

				
					}
					
				}
			}
			ksort($outputs);
			//displayArray($outputs, "get list\n");
			@closedir($dirHandler);
		}else
		{
			trigger_error('Unable to locate the folder ' . $this->currentFolderPath, E_NOTICE);
		}
		return $outputs;
	}


	/**
	 * get current dir information
	 *
	 * @param string $path
	 * @return array
	 */
	function getFolderInfo($path=null)
	{
		if(is_null($path))
		{
			return $this->currentFolderInfo;
		}else 
		{
			$obj = new manager($path, false);
			$obj->getFileList();
			return $obj->getFolderInfo();			
		}

	}



	/**
		 * return the predefined file types
		 *
		 * @return arrray
		 */
	function getFileTypes()
	{
		return $this->fileTypes;
	}
	/**
		 * print out the file types
		 *
		 */
	function printFileTypes()
	{
		foreach($fileTypes as $fileType)
		{
			if(isset($fileType[0]) && is_array($fileType[0]))
			{
				foreach($fileType[0] as $type)
				{
					echo $type. ",";
				}
			}
		}
	}


}
?>