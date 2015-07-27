<?php /* framework/application/modules/Logger/Logger.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

/**
 * Logger
 *
 * Contains __construct(), writeLogFile(), closeLogFile() public methods.
 * - __construct sets path and name of log file
 * - writeLogFile writes message to the log file (and implicitly opens log file)
 * - closeLogFile closes log file
 *
 * To use this class, pass the log file name into the __construct(),
 * pass the message into the writeLogFile() method, then close the log file with closeLogFile().
 *
 * @example		$logger_obj=new Logging('logfile.log');
 *				$logger_obj->writeLogFile('Log this message to log file');
 *				$logger_obj->closeLogFile();
 */
class Logger
{
	/*** data members ***/

    private $log_file;
    private $fp;

	/*** End data members ***/



	/*** magic methods ***/

	/**
	 * __construct
	 *
	 * Description of the constructor.
	 *
	 * @param	$log_file_name			The log file we want to use.
	 * @access	public
	 */
	public function __construct($log_file_name='logfile.log')
	{
		try
		{
			# Set the script language to the data member.
			$this->setLogFile($log_file_name);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- __construct

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setLogFile
	 *
	 * Sets the data member $log_file.
	 *
	 * @param	$log_file
	 * @access	private
	 */
	private function setLogFile($log_file)
	{
		# Check if the directory set to the LOGS constant exists.
		if(file_exists(LOGS))
		{
			# Set the data member.
			$this->log_file=LOGS.$log_file;
		}
		else
		{
			# Set the data member explicitly to NULL.
			$this->log_file=NULL;
		}
	} #==== End -- setLogFile

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getLogFile
	 *
	 * Returns the data member $log_file.
	 *
	 * @access	private
	 */
	private function getLogFile()
	{
		return $this->log_file;
	} #==== End -- getLogFile

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * writeLogFile
	 *
	 * Write message to the log file.
	 *
	 * @param	$message
	 * @access	public
	 */
	public function writeLogFile($message)
	{
		# If file pointer doesn't exist, then open log file.
		if(!is_resource($this->fp))
		{
			$this->openLogFile();
		}
		# Create time variable.
		#	Example: [Fri Jan 30 14:33:59 2014]
		$time='['.date('D M d H:i:s Y').']';
		# Write current time, script name and message to the log file.
		fwrite($this->fp, $time.' '.$message.PHP_EOL);
	} #==== End -- writeLogFile

	/**
	 * closeLogFile
	 *
	 * Close log file (it's always a good idea to close a file when you're done with it).
	 *
	 * @access	public
	 */
	public function closeLogFile()
	{
		fclose($this->fp);
	} #==== End -- closeLogFile

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * openLogFile
	 *
	 * Open log file.
	 *
	 * @access	private
	 */
	private function openLogFile()
	{
		# Set the log file to a variable.
		$log_file=$this->getLogFile();
		# Open log file for writing only and place file pointer ($fp) at the end of the file.
		#	(if the file does not exist, try to create it).
		$this->fp=fopen($log_file, 'a') or exit("Can't open $log_file!");
	} #==== End -- openLogFile

	/*** End private methods ***/

} #=== End Logger class.