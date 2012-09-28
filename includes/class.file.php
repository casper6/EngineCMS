<?
	class file
	{
		var $fileInfo = "";
		var $filePath = "";
		var $fileStat = "";
		var $mask = '';
		/**
		 * constructor
		 *
		 * @param string $path the path to a file or folder
		 */
		function __construct($path = null)
		{
			if(!is_null($path))
			{
			if(file_exists($path))
			{
				$this->filePath = $path;
				if(is_file($this->filePath))
				{
					$this->fileStat = @stat($path);
					$this->fileInfo['size'] = $this->fileStat[7];
					$this->fileInfo['atime'] = $this->fileStat[8];
					$this->fileInfo['ctime'] = $this->fileStat[10];	
					$this->fileInfo['mtime'] = $this->fileStat[9];
					$this->fileInfo['path'] = $path;
					$this->fileInfo['name'] = basename($path);	
					$this->fileInfo['is_writable'] = $this->isWritable();
					$this->fileInfo['is_readable'] = $this->isReadable();
				}elseif(is_dir($this->filePath))
				{
					$this->fileStat = @stat($path);
					$this->fileInfo['name'] = basename($path);
					$this->fileInfo['path'] = $path;
					$this->fileInfo['atime'] = $this->fileStat[8];
					$this->fileInfo['ctime'] = $this->fileStat[10];	
					$this->fileInfo['mtime'] = $this->fileStat[9];
					$this->fileInfo['is_writable'] = $this->isWritable();
					$this->fileInfo['is_readable'] = $this->isReadable();					
				}
			}else 
			{
				trigger_error('Нет такого файла. ' . $path, E_USER_NOTICE);	
			}				
			}


			
		}
		/**
		 * contructor
		 *
		 * @param string $path
		 */
		function file($path=null)
		{
			$this->__construct($path);
		}
		
		
		/**
		 * check if a file or folder writable
		 *
		 * @param file path $path
		 * @return boolean
		 */
	function isWritable($path=null)
	{
		$path = is_null($path)?$this->filePath:$path;
		//Windows
		if (DIRECTORY_SEPARATOR == "\\")
		{
			$path = toOSPath($path);
			if(is_file($path))
			{
				$fp = @fopen($path,'ab');
				if($fp)
				{
					fclose($fp);
					return true;
				}
			}elseif(is_dir($path))
			{
					$tmpnam = time().md5(uniqid('iswritable'));
					if (@touch($path . '\\' . $tmpnam)) {
						unlink($path . '\\' . $tmpnam);
						return true;
					}			
			}
			return false;			
		}else 
		{
			return is_writable(toOSPath($path));
		}

	}
	/**
	 * Returns true if the files is readable.
	 *
	 * @return boolean true if the files is readable.
	 */
	function isReadable($path =null) 
	{
		$path = is_null($path)?$this->filePath:$path;
		return is_readable(toOSPath($path));
	}		
	/**
	 * change the modified time
	 *
	 * @param string $path
	 * @param string $time
	 * @return boolean
	 */
	function setLastModified($path=null, $time) 
	{
		$path = is_null($path)?$this->filePath:$path;
		$time = is_null($time)?time():$time;
		return touch(toOSPath($path), $time);
	}

		/**
		 * Creates a new directory.
		 *
		 * @return boolean true- success, false - failure
		 */
		function mkdir($path = null, $mask=null, $dirOwner='') 
		{
			$path = is_null($path)?$this->filePath:$path;
			$mask = is_null($mask)?$this->mask:$mask;
			
			$status = mkdir(toOSPath($path));			
			if ($mask)
				@chmod(toOSPath($path), intval($mask, 8));
			if($dirOwner)
				$this->chown(toOSPath($path), $dirOwner);
			return $status;
		}	
		
	function chown($path, $owner) {
		if ($owner == "")
			return;

		$owner = explode(":", $owner);

		// Only user
		if (count($owner) == 1)
			array_push($owner, "");

		// Hmm
		if (count($owner) != 2)
			return;

		// Set user
		if ($owner[0] != "")
			@chown($path, $owner[0]);

		// Set group
		if ($owner[1] != "")
			@chgrp($path, $owner[1]);
	}		

    /**
         * Copy a file, or recursively copy a folder and its contents
         * @author      Aidan Lister <aidan@php.net>
         * @author      Paul Scott
         * @version     1.0.1
         * @param       string   $source    Source path
         * @param       string   $dest      Destination path
         * @return      bool     Returns TRUE on success, FALSE on failure
         */
    function copyTo($source, $dest)
    {
	 		if(!file_exists($dest) || !is_dir($dest))
			{
				if($this->mkdir($dest))
				{
					// Copy in to your self?
					if (strpos(getAbsPath($file), getAbsPath($dest)) === 0)
					{
						return false;		
					}
	        // Simple copy for a file
	        if (is_file($source))
	        {
	            return copy($source, $dest);
	        }elseif(is_dir($source))
	        {
		        // Loop through the folder
		        $dir = dir($source);
		        while (false !== ($entry = $dir->read()))
		        {
		            // Skip pointers
		            if ($entry == '.' || $entry == '..')
		            {
		                continue;
		            }
		            // Deep copy directories
		            if ($dest !== "$source/$entry")
		            {
		                $this->copyTo("$source/$entry", "$dest/$entry");
		            }
		        }
		        // Clean up
		        $dir->close();	 
		        return true;       	
	        }
				}					
			}   
        return false;
    }		
    /**
     * get file information
     *
     * @return array
     */
    function getFileInfo()
    {
    	return $this->fileInfo;
    }
    /**
     * close 
     *
     */
    function close()
    {
    	$this->fileInfo = null;
    	$this->fileStat = null;
    }
 	/**
	 * delete a file or a folder and all contents within that folder
	 *
	 * @param string $path
	 * @return boolean
	 */
	function delete($path = null)
	{
		$path = is_null($path)?$this->filePath:$path;
		if(file_exists($path))
		{
			if(is_file($path))
			{
				return unlink($path);
			}elseif(is_dir($path))
			{
				return $this->__recursive_remove_directory($path);
			}
			
		}else 
		{
			echo 'Файл не обнаружен.';
		}
		return false;
	}
	/**
	 * empty a folder
	 *
	 * @param string $path
	 * @return boolean
	 */
	function emptyFolder($path)
	{
		$path = is_null($path)?$this->filePath:"";
		if(file_exists($path) && is_dir($path))
		{
			return $this->__recursive_remove_directory($path, true);
		}
		return false;
	}
/**
 * recursive_remove_directory( directory to delete, empty )
 * expects path to directory and optional TRUE / FALSE to empty
 * of course PHP has to have the rights to delete the directory
 * you specify and all files and folders inside the directory
 * 
 * to use this function to totally remove a directory, write:
 * recursive_remove_directory('path/to/directory/to/delete');
 * to use this function to empty a directory, write:
 *	recursive_remove_directory('path/to/full_directory',TRUE);
 * @param string $directory
 * @param boolean $empty
 * @return boolean
 */
 function __recursive_remove_directory($directory, $empty=FALSE)
 {
     // if the path has a slash at the end we remove it here
     if(substr($directory,-1) == '/')
     {
         $directory = substr($directory,0,-1);
     }
  
     // if the path is not valid or is not a directory ...
     if(!file_exists($directory) || !is_dir($directory))
     {
         // ... we return false and exit the function
         return FALSE;
  
     // ... if the path is not readable
     }elseif(!is_readable($directory))
     {
         // ... we return false and exit the function
         return FALSE;
  
     // ... else if the path is readable
     }else{
  
         // we open the directory
         $handle = opendir($directory);
  
         // and scan through the items inside
         while (FALSE !== ($item = readdir($handle)))
         {
             // if the filepointer is not the current directory
             // or the parent directory
             if($item != '.' && $item != '..')
             {
                 // we build the new path to delete
                 $path = $directory.'/'.$item;
  
                 // if the new path is a directory
                 if(is_dir($path))                  {
                     // we call this function with the new path
                     $this->__recursive_remove_directory($path);
  
                 // if the new path is a file
                 }else{
                    // we remove the file
                    unlink($path);
                 }
             }
         }
         // close the directory
         closedir($handle);
  
        // if the option to empty is not set to true
         if($empty == FALSE)
         {
             // try to delete the now empty directory
             if(!rmdir($directory))
             {
                 // return false if not possible
                 return FALSE;
             }
         }
         // return success
         return TRUE;
     }
 }   		
	}

?>