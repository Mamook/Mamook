<?php /* Requires PHP5+ */

require_once Utility::locateFile(MODULES.'PayPal'.DS.'PayPal.php');

/**
* CustomPayPal
*
* The CustomPayPal Class is used to access and manipulate PayPal.
* Find out more about available variables at: https://cms.paypal.com/us/cgi-bin/?&cmd=_render-content&content_ID=developer/e_howto_html_Appx_websitestandard_htmlvariables
*
*/
class CustomPayPal extends PayPal
{
	/*** data members ***/

	/*** End data members ***/



	/*** magic methods ***/

	/*** End magic methods ***/



	/*** mutator methods ***/

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/*** End accessor methods ***/



	/*** public methods ***/

	/*** End public methods ***/



	/*** protected methods ***/

	/*** End protected methods ***/



	/*** private methods ***/

	/**
	* updateUserInfo
	*
	* Processes an order submitted via Shopping Cart.
	*
	* @access	private
	*/
	private function updateUserInfo($user_field)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# For debugging
		$this->script=__FILE__;

		$field=key($user_field);
		$update_value=$user_field[$field];
		$id=$this->id;
		$paymentgross=$this->payment_gross;
		$donation=FALSE;

		# Get the current status for the User.
		try
		{
			$status=$db->get_row('SELECT `gml_subscription`, `fwj_subscription`,`product` FROM `'.DBPREFIX.'users` WHERE `ID` = '.$db->quote($id).' LIMIT 1');
		}
		catch(ezDB_Error $ez)
		{
			# The product field was not retrieved!
			$this->error.="Couldn't retrieve the `gml_subscription`, `fwj_subscription`, or `product` fields for the user with ID ".$id." from the Database! \n<br />Error occured: ".$ez->message ."\n<br />Code: ".$ez->code."\n<br />Last query: ".$ez->last_query;
			# What is the subject of the error email send to the admin?
			$this->error_subject="Check IPN_log".$this->log_subj.", there was an error.";
			$this->makeLog();
			throw new Exception($this->error_subject.' -> '.$this->error, E_RECOVERABLE_ERROR);
			# Explicitly stop the script.
			die();
		}

		if(($field=='gml_subscription') || ($field=='donation') ||($field=='fwj_subscription'))
		{
			if($field=='donation')
			{
				if($paymentgross >= '15.00')
				{
					$field='gml_subscription';
					$update_value='year';
					$donation=TRUE;
				}
			}
			if($field!='donation')
			{
				$value=$this->calculateNewDate($update_value, ((isset($status->$field)) ? $status->$field : null));
			}
		}
		elseif($field='product' || $donation===TRUE)
		{
			# Retrieve the user's previous purchases and add the new one to them.
			$prev_purchases='';
			if($status!==NULL)
			{
				$prev_purchases=$status->product;
			}
			if($donation===TRUE)
			{
				$update_value='Donation';
			}
			$value=$prev_purchases.$update_value.'-';
		}
		# Update the user's account to reflect their purchase.
		try
		{
			if($field!='donation')
			{
				$db->query('UPDATE `'.DBPREFIX.'users` SET `'.$field.'` = '. $db->quote($db->escape($value)).' WHERE `ID` = '.$db->quote($id).' LIMIT 1');
				# Temporary special - GML Subscription with purchase of FWJ Subscription.
				if($field=='fwj_subscription')
				{
					$gml_value=$this->calculateNewDate($update_value, $status->gml_subscription);
					try
					{
						$db->query('UPDATE `'.DBPREFIX.'users` SET `gml_subscription` = '. $db->quote($db->escape($gml_value)).' WHERE `ID` = '.$db->quote($id).' LIMIT 1');
					}
					catch(ezDB_Error $ez)
					{
						# The user's account was not updated! This error will not stop the script.
						$this->error.="Couldn't update the \"gml_subscription\" field after updating the \"".$field."\" field for the user with ID ".$id." in the Database! \n<br />Error occured: ".$ez->message.', code: '.$ez->code.'<br />Last query: '.$ez->last_query;
						# What is the subject of the error email send to the admin?
						$this->error_subject="Check IPN_log".$this->log_subj.", there was an error.";
						$this->makeLog();
						throw new Exception($this->error_subject.' -> '.$this->error, E_RECOVERABLE_ERROR);
						# Explicitly stop the script.
						die();
					}
				}
			}
		}
		catch(ezDB_Error $ez)
		{
			# The user's account was not updated! This error will not stop the script.
			$this->error.="Couldn't update the \"".$field."\" field for the user with ID ".$id." in the Database! \n<br />Error occured: ".$ez->message.', code: '.$ez->code.'<br />Last query: '.$ez->last_query;
			# What is the subject of the error email send to the admin?
			$this->error_subject="Check IPN_log".$this->log_subj.", there was an error.";
			$this->makeLog();
			throw new Exception($this->error_subject.' -> '.$this->error, E_RECOVERABLE_ERROR);
			# Explicitly stop the script.
			die();
		}
	} #==== End -- updateUserInfo

	/*** End private methods ***/

} # End CustomPayPal class.