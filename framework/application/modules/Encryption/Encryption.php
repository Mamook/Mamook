<?php /* framework/application/modules/Encryption/Encryption.php */

/**
 * Encryption
 *
 * The Encryption Class is used to securly encrypt.
 *
 */
class Encryption
{
	/*** data members ***/

	private $encryption_key;

	/*** End data members ***/



	/*** magic methods ***/

	/**
	 * Constructor
	 *
	 * Sets the data member $encryption_key.
	 *
	 * @param	$encryption_key			The string used as the key for encrypting.
	 * @access	magic
	 */
	public function __construct($encryption_key)
	{
		try
		{
			$this->setEncryptionKey($encryption_key);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setEncryptionKey
	 *
	 * Sets the data member $table_old. Thows an error on failure.
	 *
	 * @param	$name					The name of the old table.
	 * @access	protected
	 */
	protected function setEncryptionKey($encryption_key)
	{
		if(!empty($encryption_key))
		{
			$this->encryption_key=$encryption_key;
		}
		else
		{
			throw new Exception('An encryption key needs to be passed!', E_RECOVERABLE_ERROR);
		}
	} #==== End -- setEncryptionKey

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	* getEncryptionKey
	*
	* Returns the data member $table_old. Thows an error on failure.
	*
	* @access	public
	*/
	public function getEncryptionKey()
	{
		if(isset($this->encryption_key) && !empty($this->encryption_key))
		{
			return $this->encryption_key;
		}
		else
		{
			throw new Exception('The encryption key wasn\'t set!', E_RECOVERABLE_ERROR);
		}
	} #==== End -- getEncryptionKey

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * enCodeIt
	 *
	 * Securely encrypts a string.
	 *
	 * @param	$encryptable			The string to be encrypted.
	 * @access	public
	 */
	public function enCodeIt($encryptable=NULL)
	{
		if($encryptable!==NULL)
		{
			# Make an encryption resource using a cipher.
			$td=mcrypt_module_open('rijndael-256', '', 'ecb', '');

			# Create and encryption vector based on the $td size and random.
			$iv=mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

			# Get the key size and create the key.
			$ks=mcrypt_enc_get_key_size($td);
			$key=substr(sha1($this->getEncryptionKey()), 0, $ks);

			# Initialize the module using the resource, the encryption key, and the string vector.
			mcrypt_generic_init($td, $key, $iv);

			# Encrypt the data using the $td resource.
			$encrypted_data=mcrypt_generic($td, $encryptable);

			# Encode in base64 for DB storage.
			$encoded=base64_encode($encrypted_data);

			# Make sure the encryption modules get unloaded.
			if(!mcrypt_generic_deinit($td) || !mcrypt_module_close($td))
			{
				throw new Exception('The encryption modules didn\'t unload!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			throw new Exception('You must pass a string to encode!', E_RECOVERABLE_ERROR);
		}
		return $encoded;
	} #==== End -- enCodeIt

	/**
	 * deCodeIt
	 *
	 * De-encrypts a string securely encrypted by the enCodeIt() method.
	 *
	 * @param	$encryptable			The string to be encrypted.
	 * @access	public
	 */
	public function deCodeIt($encrypted=NULL)
	{
		if($encrypted!==NULL)
		{
			# The reverse of enCodeIt. See that function for details.
			$encrypted=(string)base64_decode(trim($encrypted));

			# Make an encryption resource using a cipher.
			$td=mcrypt_module_open('rijndael-256', '', 'ecb', '');

			# Create and encryption vector based on the $td size and random.
			$iv=mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

			# Get the key size and create the key.
			$ks=mcrypt_enc_get_key_size($td);
			$key=substr(sha1($this->getEncryptionKey()), 0, $ks);

			# Initialize the module using the resource, the key, and the string vector.
			mcrypt_generic_init($td, $key, $iv);

			# Dencode the encrypted data.
			$decoded=(string)trim(mdecrypt_generic($td,$encrypted));

			# Make sure the encryption modules get unloaded.
			if(!mcrypt_generic_deinit($td) || !mcrypt_module_close($td))
			{
				throw new Exception('The encryption modules didn\'t unload!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			throw new Exception('You must pass a string to decode!', E_RECOVERABLE_ERROR);
		}
		return $decoded;
	}  #==== End -- deCodeIt

	/*** End public methods ***/

} # End Encryption class.