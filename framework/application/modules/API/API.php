<?php /* framework/application/modules/API/API.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

/**
 * API
 *
 * Interacts with the application.
 *
 */
class API
{
	/*** data members ***/

	private $server_api_key=API_KEY;

	/*** End data members ***/



	/*** accessor methods ***/

	/**
	 * getServerAPIKey
	 *
	 * Returns the data member $server_api_key.
	 *
	 * @access	private
	 */
	private function getServerAPIKey()
	{
		return $this->server_api_key;
	} #==== End -- getServerAPIKey

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * validateAPIKey
	 *
	 * Validates the clients API key against the server's API key.
	 *
	 * @param	$client_api_key			Client's API key
	 * @access	public
	 */
    public function validateAPIKey($client_api_key)
    {
        # If the server's API key does not match the clients API key.
        if($this->getServerAPIKey()!=$client_api_key)
        {
        	# Create an error array to return to the client.
            $error_array=array('error'=>1, 'message'=>'Invalid Key');
            # Return the error in JSON encoded format.
            echo json_encode($error_array);
            # Exit the application.
            exit;
        }
    } #==== End -- validateAPIKey

    /*** End public methods ***/

} # End API class.