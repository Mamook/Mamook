<?php /* framework/application/modules/PayPal/PayPal.php */

/**
 * PayPal
 *
 * The PayPal Class is used to access and manipulate PayPal.
 * Find out more about available variables at: https://cms.paypal.com/us/cgi-bin/?&cmd=_render-content&content_ID=developer/e_howto_html_Appx_websitestandard_htmlvariables
 *
 */
class PayPal
{
	/*** data members ***/

	protected $action;
	protected $cmd;
	protected $hosted_button_id=NULL;
	protected $cancel_return;
	protected $custom;
	protected $return;
	protected $shopping_url;
	protected $options;
	protected $submit;

	protected $req='cmd=_notify-validate';
	# For testing
	//protected $test='sandbox.';
	protected $test='';
	protected $paypal_url;

	# error variables
	protected $error='';
	protected $log;
	protected $log_subj='';
	protected $error_subject='';
	protected $email_to;
	protected $email_message;
	protected $datetime;

	# item information variables
	protected $item_name;
	protected $item_number=NULL;
	protected $quantity;
	protected $option_name1;
	protected $option_selection1;
	protected $option_name2;
	protected $option_selection2;

	# shopping cart item information variables
	protected $num_cart_items;
	protected $shopping_cart;

	# transaction specific variables
	protected $payment_date;
	protected $payment_type;
	protected $payment_status;
	protected $tax;
	protected $parent_txn_id;
	protected $txn_id;
	protected $txn_type;
	protected $payment_currency;
	protected $settle_currency;
	protected $shipping;
	protected $payment_gross;
	protected $payment_gross_USD;
	protected $payment_fee;
	protected $payment_fee_USD;
	protected $exchange_rate;
	protected $settle_amount;
	protected $invoice;
	protected $memo;
	protected $pending_reason;
	protected $reason_code;
	protected $auth_id;
	protected $auth_exp;
	protected $auth_status;

	# custom variables
	protected $id;

	# buyer specific variables
	protected $fname;
	protected $lname;
	protected $payer_email;
	protected $address;
	protected $city;
	protected $state;
	protected $country;
	protected $country_code;
	protected $zipcode;
	protected $address_status;
	protected $residence;
	protected $phone;
	protected $payer_status;
	protected $payer_business_name;
	protected $payer_id;

	# seller specific variables
	protected $receiver_email;
	protected $business;
	protected $receiver_id;

	protected $curl_result;
	protected $curl_err;

	protected $script;

	/*** End data members ***/



	/*** magic methods ***/

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setAction
	 *
	 * Sets the Data member $action.
	 *
	 * @param	$action					The url the PayPal button sends POST data to.
	 * @access	public
	*/
	public function setAction($action)
	{
		$action=trim($action);
		$this->action=$action;
	} #==== End -- setAction

	/**
	 * setCmd
	 *
	 * Sets the Data member $cmd.
	 *
	 * @param	$cmd					The type of button - _xclick, _donations, _xclick-subscriptions, _oe-gift-certificate, _cart, _s-xclick
	 * @access	public
	 */
	public function setCmd($cmd)
	{
		$cmd=trim($cmd);
		$values=array('_xclick', '_donations', '_xclick-subscriptions', '_oe-gift-certificate', '_cart', '_s-xclick');
		if(in_array($cmd, $values))
		{
			$this->cmd=$cmd;
		}
		else
		{
			throw new Exception('An acceptable value was not passed for "cmd".', E_RECOVERABLE_ERROR);
		}
	} #==== End -- setCmd

	/**
	 * setHostedButtonID
	 *
	 * Sets the Data member $hosted_button_id.
	 *
	 * @param	$hosted_button_id		The ID of the button hosted on PayPal.
	 * @access	public
	 */
	public function setHostedButtonID($hosted_button_id)
	{
		$hosted_button_id=trim($hosted_button_id);
		$this->hosted_button_id=$hosted_button_id;
	} #==== End -- setHostedButtonID

