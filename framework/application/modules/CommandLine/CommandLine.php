<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/*** Constants ***/

/**
 * Create a short constant for the Path Separator if it is not already defined.
 */
if(!defined('PS')) define('PS', PATH_SEPARATOR);

/*** end Constants ***/


/**
 * CommandLine
 *
 * The CommandLine Class is used to execute scripts "behind the scenes" from the command line.
 *
 * To use this class, first instantiate a new instance of the class passing the
 * language of the script (if not php.) Then use the runScript method to pass and
 * run the script. The language can be reset to run a different script.
 *
 * @example		$cl=new CommandLine('python');
 *				$cl->runScript('Python/myPythonScript.py');
 *				$cl->setLanguage('php');
 *				$cl->runScript('BackgroundPHP/myPHP_Script.php');
 */
class CommandLine
{
	/*** data members ***/

	private $language='php';
	private $script=NULL;

	/*** End data members ***/



	/*** magic methods ***/

	/**
	 * __construct
	 *
	 * Description of the constructor.
	 *
	 * @param	$language					The scripting language of the script to run.
	 * @access	public
	 */
	public function __construct($language='php')
	{
		try
		{
			# Set the script language to the data member.
			$this->setLanguage($language);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- __construct

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setLanguage
	 *
	 * Sets the data member $language.
	 *
	 * @param	$language
	 * @access	public
	 */
	public function setLanguage($language)
	{
		# Check if the passed value is empty.
		if(empty($language) OR !is_string($language))
		{
			# Explicitly set the language to "php".
			$language='php';
		}
		# Set the data member.
		$this->language=$language;
	} #==== End -- setLanguage

	/**
	 * setScript
	 *
	 * Sets the data member $script.
	 *
	 * @param	$file
	 * @access	public
	 */
	public function setScript($file)
	{
		# Check if the passed value is empty.
		if(empty($file) OR !is_string($file))
		{
			# Explicitly set the language to "php".
			throw new Exception('There must be a filename passed to run a script in the background!', E_RECOVERABLE_ERROR);
		}
		if($this->getLanguage()!='ffmpeg')
		{
			# Explicitly remove the BASE_PATH from the filename for normalization.
			$file=str_replace(BASE_PATH, '', $file);
			# Prepend the filename with the BASE_PATH to make a complete path.
			$file=BASE_PATH.$file;
		}
		# Set the data member.
		$this->script=$file;
	} #==== End -- setScript

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getLanguage
	 *
	 * Returns the data member $language.
	 *
	 * @access	private
	 */
	private function getLanguage()
	{
		return $this->language;
	} #==== End -- getLanguage

	/**
	 * getScript
	 *
	 * Returns the data member $script.
	 *
	 * @access	private
	 */
	private function getScript()
	{
		return $this->script;
	} #==== End -- getScript

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * runScript
	 *
	 * Runs the passed script in the background. Errors on Linux are written to a log file.
	 *
	 * @access	public
	 * @param	script						The path to the script to execute.
	 * @param	$params						Add extra arguments after the script. Can be an array or a string, NOT an object.
	 */
	public function runScript($script=NULL, $params=NULL)
	{
		try
		{
			# Set the script to the data member effectively "cleaning" it.
			$this->setScript($script);
			# Get the correct executable.
			$getExecutableMethod='get'.strtoupper($this->getLanguage()).'_Executable';

			if($this->getLanguage()=='php' || $this->getLanguage()=='python')
			{
				$command=$this->$getExecutableMethod().' -f '.$this->getScript();
			}
			else
			{
				$command=$this->$getExecutableMethod().' '.$this->getScript();
			}

			# Check if $params is set, and if it's an array.
			if(isset($params) && is_array($params))
			{
				# Function to flatten multidimensional arrays.
				function flatten(array $params)
				{
					# Create an empty array.
					$new_params=array();
					# Convert the multidimensional to a single dimensional array.
					array_walk_recursive($params, function($a, $b) use (&$new_params) { $new_params[$b] = $a; });
					# Return the new array.
					return $new_params;
				}
				# Call the flatten function.
				$new_params=flatten($params);

				# Get the array keys.
				$keys=array_keys($new_params);
				# Separate the keys with a pipe (|).
				$pipe_separated_keys=implode("|", $keys);

				# Get the array values.
				$values=array_values($new_params);
				# Separate the values with a pipe (|).
				$pipe_separated_values=implode("|", $values);

				$command.=' "'.$pipe_separated_keys.'" "'.$pipe_separated_values.'"';
			}
			# If $params is not an array or an object.
			elseif(isset($params) && !is_object($params))
			{
				$command.=$params;
			}

			//passthru($doc->$getExecutableMethod().' '.BASE_PATH.$script.' '.$argv_parameter.' >> '.LOGS.'log_file.log 2>&1 &');
			# Run it in the background.
			$this->execInBackground($command);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- runScript

	/*** End public methods ***/



	/*** protected methods ***/

	/**
	 * execInBackground
	 *
	 * Execute the passed command in the background. On linux errors are written to a log file.
	 *
	 * @access	protected
	 * @param	command						The command to execute in the background.
	 */
	protected function execInBackground($command)
	{
		try
		{
			//echo microtime().'<br/>';
			//echo $command.'<br/>';
			# Check if this is a Windows server.
			if($this->isWindows()===TRUE)
			{
				# Execute the script in the background Windows style.
				pclose(popen('start /B '. $command, 'r'));
				//echo 'Did that on Windows.<br/>';
			}
			else
			{
				# Create time. Example: [Fri Jan 30 14:33:59 2014]
				$debug_date='['.date('D M d H:i:s Y').'] ';
				# Execute the script in the background Linux style. Writes errors to a log file.
				exec($command.' >> '.LOGS.'cl_log_file.log &', $arrOutput);
				//exec($command." > /dev/null &", $arrOutput);
				$error_result=print_r($arrOutput,TRUE);
				if(!empty($error_result))
				{
					//file_put_contents(LOGS.'cl_log_file.log', $debug_date." ".$command."\r\n", FILE_APPEND | LOCK_EX);
					//file_put_contents(LOGS.'cl_log_file.log', $debug_date.' '.$error_result."\r\n", FILE_APPEND);
				}
				//echo 'Did that.<br/>';
			}
			//echo microtime().'<br/>';
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- execInBackground

	/*** End protected methods ***/



	/*** private methods ***/

	/**
	 * getPHP_Executable
	 *
	 * Determine the location of the PHP executable.
	 *
	 * @access	private
	 * @return	The executable location on success, otherwise FALSE.
	 */
	public function getPHP_Executable()
	{
		# Set the default executable path as FALSE.
		$php_executable_path=FALSE;

		try
		{
			# Set the default executable to a local variable.
			$php_executable=DS.'php'.(($this->isWindows()===TRUE) ? '.exe' : '');
			# First try the PHP_BINARY constant.
			if(defined('PHP_BINARY') && file_exists(PHP_BINARY) && is_file(PHP_BINARY))
			{
				$php_executable_path=PHP_BINARY;
			}
			# Try the PHP_BINDIR constant.
			elseif(defined('PHP_BINDIR') && file_exists(PHP_BINDIR.$php_executable) && is_file(PHP_BINDIR.$php_executable))
			{
				$php_executable_path=PHP_BINDIR.$php_executable;
			}
			else
			{
				# Explode the PATH into an array of individual paths.
				$paths=explode(PS, getenv('PATH'));
				# Loop through the paths.
				foreach($paths as $path)
				{
					# Set the default executable to a local variable.
					$php_executable_path=$path.$php_executable;
					# Check if this is a windows server and if "php.exe" explicitly exists in the path. Is so, set this path to the local variable.
					if(($this->isWindows()===TRUE) && strstr($path, 'php.exe'))
					{
						$php_executable_path=$path;
						break;
					}
					# Check if the path to the executable in the local variable actually exists.
					if(file_exists($php_executable_path) && is_file($php_executable_path))
					{
						 break;
					}
				}
			}
			return $php_executable_path;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getPHP_Executable

	/**
	 * getPYTHON_Executable
	 *
	 * Determine the location of the Python executable.
	 *
	 * @access	private
	 * @return	The executable location on success, otherwise FALSE.
	 */
	public function getPYTHON_Executable()
	{
		# Set the default executable path as FALSE.
		$python_executable_path=FALSE;

		try
		{
			# Explode the PATH into an array of individual paths.
			$paths=explode(PS, getenv('PATH'));
			# Loop through the paths.
			foreach($paths as $path)
			{
				# Set the default executable to a local variable.
				$python_executable_path=$path.DS.'python';
				# Check if this is a windows server.
				if($this->isWindows()===TRUE)
				{
					$python_executable_path+='.exe';
					# Check if "php.exe" explicitly exists in the path. Is so, set this path to the local variable.
					if(strstr($path, 'python.exe'))
					{
						$python_executable_path=$path;
						break;
					}
				}
				# Check if the path to the executable in the local variable actually exists.
				if(file_exists($python_executable_path) && is_file($python_executable_path))
				{
					 break;
				}
			}
			return $python_executable_path;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getPYTHON_Executable

	/**
	 * getFFMPEG_Executable
	 *
	 * Determine the location of the ffmpeg executable.
	 *
	 * @access	private
	 * @return	The executable location on success, otherwise FALSE.
	 */
	public function getFFMPEG_Executable()
	{
		# Set the default executable path as FALSE.
		$ffmpeg_executable_path=FALSE;

		try
		{
			# Explode the PATH into an array of individual paths.
			$paths=explode(PS, getenv('PATH'));
			# Loop through the paths.
			foreach($paths as $path)
			{
				# Set the default executable to a local variable.
				$ffmpeg_executable_path=$path.DS.'ffmpeg';
				# Check if this is a windows server.
				if($this->isWindows()===TRUE)
				{
					$ffmpeg_executable_path+='.exe';
					# Check if "php.exe" explicitly exists in the path. Is so, set this path to the local variable.
					if(strstr($path, 'ffmpeg.exe'))
					{
						$ffmpeg_executable_path=$path;
						break;
					}
				}
				# Check if the path to the executable in the local variable actually exists.
				if(file_exists($ffmpeg_executable_path) && is_file($ffmpeg_executable_path))
				{
					 break;
				}
			}
			return $ffmpeg_executable_path;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getFFMPEG_Executable

	/**
	 * isWindows
	 *
	 * Determines if PHP is installed on a Windows server or not.
	 *
	 * @access	private
	 * @return	Boolean						TRUE if on Windows, FALSE if not.
	 */
	private function isWindows()
	{
		try
		{
			# Check if the $_SERVER['WINDIR'] server variable is set. If it is, then this must be a windows server.
			return isset($_SERVER['WINDIR']);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- isWindows

	/*** End private methods ***/

} #=== End CommandLine class.