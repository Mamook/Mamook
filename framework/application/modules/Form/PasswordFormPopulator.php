<?php /* framework/application/modules/Form/PasswordFormPopulator.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormPopulator.php');


/**
 * PasswordFormPopulator
 *
 * The PasswordFormPopulator Class is used populate chnage password forms.
 *
 */
class PasswordFormPopulator extends FormPopulator
{
    /*** data members ***/

    private $email_password=NULL;
    private $password_confirmed=NULL;
    private $user_object=NULL;

    /*** End data members ***/



    /*** mutator methods ***/

    /**
     * Sets the data member $email_password.
     *
     * @param   $email_password
     * @access  protected
     */
    protected function setEmailPassword($email_password)
    {
        # Check if the passed value is empty.
        if(empty($email_password) OR $email_password!='checked')
        {
            # Explicitly set the value to NULL.
            $email_password=NULL;
        }
        # Set the data member.
        $this->email_password=$email_password;
    }

    /**
     * Sets the data member $password_confirmed.
     *
     * @param   $object
     * @access  protected
     */
    protected function setPasswordConfirmed($password_confirmed)
    {
        # Check if the value is empty.
        if(!empty($password_confirmed))
        {
            # Clean it up and set the data member.
            $password_confirmed=trim($password_confirmed);
        }
        else
        {
            # Explicitly set it to NULL.
            $password_confirmed=NULL;
        }
        # Set the data member.
        $this->password_confirmed=$password_confirmed;
    }

    /**
     * Sets the data member $user_object.
     *
     * @param    $object
     * @access    protected
     */
    protected function setUserObject($object)
    {
        # Check if the passed value is empty and an object.
        if(empty($object) OR !is_object($object))
        {
            # Explicitly set the value to NULL.
            $object=NULL;
        }
        # Set the data member.
        $this->user_object=$object;
    }

    /*** End mutator methods ***/



    /*** accessor methods ***/

    /**
     * Returns the data member $email_password.
     *
     * @access    public
     */
    public function getEmailPassword()
    {
        return $this->email_password;
    }

    /**
     * Returns the data member $password_confirmed.
     *
     * @access    public
     */
    public function getPasswordConfirmed()
    {
        return $this->password_confirmed;
    }

    /**
     * Returns the data member $user_object.
     *
     * @access    public
     */
    public function getUserObject()
    {
        return $this->user_object;
    }

    /*** End accessor methods ***/



    /*** public methods ***/

    /**
     * Populates a user profile form.
     *
     * @param    array $data                An array of values to populate the form with.
     * @access    public
     */
    public function populatePasswordForm($data=array(), $index='password')
    {
        try
        {
            # Instantiate a new User object.
            $user_obj=new User();
            # Set the Staff object to the staff_object data member for use outside of this method.
            $this->setUserObject($user_obj);

            # Set the passed data array to the data member.
            $this->setData($data);

            # Process any post data held in SESSION and set it to the data data member. This overwrites any passed data.
            $this->setSessionDataToDataArray($index);

            # Set any POST values to the appropriate data members.
            $this->setPostDataToDataArray();

            # Populate the data members with defaults, passed values, or data saved in SESSION.
            $this->setDataToDataMembers($this->getUserObject());
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /*** End public methods ***/



    /*** private methods ***/

    /**
     * If there are new post data values from POST data, they are set to the appropriate data
     * member (PasswordFormPopulator or User).
     *
     * @access    private
     */
    private function setPostDataToDataArray()
    {
        # Set the Database instance to a variable.
        $db=DB::get_instance();

        try
        {
            # Check if the form has been submitted.
            if(array_key_exists('_submit_check', $_POST) && ((isset($_POST['send']) && ($_POST['send']=='Change Password'))))
            {
                # Set the data array to a local variable.
                $data=$this->getData();

                # Check if there was POST data sent.
                if(isset($_POST['password']))
                {
                    # Clean it up and set it to the data array index.
                    $data['Password']=$db->sanitize($_POST['password']);
                }

                # Check if there was POST data sent.
                if(isset($_POST['password_confirmed']))
                {
                    # Clean it up and set it to the data array index.
                    $data['PasswordConfirmed']=$db->sanitize($_POST['password_confirmed']);
                }
                # Check if there was POST data sent.
                if(isset($_POST['email_password']) && $_POST['email_password']=='checked')
                {
                    # Clean it up and set it to the data array index.
                    $data['EmailPassword']=$_POST['email_password'];
                }
                else
                {
                    $data['EmailPassword']=NULL;
                }
                # Reset the data array to the data member.
                $this->setData($data);
            }
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /*** End private methods ***/
}