	/**
	 * setCancelReturn
	 *
	 * Sets the Data member $cancel_return.
	 *
	 * @param	$cancel_return			The url to return the user to if they cancel the order process.
	 * @access	public
	 */
	public function setCancelReturn($cancel_return)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		$cancel_return=trim($cancel_return);
		$valid=$validator->validURL($cancel_return);
		if($valid===TRUE)
		{
			$this->cancel_return=$cancel_return;
		}
		else
		{
			throw new Exception('A valid URL wasn\'t passed to cancel_return.', E_RECOVERABLE_ERROR);
		}
	} #==== End -- setCancelReturn

	/**
	 * setCustom
	 *
	 * Sets the Data member $custom.
	 *
	 * @param	$custom					A custom value to be passed through PayPal.
	 * @access	public
	 */
	public function setCustom($custom)
	{
		$custom=trim($custom);
		$this->custom=$custom;
	} #==== End -- setCustom

	/**
	 * setReturn
	 *
	 * Sets the Data member $return.
	 *
	 * @param	$return					The url to return the user to once the PayPal transaction is completed.
	 * @access	public
	 */
	public function setReturn($return)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		$return=trim($return);
		$valid=$validator->validURL($return);
		if($valid===TRUE)
		{
			$this->return=$return;
		}
		else
		{
			throw new Exception('A valid URL wasn\'t passed to return.', E_RECOVERABLE_ERROR);
		}
	} #==== End -- setReturn

	/**
	 * setShoppingURL
	 *
	 * Sets the Data member $shopping_url.
	 *
	 * @param	$shopping_url			The url to send the user to if there is a shopping cart and they click "continue shopping".
	 * @access	public
	 */
	public function setShoppingURL($shopping_url)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		$shopping_url=trim($shopping_url);
		$valid=$validator->validURL($shopping_url);
		if($valid===TRUE)
		{
			$this->shopping_url=$shopping_url;
		}
		else
		{
			throw new Exception('A valid URL wasn\'t passed to shopping_url.', E_RECOVERABLE_ERROR);
		}
	} #==== End -- setShoppingURL

	/**
	 * setOptions
	 *
	 * Sets the Data member $options.
	 *
	 * @param	$options				A multidimensional array:
	 *										The $array[0][0] being label for the select,
	 *										the $array[0][1] being an array of values,
	 *										and $array[0][2] being the optional price values.
	 *										There may be a maximum of 7 option field names {6 with Subscribe buttons})
	 * @access	public
	 */
	public function setOptions($options)
	{
		$options=(array)$options;
		$this->options=$options;
	} #==== End -- setOptions

	/**
	 * setSubmit
	 *
	 * Sets the Data member $submit.
	 *
	 * @param	$type					The type of submit button, ie image or subit. Default is submit.
	 * @param	$name					The name of the submit button. Default is submit.
	 * @param	$value					The value of the submit button. Default is "Buy Now!".
	 * @param	$image					If the type of submit button is image, the URL to the source. Default is NULL.
	 * @param	$image					A class for the submit button. Default is NULL.
	 * @access	public
	 */
	public function setSubmit($type='submit', $name='submit', $value='Buy Now!', $image=NULL, $class=NULL)
	{
		$submit['type']=$type;
		$submit['name']=$name;
		$submit['value']=$value;
		$submit['image']=$image;
		$submit['class']=$class;
		$this->submit=$submit;
	} #==== End -- setSubmit

	/**
	 * setPayPalURL
	 *
	 * Sets the URL to PayPal services.
	 *
	 * @param	$test					Default is empty.
	 * @access	public
	 */
	public function setPayPalURL($test='')
	{
		$test=trim($test);
		$paypal_url='https://www.'.$test.'paypal.com/cgi-bin/webscr';
		$this->paypal_url=$paypal_url;
	} #==== End -- setPayPalURL

	/**
	 * setEmailMessage
	 *
	 * Sets the Data member $email_message.
	 *
	 * @param	$email_message			The message to send via email in case of an error.
	 * @access	public
	 */
	public function setEmailMessage($email_message)
	{
		$email_message=trim($email_message);
		$email_message=wordwrap($email_message, 70, "\n", TRUE);
		$this->email_message=$email_message;
	} #==== End -- setEmailMessage

	/**
	 * setItemNumber
	 *
	 * Sets the Data member $item_number.
	 *
	 * @param	$item_number			The ID of the item in the Database.
	 * @access	public
	 */
	public function setItemNumber($item_number)
	{
		$email_message=trim($item_number);
		$this->item_number=$item_number;
	} #==== End -- setItemNumber

	/**
	 * setShoppingCart
	 *
	 * Sets the Data member $shopping_cart for all the Cart Item info.
	 *
	 * @access	public
	 */
	public function setShoppingCart()
	{
		$shopping_cart='';

		# Was this a Shopping Cart transaction?
		if($this->txn_type == "cart")
		{
			$num_cart_items=$this->num_cart_items;
			if(($_SERVER['REQUEST_METHOD']=='POST') && !empty($_POST))
			{
				$shopping_cart.="Shopping cart information:<br />\n";
				# Loop through the Shopping Cart Items and assign the info to variables.
				for($i=1; $i <= $num_cart_items; $i++)
				{
					# $i will increase each time till it equals the number of items in the cart.
					$cartitemname="item_name".$i;
					$cartitemname=$_POST[$cartitemname];
					$cartitemnumber="item_number".$i;
					$cartitemnumber=$_POST[$cartitemnumber];
					$cartitem_quantity="quantity".$i;
					$cartitem_quantity=$_POST[$cartitem_quantity];
					$carton0="option_name1_".$i;
					$carton0=$_POST[$carton0];
					$cartos0="option_selection1_".$i;
					$cartos0=$_POST[$cartos0];
					$carton1="option_name2_".$i;
					$carton1=$_POST[$carton1];
					$cartos1="option_selection2_".$i;
					$cartos1=$_POST[$cartos1];
					# Append our shopping cart item variable with the first iteration of info.
					$shopping_cart.="item name".$i.": ".urldecode($cartitemname)."<br />\n".
													"item number".$i.": ".$cartitemnumber."<br />\n".
													"item quantity".$i.": ".$cartitem_quantity."<br />\n".
													"option name1_".$i.": ".urldecode($carton0)."<br />\n".
													"option selection1_".$i.": ".$cartos0."<br />\n".
													"option name2_".$i.": ".urldecode($carton1)."<br />\n".
													"option selection2_".$i.": ".$cartos1."<br />\n";
				}
				# After we have looped through all the items, append our variable with a line break.
				$shopping_cart.="<br />\n";
			}
		}
		$this->shopping_cart=$shopping_cart;
	} #==== End -- setShoppingCart

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAction
	 *
	 * Returns the Data member $action.
	 *
	 * @access	protected
	 */
	protected function getAction()
	{
		return $this->action;
	} #==== End -- getAction

	/**
	 * getCmd
	 *
	 * Returns the Data member $cmd.
	 *
	 * @access	protected
	 */
	protected function getCmd()
	{
		return $this->cmd;
	} #==== End -- getCmd

	/**
	 * getHostedButtonID
	 *
	 * Returns the Data member $hosted_button_id.
	 *
	 * @access	protected
	 */
	protected function getHostedButtonID()
	{
		return $this->hosted_button_id;
	} #==== End -- getHostedButtonID

	/**
	 * getCancelReturn
	 *
	 * Returns the Data member $cancel_return.
	 *
	 * @access	protected
	 */
	protected function getCancelReturn()
	{
		return $this->cancel_return;
	} #==== End -- getCancelReturn

	/**
	 * getCustom
	 *
	 * Returns the Data member $custom.
	 *
	 * @access	protected
	 */
	protected function getCustom()
	{
		return $this->custom;
	} #==== End -- getCustom

	/**
	 * getReturn
	 *
	 * Returns the Data member $return.
	 *
	 * @access	protected
	 */
	protected function getReturn()
	{
		return $this->return;
	} #==== End -- getReturn

	/**
	 * getShoppingURL
	 *
	 * Returns the Data member $shopping_url.
	 *
	 * @access	protected
	 */
	protected function getShoppingURL()
	{
		return $this->shopping_url;
	} #==== End -- getShoppingURL

	/**
	 * getOptions
	 *
	 * Returns the Data member $options.
	 *
	 * @access	protected
	 */
	protected function getOptions()
	{
		return $this->options;
	} #==== End -- getOptions

	/**
	 * getSubmit
	 *
	 * Returns the Data member $submit.
	 *
	 * @access	protected
	 */
	protected function getSubmit()
	{
		return $this->submit;
	} #==== End -- getSubmit

	/**
	 * getPayPalURL
	 *
	 * Returns the Data member $paypal_url.
	 *
	 * @access	protected
	 */
	protected function getPayPalURL()
	{
		return $this->paypal_url;
	} #==== End -- getPayPalURL

	/**
	 * getEmailMessage
	 *
	 * Returns the Data member $email_message.
	 *
	 * @access	protected
	 */
	protected function getEmailMessage()
	{
		return $this->email_message;
	} #==== End -- getEmailMessage

	/**
	 * getItemNumber
	 *
	 * Returns the Data member $item_number.
	 *
	 * @access	protected
	 */
	protected function getItemNumber()
	{
		return $this->item_number;
	} #==== End -- getItemNumber

	/**
	 * getShoppingCart
	 *
	 * Returns the Data member $shopping_cart.
	 *
	 * @access	protected
	 */
	protected function getShoppingCart()
	{
		return $this->shopping_cart;
	} #==== End -- getShoppingCart

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * getPayPalPOST
	 *
	 * Creates and displays a PayPal button for use on the page.
	 *
	 * @access	public
	 */
	public function getPayPalPOST()
	{
		# Is it for a shopping cart that includes options?
		if (isset($_POST['shopping_url']) && isset($_POST['cmd']) && isset($_POST['hosted_button_id']) && isset($_POST['return']) && isset($_POST['cancel_return']) && isset($_POST['os0']))
		{
			# hang on to the passed values
			$cancel_return = $_POST['cancel_return'];
			$return = $_POST['return'];
			$shopping_url = $_POST['shopping_url'];
			$cmd = $_POST['cmd'];
			$hosted_button_id = $_POST['hosted_button_id'];
			$os0 = $_POST['os0'];
			# since the login form has been submitted, let's add the user's ID ( get_buyer_id() ) to our $post_login
			# define POST_LOGIN (before we call setting.php)
			$post_login = PAYPAL_URL.'?cmd='.$cmd.'&hosted_button_id='.$hosted_button_id.'&cancel_return='.$cancel_return.'&return='.$return.'&shopping_url='.$shopping_url.'&os0='.$os0;
			return $post_login;
		}
		# Is it for a single item that includes options?
		elseif (isset($_POST['cmd']) && isset($_POST['hosted_button_id']) && isset($_POST['return']) && isset($_POST['cancel_return']) && isset($_POST['os0']))
		{
			# hang on to the passed values
			$cancel_return = $_POST['cancel_return'];
			$return = $_POST['return'];
			$cmd = $_POST['cmd'];
			$hosted_button_id = $_POST['hosted_button_id'];
			$os0 = $_POST['os0'];
			# since the login form has been submitted, let's add the user's ID ( get_buyer_id() ) to our $post_login
			# define POST_LOGIN (before we call setting.php)
			$post_login = PAYPAL_URL.'?cmd='.$cmd.'&hosted_button_id='.$hosted_button_id.'&cancel_return='.$cancel_return.'&return='.$return.'&os0='.$os0;
			return $post_login;
		}
		# Is it for a shopping cart that doesn't includes options?
		elseif (isset($_POST['shopping_url']) && isset($_POST['cmd']) && isset($_POST['hosted_button_id']) && isset($_POST['return']) && isset($_POST['cancel_return']))
		{
			# hang on to the passed values
			$cancel_return = $_POST['cancel_return'];
			$return = $_POST['return'];
			$shopping_url = $_POST['shopping_url'];
			$cmd = $_POST['cmd'];
			$hosted_button_id = $_POST['hosted_button_id'];
			# since the login form has been submitted, let's add the user's ID ( get_buyer_id() ) to our $post_login
			# define POST_LOGIN (before we call setting.php)
			$post_login = PAYPAL_URL.'?cmd='.$cmd.'&hosted_button_id='.$hosted_button_id.'&cancel_return='.$cancel_return.'&return='.$return.'&shopping_url='.$shopping_url;
			return $post_login;
		}
		# Is it for a single item that doesn't includes options?
		elseif (isset($_POST['cmd']) && isset($_POST['hosted_button_id']) && isset($_POST['return']) && isset($_POST['cancel_return']))
		{
			# hang on to the passed values
			$cancel_return = $_POST['cancel_return'];
			$return = $_POST['return'];
			$cmd = $_POST['cmd'];
			$hosted_button_id = $_POST['hosted_button_id'];
			# since the login form has been submitted, let's add the user's ID ( get_buyer_id() ) to our $post_login
			# define POST_LOGIN (before we call setting.php)
			$post_login = PAYPAL_URL.'?cmd='.$cmd.'&hosted_button_id='.$hosted_button_id.'&cancel_return='.$cancel_return.'&return='.$return;
			return $post_login;
		}
		else { return FALSE; }
	} #==== End -- getPayPalPOST

	/**
	 * makePayPalButton
	 *
	 * Catches the POST Data from a PayPal button and creates a URL for a PayPal page.
	 *
	 * @param	$button_name			Optional.
	 * @param	$method					Optional.
	 * @param	$target					Optional.
	 * @param	$options				Optional.
	 * @param	$shopping_cart			Optional.
	 * @param	$test					Optional.
	 * @param	$class					Optional.
	 * @access	public
	 * @return	string
	 */
	public function makePayPalButton($button_name='paypal_button', $method='POST', $target='_top', $options=FALSE, $shopping_cart=TRUE, $test=FALSE, $class=NULL)
	{
		# Get the FormGenerator Class.
		require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
		# Instantiate a FormGenerator object.
		$button=new FormGenerator($button_name, PAYPAL_URL, $method, $target, NULL, $class);

		if($test===TRUE)
		{
			$button->addElement('hidden', array('name'=>'test', 'value'=>'y'));
		}
		$button->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
		$button->addElement('hidden', array('name'=>'cmd', 'value'=>$this->getCmd()));
		if($this->getHostedButtonID()!==NULL)
		{
			$button->addElement('hidden', array('name'=>'hosted_button_id', 'value'=>$this->getHostedButtonID()));
		}
		if($this->getItemNumber()!==NULL)
		{
			$button->addElement('hidden', array('name'=>'item_number', 'value'=>$this->getItemNumber()));
		}
		$button->addElement('hidden', array('name'=>'cancel_return', 'value'=>$this->getCancelReturn()));
		$custom=$this->getCustom();
		if(!empty($custom))
		{
			$button->addElement('hidden', array('name'=>'custom', 'value'=>$this->getCustom()));
		}
		$button->addElement('hidden', array('name'=>'return', 'value'=>$this->getReturn()));
		if($options===TRUE)
		{
			$options=$this->getOptions();
			$num_options=count($options);
			for($i=0;$num_options>$i;$i++)
			{
				$num_values=count($options[$i][1]);
				for($v=0;$num_values>$v;$v++)
				{
					$option_values['os'.$v]=$options[$i][1][$v];
					if(isset($options[$i][2]))
					{
						$option_values['os'.$v]=$options[$i][1][$v].' - '.$options[$i][2][$v];
						$button->addElement('hidden', array('name'=>'option_select'.$i, 'value'=>$options[$i][1][$v]));
						$button->addElement('hidden', array('name'=>'option_amount'.$i, 'value'=>$options[$i][2][$v]));
					}
				}
				$button->addFormPart('<label for="'.'on'.$i.'">'.$options[$i][0].'</label>');
				$button->addElement('select',array('name'=>'on'.$i),$option_values);
			}
		}
		if($shopping_cart!==FALSE)
		{
			$button->addElement('hidden',array('name'=>'shopping_url','value'=>$this->getShoppingURL()));
		}
		$submit=$this->getSubmit();
		$button->addElement($submit['type'], array('name'=>$submit['name'], 'value'=>$submit['value']), '', $submit['image'], $submit['class']);
		return $button->display();
	} #==== End -- makePayPalButton

	/**
	 * processPayPal
	 *
	 * Processes a PayPal transaction recieved from PayPal.
	 *
	 * @param	$fixed_price			Optional.
	 * @param	$user_field				Optional.
	 * @param	$email					Optional.
	 * @access	public
	 */
	public function processPayPal($fixed_price=TRUE, $user_field=NULL, $email=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		if(($_SERVER['REQUEST_METHOD']=='POST') && !empty($_POST))
		{
			$this->readPayPalPost();

			# For debugging
			$this->script=__FILE__;

			/*
			 * Post back to PayPal system to validate (via cURL)
			 * postback to: https://www.paypal.com/cgi-bin/webscr (for real Paypal)
			 * Postback to: https://www.sandbox.paypal.com/cgi-bin/webscr (for testing Paypal - Sandbox)
			 */
			$this->setPayPalURL($this->test);
			$url=$this->getPayPalURL();
			$this->cURLToPayPal($url);

			# Get the validation script
			# (check that order is Completed and Verified and enter info into Database)
			# Is the order VERIFIED?
			if(strcmp($this->curl_result, "VERIFIED") !== 0)
			{
				# The order is NOT VERIFIED (INVALID.)
				$this->error.="Not Verified!\n<br />";
				# What is the subject of the error email send to the admin?
				$this->error_subject="Check IPN_log".$this->log_subj.", there was an error.";
				$this->makeLog();
				throw new Exception($this->error_subject.' -> '.$this->error, E_RECOVERABLE_ERROR);
				die();
			}
			# The order is VERIFIED.
			# Is the order Completed?
			if($this->payment_status !== 'Completed' && $this->payment_status !== 'Refunded')
			{
				# The order is NOT Completed!
				$this->error.="Payment not completed!\n<br />";
				# What is the subject of the error email send to the admin?
				$this->error_subject="Check IPN_log".$this->log_subj.", there was an error.";
				$this->makeLog();
				throw new Exception($this->error_subject.' -> '.$this->error, E_RECOVERABLE_ERROR);
				die();
			}
			# The order is Completed.
			# Check if Transaction ID has been processed before.
			try
			{
				$checkQuery=$db->query('SELECT `txnid` FROM `'.DBPREFIX.'orders` WHERE `txnid` = '.$db->quote($db->escape($this->txn_id)));
			}
			catch(ezDB_Error $ez)
			{
				# Unable to access the orders table! This error will stop the script.
				$this->error.="Unable to access the \"orders\" table in Database! \n<br />Error occured: ".$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query;
				# What is the subject of the error email send to the admin?
				$this->error_subject="Check IPN_log".$this->log_subj.", there was an error.";
				$this->makeLog();
				throw new Exception($this->error_subject.' -> '.$this->error, E_RECOVERABLE_ERROR);
				die();
			}
			if($checkQuery !== 0)
			{
				# The Transaction ID is already in the DataBase!
				$this->error.="Duplicate Transaction ID check query failed:\n<br />" . mysql_error() . "\n<br />" . mysql_errno()."\n<br /><br />";
				# What is the subject of the error email send to the admin?
				$this->error_subject="Check IPN_log".$this->log_subj.", there was an error.";
				$this->makeLog();
				throw new Exception($this->error_subject.' -> '.$this->error, E_RECOVERABLE_ERROR);
				die();
			}
			# This is a unique Transaction ID.
			# Check that this is really our order by comparing the email they have associated with us is the email we use for PayPal.
			switch($this->receiver_email)
			{
				case 'seller@paypalsandbox.com':
					break;
				case PP_EMAIL:
					break;
				default:
					# The $receiver_email does not match any of our email addresses!
					$this->error.="That ain\'t us!\n<br />";
					# What is the subject of the error email send to the admin?
					$this->error_subject="Check IPN_log".$this->log_subj.", there was an error.";
					$this->makeLog();
					throw new Exception($this->error_subject.' -> '.$this->error, E_RECOVERABLE_ERROR);
					die();
			}
			# The $receiver_email matches our email address.

			# Is it a shopping cart transaction?
			$this->processCart();

			# It's not a shopping cart transaction
			if($this->txn_type != "cart")
			{
				$this->processOrder($fixed_price, $user_field, $email);
			}

			# Find the buyer in our database.
			$id=$this->id;
			$getUser='SELECT `fname`, `lname` FROM `'.DBPREFIX.'users` WHERE `ID` = '.$db->quote($db->escape($id));
			# Can't find that user!
			if($db->query($getUser) !== 1)
			{
				$this->error.="Not a registered user from our site!\n<br />";
				# What is the subject of the error email send to the admin?
				$this->error_subjec="Check IPN_log".$this->log_subj.", there was an error.";
				$this->makeLog();
				throw new Exception($this->error_subject.' -> '.$this->error, E_RECOVERABLE_ERROR);
				die();
			}
		}
	} #==== End -- processPayPal

	/**
	 * redirectToPayPal
	 *
	 * Catches PayPal Data and redirects it to the appropriate PayPal site.
	 *
	 * @param	bool $redirect			Optional.
	 * @access	public
	 */
	public function redirectToPayPal($redirect=FALSE)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Document instance to a variable.
		$doc=Document::getInstance();

		if(array_key_exists('_submit_check', $_REQUEST))
		{
			# Is this a test payment meant for the "Sandbox"?
			if(isset($_REQUEST['test']) && ($_REQUEST['test']=='y'))
			{
				$this->test='sandbox.';
			}
			# Set the PayPal URL.
			$this->setPayPalURL($this->test);
			$paypal_url=$this->getPayPalURL();

			$cmd='?cmd='.$_REQUEST['cmd'];
			$hosted_button_id=((isset($_REQUEST['hosted_button_id'])) ? '&hosted_button_id='.$_REQUEST['hosted_button_id'] : '');
			$cancel_return='&cancel_return='.$_REQUEST['cancel_return'];
			$custom='&custom='.$_REQUEST['custom'];
			$return='&return='.$_REQUEST['return'];
			$business='&business='.PP_EMAIL;
			$redirect_url=$paypal_url.$cmd.$hosted_button_id.$cancel_return.$custom.$return.$business;
			if(!isset($_REQUEST['hosted_button_id']))
			{
				if(isset($_REQUEST['item_number']))
				{
					$product=$db->get_row('SELECT `title`, `price` FROM `'.DBPREFIX.'products` WHERE `id` = '.$db->quote($db->escape(urldecode($_REQUEST['item_number']))));
					$item_name='&item_name='.$product->title;
					$amount='&amount='.$product->price;
					$item_number='&item_number='.$_REQUEST['item_number'];
					$notify_url='&notify_url='.$this->notify_url;
					$shipping='&shipping='.$this->shipping;
					$shipping2='&shipping2='.$this->shipping2;
					$handling='&handling='.$this->handling;
					$redirect_url.=$item_name.$amount.$item_number.$item_name.$notify_url;
				}
			}
			# Is it for a shopping cart?
			if(isset($_REQUEST['shopping_url']))
			{
				$shopping_url='&shopping_url='.$_REQUEST['shopping_url'];
				$add='1';
				$redirect_url.=$shopping_url.$add;
			}
			for($i=0; $i < 7; $i++)
			{
				$option='os'.$i;
				$option_select='option_select'.$i;
				$option_amount='option_amount'.$i;
				# Are there options?
				if(isset($_REQUEST[$option]))
				{

					${$option}='&'.$option.'='.$_REQUEST[$option];
					$redirect_url.=${$option};
				}
				if(isset($_REQUEST[$option_select]))
				{

					${$option_select}='&'.$option_select.'='.$_REQUEST[$option_select];
					$redirect_url.=${$option_select};
				}
				if(isset($_REQUEST[$option_amount]))
				{

					${$option_amount}='&'.$option_amount.'='.$_REQUEST[$option_amount];
					$redirect_url.=${$option_amount};
				}
			}
			$doc->redirect($redirect_url);
		}
		else
		{
			if($redirect===TRUE)
			{
				$doc->redirect(DEFAULT_REDIRECT);
			}
			elseif($redirect!==FALSE)
			{
				$doc->redirect($redirect);
			}
		}
	} #==== End -- redirectToPayPal

	/*** End public methods ***/



	/*** protected methods ***/

	/**
	 * addCartToDB
	 *
	 * Add the processed Shopping Cart order to the DB.
	 *
	 * @access	protected
	 */
	protected function addCartToDB()
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# For debugging
		$this->script=__FILE__;

		# Put cart order info in the orders table.
		try
		{
			$db->query('INSERT INTO '.DBPREFIX.'orders (`user_id`, `txnid`, `payer_id`, `txn_type`, `payer_email`, `address`, `city`, `state`, `country`, `country_code`, `zipcode`, `residence`, `payment_currency`, `settle_currency`, `exchange_rate`, `shipping`, `payment_gross`, `payment_fee`, `settle_amount`, `memo`, `payment_date`, `pending_reason`, `reason_code`, `creation_date`) VALUES ('.
					$db->quote($db->escape($this->id)).', '.
					$db->quote($db->escape($this->txn_id)).', '.
					$db->quote($db->escape($this->payer_id)).', '.
					$db->quote($db->escape($this->txn_type)).', '.
					$db->quote($db->escape($this->payer_email)).', '.
					$db->quote($db->escape($this->address)).', '.
					$db->quote($db->escape($this->city)).', '.
					$db->quote($db->escape($this->state)).', '.
					$db->quote($db->escape($this->country)).', '.
					$db->quote($db->escape($this->country_code)).', '.
					$db->quote($db->escape($this->zipcode)).', '.
					$db->quote($db->escape($this->residence)).', '.
					$db->quote($db->escape($this->payment_currency)).', '.
					$db->quote($db->escape($this->settle_currency)).', '.
					$db->quote($db->escape($this->exchange_rate)).', '.
					$db->quote($db->escape($this->shipping)).', '.
					$db->quote($db->escape($this->payment_gross)).', '.
					$db->quote($db->escape($this->payment_fee)).', '.
					$db->quote($db->escape($this->settle_amount)).', '.
					$db->quote($db->escape($this->memo)).', '.
					$db->quote($db->escape($this->payment_date)).', '.
					$db->quote($db->escape($this->pending_reason)).', '.
					$db->quote($db->escape($this->reason_code)).', '.
					$db->quote($db->escape(YEAR_MM_DD)).')');
		}
		catch(ezDB_Error $e)
		{
			# The orders table was not updated! This error will not stop the script.
			$this->error.="Transaction not entered into \"orders\" in Database! \n<br />Error occured: " . $e->message . ', code: ' . $e->code . '<br />Last query: '. $e->last_query;
			# What is the subject of the error email send to the admin?
			$this->error_subject="Check IPN_log".$this->log_subj.", there was an error.";
			$this->makeLog();
			throw new Exception($this->error_subject.' -> '.$this->error, E_RECOVERABLE_ERROR);
			die();
		}
		# Put each item from the shopping cart order into the orders_cart_info table.
		for($i=1; $i<=$this->num_cart_items; $i++)
		{
			# $i will increase each time till it equals the number of items in the cart.
			$itemname="item_name".$i;
			$itemname=$_POST[$itemname];
			$itemnumber="item_number".$i;
			$itemnumber=$_POST[$itemnumber];
			$item_quantity="quantity".$i;
			$item_quantity=$_POST[$item_quantity];
			$on0="option_name1_".$i;
			$on0=$_POST[$on0];
			$os0="option_selection1_".$i;
			$os0=$_POST[$os0];
			$on1="option_name2_".$i;
			$on1=$_POST[$on1];
			$os1="option_selection2_".$i;
			$os1=$_POST[$os1];
			try
			{
				$db->query('INSERT INTO `'.DBPREFIX.'orders_cart` (`user_id`, `txnid`, `payer_id`, `item_number`, `item_name`, `quantity`, `option_name1`, `option_selection1`, `option_name2`, `option_selection2`, `payment_date`, `creation_date`) VALUES ('.
						$db->quote($db->escape($this->id)).', '.
						$db->quote($db->escape($this->txn_id)).', '.
						$db->quote($db->escape($this->payer_id)).', '.
						$db->quote($db->escape($this->itemnumber)).', '.
						$db->quote($db->escape($this->itemname)).', '.
						$db->quote($db->escape($this->item_quantity)).', '.
						$db->quote($db->escape($this->on0)).', '.
						$db->quote($db->escape($this->os0)).', '.
						$db->quote($db->escape($this->on1)).', '.
						$db->quote($db->escape($this->os1)).',' .
						$db->quote($db->escape($this->payment_date)).', '.
						$db->quote($db->escape($this->thisyear)).')');
			}
			catch(ezDB_Error $e)
			{
				# The orders table was not updated! This error will not stop the script.
				$this->error.="Transaction not entered into \"orders_cart\" in the Database! \n<br />Error occured: " . $e->message . ', code: ' . $e->code . '<br />Last query: '. $e->last_query;
				# What is the subject of the error email send to the admin?
				$this->error_subject="Check IPN_log".$this->log_subj.", there was an error.";
				$this->makeLog();
				throw new Exception($this->error_subject.' -> '.$this->error, E_RECOVERABLE_ERROR);
				die();
			}
			# Update the user's privileges.
			//require_once BASE_PATH.'MDC/IPN_update_privileges.php';
		} # end for
	} #==== End -- addCartToDB

	/**
	 * addOrderToDB
	 *
	 * Add the processed non-Shopping Cart order to the DB.
	 *
	 * @access	protected
	 */
	protected function addOrderToDB()
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# For debugging
		$this->script=__FILE__;

		# Put order info in the DataBase (orders table.)
		try
		{
			$db->query('INSERT INTO `'.DBPREFIX.'orders` (`user_id`, `txnid`, `payer_id`, `txn_type`, `item_number`, `payer_email`, `address`, `city`, `state`, `country`, `country_code`, `zipcode`, `residence`, `item_name`, `quantity`, `option_name1`, `option_selection1`, `option_name2`, `option_selection2`, `payment_currency`, `settle_currency`, `exchange_rate`, `shipping`, `payment_gross`, `payment_fee`, `settle_amount`, `memo`, `payment_date`, `pending_reason`, `reason_code`, `creation_date`) VALUES ('.
					$db->quote($db->escape($this->id)).', '.
					$db->quote($db->escape($this->txn_id)).', '.
					$db->quote($db->escape($this->payer_id)).', '.
					$db->quote($db->escape($this->txn_type)).', '.
					$db->quote($db->escape($this->item_number)).', '.
					$db->quote($db->escape($this->payer_email)).', '.
					$db->quote($db->escape($this->address)).', '.
					$db->quote($db->escape($this->city)).', '.
					$db->quote($db->escape($this->state)).', '.
					$db->quote($db->escape($this->country)).', '.
					$db->quote($db->escape($this->country_code)).', '.
					$db->quote($db->escape($this->zipcode)).', '.
					$db->quote($db->escape($this->residence)).', '.
					$db->quote($db->escape($this->item_name)).', '.
					$db->quote($db->escape($this->quantity)).', '.
					$db->quote($db->escape($this->option_name1)).', '.
					$db->quote($db->escape($this->option_selection1)).', '.
					$db->quote($db->escape($this->option_name2)).', '.
					$db->quote($db->escape($this->option_selection2)).', '.
					$db->quote($db->escape($this->payment_currency)).', '.
					$db->quote($db->escape($this->settle_currency)).', '.
					$db->quote($db->escape($this->exchange_rate)).', '.
					$db->quote($db->escape($this->shipping)).', '.
					$db->quote($db->escape($this->payment_gross)).', '.
					$db->quote($db->escape($this->payment_fee)).', '.
					$db->quote($db->escape($this->settle_amount)).', '.
					$db->quote($db->escape($this->memo)).',' .
					$db->quote($db->escape($this->payment_date)).', '.
					$db->quote($db->escape($this->pending_reason)).', '.
					$db->quote($db->escape($this->reason_code)).', '.
					$db->quote($db->escape(YEAR_MM_DD)).')');
		}
		catch(ezDB_Error $ez)
		{
			# The orders table was not updated! This error will not stop the script.
			$this->error.="Transaction not entered into \"orders\" in the Database! \n<br />Error occured: ".$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query;
			# What is the subject of the error email send to the admin?
			$this->error_subject="Check IPN_log".$this->log_subj.", there was an error.";
			$this->makeLog();
			throw new Exception($this->error_subject.' -> '.$this->error, E_RECOVERABLE_ERROR);
			die();
		}
	} #==== End -- addOrderToDB

	/**
	 * cURLToPayPal
	 *
	 * Uses cURL to POST back to the PayPal system to validate a transaction recieved from PayPal.
	 *
	 * @param	$url
	 * @access	protected
	 */
	protected function cURLToPayPal($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->req);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($this->req)));
		curl_setopt($ch, CURLOPT_HEADER , 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);

		$this->curl_result=@curl_exec($ch);
		$this->curl_err=curl_error($ch);
		curl_close($ch);
	} #==== End -- cURLToPayPal

	/**
	 * makeLog
	 *
	 * Makes an error log.
	 *
	 * @param	$mail					Optional.
	 * @access	protected
	 */
	protected function makeLog($mail=TRUE)
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();

		$this->datetime=MONTH_DD_YEAR_12TIME_TZ;

		# Set the shopping cart data.
		$this->setShoppingCart();

		$orderData="Log date: ".$this->datetime."<br />\n<br />\n".
								"Item information:<br />\n".
								"item name: ".urldecode($this->item_name)."<br />\n".
								(($this->item_number!==NULL) ? "item number: ".$this->item_number."<br />\n" : '').
								(($this->quantity!==NULL) ? "quantity: ".$this->quantity."<br />\n" : '').
								(($this->option_name1!==NULL) ? "option name1: ".urldecode($this->option_name1)."<br />\n" : '').
								(($this->option_selection1!==NULL) ? "option selection1: ".$this->option_selection1."<br />\n" : '').
								(($this->option_name2!==NULL) ? "option name2: ".urldecode($this->option_name2)."<br />\n" : '').
								(($this->option_selection2!==NULL) ? "option selection2: ".$this->option_selection2."<br />\n<br />\n" : '').
								(($this->num_cart_items!==NULL) ? "number of cart items: ".$this->num_cart_items."<br />\n" : '').

								"Transaction specific information:<br />\n".
								"payment date: ".urldecode($this->payment_date)."<br />\n".
								"payment type: ".$this->payment_type."<br />\n".
								"payment status: ".$this->payment_status."<br />\n".
								"tax: ".$this->tax."<br />\n".
								(($this->parent_txn_id!==NULL) ? "parent transaction id: ".$this->parent_txn_id."<br />\n" : '').
								"transaction id: ".$this->txn_id."<br />\n".
								"transaction type: ".$this->txn_type."<br />\n".
								"payment currency: ".$this->payment_currency."<br />\n".
								(($this->settle_currency!==NULL) ? "settle currency: ".$this->shipping."<br />\n" : '').
								(($this->settle_currency!==NULL) ? "shipping: ".$this->shipping."<br />\n" : '').
								"payment gross: ".$this->payment_gross."<br />\n".
								(($this->payment_gross_USD!==NULL) ? "payment gross (USD): ".$this->payment_gross_USD."<br />\n" : '').
								"payment fee: ".$this->payment_fee."<br />\n".
								(($this->payment_fee_USD!==NULL) ? "payment fee (USD): ".$this->payment_fee_USD."<br />\n" : '').
								(($this->exchange_rate!==NULL) ? "exchange rate: ".$this->exchange_rate."<br />\n" : '').
								(($this->settle_amount!==NULL) ? "settle amount: ".$this->settle_amount."<br />\n" : '').
								(($this->invoice!==NULL) ? "invoice: ".$this->invoice."<br />\n" : '').
								(($this->memo!==NULL) ? "memo: ".urldecode($this->memo)."<br />\n" : '').
								(($this->pending_reason!==NULL) ? "pending reason: ".$this->pending_reason."<br />\n" : '').
								(($this->reason_code!==NULL) ? "reason code: ".$this->reason_code."<br />\n" : '').
								(($this->auth_id!==NULL) ? "auth id: ".$this->auth_id."<br />\n" : '').
								(($this->auth_exp!==NULL) ? "auth exp: ".$this->auth_exp."<br />\n" : '').
								(($this->auth_status!==NULL) ? "auth status: ".$this->auth_status."<br />\n<br />\n" : '').

								# Get the shopping cart data.
								$this->getShoppingCart().

								"CWIS User ID: ".$this->id."<br />\n<br />\n".

								"Buyer specific information:<br />\n".
								"first name: ".$this->fname."<br />\n".
								"last name: ".$this->lname."<br />\n".
								"payer email: ".$this->payer_email."<br />\n".
								"address: ".urldecode($this->address)."<br />\n".
								"city: ".$this->city."<br />\n".
								"state: ".$this->state."<br />\n".
								"country: ".$this->country."<br />\n".
								"country code: ".$this->country_code."<br />\n".
								"zipcode: ".$this->zipcode."<br />\n".
								"address status: ".$this->address_status."<br />\n".
								"residence: ".$this->residence."<br />\n".
								(($this->phone!==NULL) ? "phone: ".$this->phone."<br />\n" : '').
								"payer status: ".$this->payer_status."<br />\n".
								(($this->payer_business_name!==NULL) ? "payer business name: ".urldecode($this->payer_business_name)."<br />\n" : '').
								"payer id: ".$this->payer_id."<br />\n<br />\n".

								"Seller specific information<br />\n".
								"receiver_email: ".urldecode($this->receiver_email)."<br />\n".
								"business: ".urldecode($this->business)."<br />\n".
								"receiver id: ".$this->receiver_id."<br />\n<br />\n".

								"error: ".$this->error."<br />\n<br />\n".

								"I caught the error on: ".$this->script."<br />\n<br />\n".

								"****EndLog date: ".$this->datetime."\n\n\n";
		# $subject is set in the script that calls this page
		$pp_post=wordwrap(str_replace('&', "<br />\n&", $this->req), 70, "<br />\n");
		$this->setEmailMessage($this->curl_result."<br />\n<br />\nThis is what Paypal sent us:<br />\n".$pp_post."<br />\n<br />\n".$orderData);
		$message=$this->getEmailMessage();
		if($mail===TRUE)
		{
			$this->email_to=ADMIN_EMAIL;
			$do_mail=$doc->sendEmail($this->error_subject, $this->email_to, $message);
		}
		if($mail!==TRUE)
		{
			$message.="Not sending email.\n";
		}
		# write a flat file
		# Remove the xhtml "<br />\n"
		$message=str_replace('<br />', '', $message);
		# $log is set in the first page of this script
		$fh=fopen($this->log, 'a') or die('can\'t open file');
		fwrite($fh, $message);
		fclose($fh);
	} #==== End -- makeLog

	/**
	 * processCart
	 *
	 * Processes an order submitted via Shopping Cart.
	 *
	 * @access	protected
	 */
	protected function processCart()
	{
		# For debugging
		$this->script=__FILE__;

		if($this->txn_type == "cart")
		{
			# Check that the product information is correct
			# Assign Shopping Cart Item info to variables.
			$num_cart_items=$this->num_cart_items;
			# $i will increase each time till it equals the number of items in the cart.
			for($i=1; $i <= $num_cart_items; $i++)
			{
				$item_name="item_name".$i;
				$item_name=$_POST[$item_name];
				$item_quantity="quantity".$i;
				$item_quantity=$_POST[$item_quantity];
				$payment_gross="mc_gross_".$i;
				$payment_gross=$_POST[$payment_gross];
				# Process the main order info.
				$this->processOrder($item_name, $payment_gross, $item_quantity);
			} # end for
			# The buyer is a user in the DataBase.
			# Put cart order info in the orders and orders_cart_info tables and give buyer appropriate privileges.
			$this->addCartToDB();
		}
	} #==== End -- processCart

	/**
	 * processOrder
	 *
	 * Processes an order not submitted via Shopping Cart.
	 *
	 * @param	boolean $fixed_price	TRUE is the product should have a fixed price,
	 *									FALSE if the amount is dictated by the puchaser as in the case of a donation.
	 * @param	array $user_field		The key= the type of product, ie "donation", "subscription", "product".
	 *										The value= update value. NULL = don't update a user.
	 * @param	string/array $email		The email address(es) to send the transaction notification to.
	 *										May be a string"single_email_address" or and array of email addresses.
	 *										NULL = don't send an email.
	 * @access	protected
	 */
	protected function processOrder($fixed_price=TRUE, $user_field=NULL, $email=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# For debugging
		$this->script=__FILE__;

		$item_name=urldecode($this->item_name);
		$paymentgross=$this->payment_gross;
		$item_quantity=$this->quantity;

		# Check that the product information is correct.
		# Get the Product class.
		require_once Utility::locateFile(MODULES.'Product'.DS.'Product.php');
		# Instantiate a new Product object.
		$product=new Product();
		# Get the product info from the `product` table and set it to variables.
		$product->getProducts('all', 1, '`price`, `currency`', 'id', 'ASC', '`title` = '.$db->quote($db->escape($item_name)));
		# Get the returned product from the datamember and set it to a variable.
		$products=$product->getAllProducts();
		# Check if there was a product returned.
		if(empty($products))
		{
			# Wrong product!
			# Add to the error data member.
			$this->error.="Not what we sold ya!\n<br />";
			# Set the subject of the error email to send to the admin
			$this->error_subject="Check IPN_log".$this->log_subj.", there was an error.";
			# Add the error to the error log.
			$this->makeLog();
			# Throw an error.
			throw new Exception($this->error_subject.' -> '.$this->error, E_RECOVERABLE_ERROR);
			# Explicitly stop the script.
			die();
		}

		# It is the right product.
		# Loop through the returned product to get the record and set it to a variable.
		foreach($products as $this_product)
		{
			# Set the price to a variable.
			$thePrice=$this_product->price;
			# Set the currency to a variable.
			$theCurrency=$this_product->currency;
		}
		# Check if the price is fixed.
		if($fixed_price===TRUE)
		{
			# Compare the price.
			# Wrong price!
			if($paymentgross !== number_format(($thePrice * $item_quantity), 2, '.', ','))
			{
				$this->error.="Not the right price!\n<br />";
				# What is the subject of the error email send to the admin?
				$this->error_subject="Check IPN_log".$this->log_subj.", there was an error.";
				$this->makeLog();
				throw new Exception($this->error_subject.' -> '.$this->error, E_RECOVERABLE_ERROR);
				# Explicitly stop the script.
				die();
			}
		}
		# The price is right.
		# Compare the currency.
		# Wrong currency!
		if($this->payment_currency!==$theCurrency)
		{
			# Add to the error data member.
			$this->error.="Not the right type of currency!\n<br />";
			# Set the subject of the error email to send to the admin.
			$this->error_subject="Check IPN_log".$this->log_subj.", there was an error.";
			# Add the error to the error log.
			$this->makeLog();
			# Throw an error.
			throw new Exception($this->error_subject.' -> '.$this->error, E_RECOVERABLE_ERROR);
			# Explicitly stop the script.
			die();
		}

		# The currency is correct.
		# Put order info in the `orders` table and give the buyer appropriate privileges.
		$this->addOrderToDB();
		# Check if the passed user field variable is empty.
		if(!empty($user_field))
		{
			# Explicitly make it an array.
			$user_field=(array)$user_field;
			# Update the user to reflect their purchase.
			$this->updateUserInfo($user_field);
		}
		# Check if the passed email variable is empty.
		if(!empty($email))
		{
			# Explicitly make it an array.
			$email=(array)$email;
			# Send an email to the the proper admins and/or managers.
			$this->emailTransactionInfo($email);
		}
	} #==== End -- processOrder

	/**
	 * readPayPalPost
	 *
	 * Reads Data sent from PayPal and populates the appropriate Data members.
	 *
	 * @access	protected
	 */
	protected function readPayPalPost()
	{
		$this->log=LOGS.'IPN.log';
		# read the post from PayPal system and add 'cmd'
		foreach($_POST as $key => $value)
		{
			$value=urlencode(stripslashes($value));
			$this->req.='&'.$key.'='.$value;
		}

		# Check if this is for testing with the sandbox.
		if(isset($_POST['test_ipn']) && ($_POST['test_ipn'] == 1))
		{
			$this->test='sandbox.';
			# Where do we log errors?
			$this->log=LOGS.'IPN_test.log';
			$this->log_subj='_test';
		}

		# assign posted variables to local variables
		# item information variables
		$this->item_name=$_POST['item_name'];
		$this->item_number=((isset($_POST['item_number'])) ? $_POST['item_number'] : NULL);
		$this->quantity=((isset($_POST['quantity'])) ? $_POST['quantity'] : NULL);
		$this->num_cart_items=((isset($_POST['num_cart_items'])) ? $_POST['num_cart_items'] : NULL);
		$this->option_name1=((isset($_POST['option_name1'])) ? $_POST['option_name1'] : NULL);
		$this->option_selection1=((isset($_POST['option_selection1'])) ? $_POST['option_selection1'] : NULL);
		$this->option_name2=((isset($_POST['option_name2'])) ? $_POST['option_name2'] : NULL);
		$this->option_selection2=((isset($_POST['option_selection2'])) ? $_POST['option_selection2'] : NULL);

		# transaction specific variables
		$this->payment_date=$_POST['payment_date'];
		$this->payment_type=$_POST['payment_type'];
		$this->payment_status=$_POST['payment_status'];
		$this->tax=((isset($_POST['tax'])) ? $_POST['tax'] : NULL);
		$this->parent_txn_id=((isset($_POST['parent_txn_id'])) ? $_POST['parent_txn_id'] : NULL);
		$this->txn_id=$_POST['txn_id'];
		$this->txn_type=((isset($_POST['txn_type'])) ? $_POST['txn_type'] : NULL);
		$this->payment_currency=$_POST['mc_currency'];
		$this->settle_currency=((isset($_POST['settle_currency'])) ? $_POST['settle_currency'] : NULL);
		$this->shipping=((isset($_POST['shipping'])) ? $_POST['shipping'] : NULL);
		$this->payment_gross=$_POST['mc_gross'];
		$this->payment_gross_USD=((isset($_POST['payment_gross'])) ? $_POST['payment_gross'] : NULL);
		$this->payment_fee=$_POST['mc_fee'];
		$this->payment_fee_USD=((isset($_POST['payment_fee'])) ? $_POST['payment_fee'] : NULL);
		$this->exchange_rate=((isset($_POST['exchange_rate'])) ? $_POST['exchange_rate'] : NULL);
		$this->settle_amount=((isset($_POST['settle_amount'])) ? $_POST['settle_amount'] : NULL);
		$this->invoice=((isset($_POST['invoice'])) ? $_POST['invoice'] : NULL);
		$this->memo=((isset($_POST['memo'])) ? $_POST['memo'] : NULL);
		$this->pending_reason=((isset($_POST['pending_reason'])) ? $_POST['pending_reason'] : NULL);
		$this->reason_code=((isset($_POST['reason_code'])) ? $_POST['reason_code'] : NULL);
		$this->auth_id=((isset($_POST['auth_id'])) ? $_POST['auth_id'] : NULL);
		$this->auth_exp=((isset($_POST['auth_exp'])) ? $_POST['auth_exp'] : NULL);
		$this->auth_status=((isset($_POST['auth_status'])) ? $_POST['auth_status'] : NULL);

		# custom variables
		$this->id=$_POST['custom'];

		# buyer specific variables
		$this->fname=$_POST['first_name'];
		$this->lname=$_POST['last_name'];
		$this->payer_email=$_POST['payer_email'];
		$this->address=((isset($_POST['address_street'])) ? $_POST['address_street'] : NULL);
		$this->city=((isset($_POST['address_city'])) ? $_POST['address_city'] : NULL);
		$this->state=((isset($_POST['address_state'])) ? $_POST['address_state'] : NULL);
		$this->country=((isset($_POST['address_country'])) ? $_POST['address_country'] : NULL);
		$this->country_code=((isset($_POST['address_country_code'])) ? $_POST['address_country_code'] : NULL);
		$this->zipcode=((isset($_POST['address_zip'])) ? $_POST['address_zip'] : NULL);
		$this->address_status=((isset($_POST['address_status'])) ? $_POST['address_status'] : NULL);
		$this->residence=((isset($_POST['residence_country'])) ? $_POST['residence_country'] : NULL);
		$this->phone=((isset($_POST['contact_phone'])) ? $_POST['contact_phone'] : NULL);
		$this->payer_status=((isset($_POST['payer_status'])) ? $_POST['payer_status'] : NULL);
		$this->payer_business_name=((isset($_POST['payer_business_name'])) ? $_POST['payer_business_name'] : NULL);
		$this->payer_id =$_POST['payer_id'];

		# seller specific variables
		$this->receiver_email=$_POST['receiver_email'];
		$this->business=$_POST['business'];
		$this->receiver_id=$_POST['receiver_id'];
	} #==== End -- readPayPalPost

	/**
	 * calculateNewDate
	 *
	 * Sends an email regarding the order.
	 *
	 * @param	string $add_time		The length of time to add to the current date.
	 *										ie. 'year', 'month', or 'week'.
	 * @param	string $date			The date to add to. If the passed date is in the past or empty, it is updated to the current date before adding time.
	 * @access	protected
	 */
	protected function calculateNewDate($add_time='year', $date=NULL)
	{
		# Check if the passed date is empty or in the past.
		if($date!==NULL || $date<date('Y-m-d'))
		{
			# Set the date to the current date.
			$date=date('Y-m-d');
		}
		# Explode the date into an array. $date_a[0]=year, $date_a[1]=month, $date_a[2]=day,
		$date_a=explode('-', $date);
		# Compare the $add_time value.
		switch($add_time)
		{
			case 'year':
				# Add one year to the date.
				$value=date('Y-m-d', mktime(0, 0, 0, $date_a[1], $date_a[2], $date_a[0]+1));
				break;
			case 'month':
				# Add 31 days to the date.
				$value=date('Y-m-d', mktime(0, 0, 0, $date_a[1], $date_a[2]+31, $date_a[0]));
				break;
			case 'week':
				# Add 7 days to the date.
				$value=date('Y-m-d', mktime(0, 0, 0, $date_a[1], $date_a[2]+7, $date_a[0]));
				break;
		}
		return $value;
	} #==== End -- calculateNewDate

	/*** End protected methods ***/



	/*** private methods ***/

	/**
	 * emailTransactionInfo
	 *
	 * Sends an email regarding the order.
	 *
	 * @param	string/array $email		The email address(es) to send the transaction notification to.
	 *										May be a string"single_email_address" or and array of email addresses.
	 * @access	private
	 */
	private function emailTransactionInfo($email)
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();

		# Set the email subject to a variable.
		$subject='Important '.DOMAIN_NAME.' Info';
		# Create the email message and set it to a variable.
		$message="Hello,<br />\n<br />\n".
		'There has been a purchase and/or donation at the '.DOMAIN_NAME.' website. The payment has been credited to the corresponding PayPal&trade; account. Please login at: <a href="http://www.paypal.com/">http://www.paypal.com/</a> to view the details.'."<br />\n<br />\n".
		DOMAIN_NAME.' Automated Emailer';

		# Loop through the email addresses.
		foreach($email as $to)
		{
			# Send a copy of the email to this address.
			$doc->sendEmail($subject, $to, $message);
		}
	} #==== End -- emailTransactionInfo

	/**
	 * updateUserInfo
	 *
	 * Processes an order submitted via Shopping Cart.
	 *
	 * @param	$user_field
	 * @access	public
	 */
	public function updateUserInfo($user_field)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# For debugging
		$this->script=__FILE__;

		$field=key($user_field);
		$update_value=$user_field[$field];
		$id=$this->id;
		$paymentgross=$this->payment_gross;

		# Get the current status for the User.
		try
		{
			# Instantiate a new User object.
			$user=new User();
			# Update the user's account to reflect their purchase.
			try
			{
				# Update the user's info in the `users` table.
				$user->updateUser(array('ID'=>$id), array($field=>$update_value));
			}
			catch(ezDB_Error $ez)
			{
				# The user's account was not updated! This error will not stop the script.
				$this->error.="Couldn't update the \"".$field."\" field for the user with ID ".$id." in the Database! \n<br />Error occured: ".$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query;
				# What is the subject of the error email send to the admin?
				$this->error_subject="Check IPN_log".$this->log_subj.", there was an error.";
				# Add the error to the error log.
				$this->makeLog();
				throw new Exception($this->error_subject.' -> '.$this->error, E_RECOVERABLE_ERROR);
				# Explicitly stop the script.
				die();
			}
		}
		catch(Exception $e)
		{
			# The product field was not retrieved!
			$this->error.="Couldn't retrieve the `product` field for the user with ID ".$id." from the Database! \n<br />Error occured: ".$ez->error ."\n<br />Code: ".$ez->errno."\n<br />Last query: ".$ez->last_query;
			# What is the subject of the error email send to the admin?
			$this->error_subject="Check IPN_log".$this->log_subj.", there was an error.";
			# Add the error to the error log.
			$this->makeLog();
			throw new Exception($this->error_subject.' -> '.$this->error, E_RECOVERABLE_ERROR);
			# Explicitly stop the script.
			die();
		}
	} #==== End -- updateUserInfo

	/*** End private methods ***/

} # End PayPal class.