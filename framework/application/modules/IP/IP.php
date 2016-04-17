<?php /* framework/application/modules/IP/IP.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

/**
 * IP
 *
 * The IP class is used to for miscellaneous IP functions.
 *
 */
class IP
{
	/*** data members ***/

	protected $ip=NULL;
	private static $ip_obj;
	protected $ip_version=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setIP
	 *
	 * Sets the data member $ip.
	 *
	 * @param	$ip
	 * @access	protected
	 */
	protected function setIP($ip)
	{
		# Check if the passed value is empty.
		if(!empty($ip))
		{
			# Clean it up.
			$ip=$ip;
		}
		else
		{
			# Explicitly set it to NULL.
			$ip=NULL;
		}
		# Set the data member.
		$this->ip=$ip;
	} #==== End -- setIP

	/**
	 * setIPVersion
	 *
	 * Sets the data member $ip_version.
	 *
	 * @param	$ip_version
	 * @access	protected
	 */
	protected function setIPVersion($ip_version)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed value is NULL.
		if($ip_version!==NULL)
		{
			# Clean it up.
			$ip_version=trim($ip_version);
			# Check if the passed value is an integer.
			if($validator->isInt($ip_version)===TRUE)
			{
				# Explicitly make it an integer.
				$ip_version=(int)$ip_version;
			}
			else
			{
				throw new Exception('The passed IP version was not a number!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set it to NULL.
			$ip_version=NULL;
		}
		# Set the data member.
		$this->ip_version=$ip_version;
	} #==== End -- setIPVersion

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getIP
	 *
	 * Returns the data member $ip.
	 *
	 * @access	public
	 */
	public function getIP()
	{
		return $this->ip;
	} #==== End -- getIP

	/**
	 * getIPVersion
	 *
	 * Returns the data member $ip_version.
	 *
	 * @access	public
	 */
	public function getIPVersion()
	{
		return $this->ip_version;
	} #==== End -- getIPVersion

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * createQueryParam
	 *
	 * Determines what MySQL function should be used based on MySQL version.
	 *
	 * @param	$ip						The visitor's IP address or ip field in database table.
	 * @access	public
	 * @return	string
	 */
	/*
	public function createQueryParam($ip, $select_query=FALSE)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Get the database client version.
		$server_version=$db->server_version;

		# If the client version is 5.6.3+ use INET6_ATON.
		if($server_version>=50603)
		{
			# If this is not for a select query.
			if($select_query===FALSE)
			{
				return "INET6_ATON('".$ip."')";
			}
			# This is for a select query.
			return 'INET6_NTOA(`'.$ip.'`)';
		}
		# Else we assume PHP has IPv6 support and use PHP's inet_pton() to convert the IP to a binary.
		else
		{
			# If this is not for a select query.
			if($select_query===FALSE)
			{
				# If IPv4 then use MySQL function.
				if($this->getIPVersion()==4)
				{
					return "INET_ATON('".$ip."')";
				}
				else
				{
					# Supports IPv4 & IPv6 (if PHP has IPv6 supprot enabled).
					return $db->quote($db->escape(inet_pton($ip)));
				}
			}
			# NOTE: How to handle select queries for IPv6 if MySQL version is less then 5.6.3?
			# Can a MySQL bitwise operation be used here?
			# IPv4 Only.
			//return 'INET_NTOA(`'.$ip.'`)';
			# For IPv6 I could use PHP's inet_ntop() but we can't return it here.
		}
	} #=== End -- createQueryParam
	*/

	/**
	 * createInsertQueryParam
	 *
	 * Determines what MySQL function should be used based on MySQL version.
	 *
	 * @param	$ip						The visitor's IP address.
	 * @access	public
	 * @return	string
	 */
	public function createInsertQueryParam($ip)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Get the database client version.
		$server_version=$db->server_version;

		# If the client version is 5.6.3+ use INET6_ATON.
		if($server_version>=50603)
		{
			return "INET6_ATON('".$ip."')";
		}
		# Else we assume PHP has IPv6 support and use PHP's inet_pton() to convert the IP to a binary.
		else
		{
			# If IPv4 then use MySQL function.
			if($this->getIPVersion()==4)
			{
				return "INET_ATON('".$ip."')";
			}
			else
			{
				# Supports IPv4 & IPv6 (if PHP has IPv6 supprot enabled).
				return $db->quote($db->escape(inet_pton($ip)));
			}
		}
	} #=== End -- createInsertQueryParam

	/**
	 * createSelectQueryParam
	 *
	 * Determines what MySQL function should be used based on MySQL version.
	 *
	 * @param	$ip_field					The IP field in database table.
	 * @access	public
	 * @return	string
	 */
	public function createSelectQueryParam($ip_field)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Get the database client version.
		$server_version=$db->server_version;

		# If the client version is 5.6.3+ use INET6_ATON.
		if($server_version>=50603)
		{
			# This is for a select query.
			return 'INET6_NTOA(`'.$ip_field.'`)';
		}
		# Else we assume PHP has IPv6 support and use PHP's inet_ntop() to convert the binary IP to human readable string.
		else
		{
			# Return FALSE because INET6_NTOA() is not supported and we need to use PHP's inet_ntop().
			return FALSE;
		}
	} #=== End -- createSelectQueryParam

	/**
	 * findIP
	 *
	 * Returns the IP of the visitor.
	 * Throws an error if the IP address is not valid.
	 *
	 * @param	bool $for_insert_query	Convert IP addresss to binary for database.
	 * @access	public
	 * @return	string
	 */
	public function findIP($for_insert_query=TRUE)
	{
		# Get the visitor's IP addreess.
		#	Use $_SERVER over getenv() since it's more server compatible.
		#	If $_SERVER['REMOTE_ADDR'] is empty, use getenv().
		$ip=$ip=(!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : getenv('REMOTE_ADDR');
		# Check if the IP Address is valid.
		#	Throws an error if the IP is not valid.
		if($this->ipValid($ip)===TRUE)
		{
			# If we need to create the IP to a binary.
			if($for_insert_query===TRUE)
			{
				# Convert the visitor's IP to a binary.
				//$ip=$this->createQueryParam($ip);
				$ip=$this->createInsertQueryParam($ip);
			}
			# Set the IP address to the data member.
			$this->setIP($ip);
		}
		# Return the data member.
		return $this->getIP();
	} #==== End -- findIP

	/**
	 * getInstance
	 *
	 * Gets the singleton instance of this class.
	 *
	 * @access	public
	 */
	public static function getInstance()
	{
		if(!self::$ip_obj)
		{
			self::$ip_obj=new IP();
		}
		return self::$ip_obj;
	} #==== End -- getInstance

	/**
	 * ipValid
	 *
	 * Will determine if a given IP address is valid or not.
	 * Will set the version of the IP address to the $ip_version data member.
	 * Throws an error if the IP is not valid.
	 *
	 * @access	public
	 * @param	$ip						The IP address to validate.
	 * @return	boolean
	 */
	public function ipValid($ip)
	{
		# Detect if it is a valid IPv4 Address
		if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
		{
			# This is an IPv4 address.
			$version=4;
		}
		# Detect if it is a valid IPv6 Address
		elseif(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
		{
			# This is an IPv6 address.
			$version=6;
		}
		if(isset($version))
		{
			$this->setIPVersion($version);
			return TRUE;
		}
		else
		{
			throw new Exception('The IP address was not valid!', E_RECOVERABLE_ERROR);
		}
		return FALSE;
	} #==== End -- ipValid

	/*** End public methods ***/

} #=== End IP class.