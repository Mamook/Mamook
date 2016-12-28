<?php /* framework/application/modules/Form/PasswordFormProcessor.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


# Get the FormValidator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');

# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');


/**
 * PasswordFormProcessor
 *
 * The PasswordFormProcessor Class is used change or update an account password.
 *
 */
class PasswordFormProcessor extends FormProcessor
{
    /*** public methods ***/

    /**
     * processPassword
     *
     * Processes a submitted password.
     *
     * @param    $data                    An array of values to populate the form with.
     * @access    public
     * @return    string
     */
    public function processPassword($data)
    {
        try
        {
            # Bring the alert-title variable into scope.
            global $alert_title;
            # Bring the login variable into scope. Use this User Object instance for user info not submitted in the form.
            global $login;
            # Set the Document instance to a variable.
            $doc=Document::getInstance();
            # Get the PasswordFormPopulator Class.
            require_once Utility::locateFile(MODULES.'Form'.DS.'PasswordFormPopulator.php');

            # Reset the form if the "reset" button was submitted.
            $this->processReset('send', 'password');

            # Instantiate a new instance of PasswordFormPopulator.
            $populator=new PasswordFormPopulator();
            # Populate the form and set the Password data members for this post.
            $populator->populatePasswordForm($data);
            # Set the Populator object to the data member.
            $this->setPopulator($populator);

            # Get the User object from the PasswordFormPopulator object and set it to a variable for use in this method.
            $user_obj=$populator->getUserObject();

            # Set the User's email to a variable.
            $email=$login->getEmail();
            # Set the email password value to a variable.
            $email_password=$populator->getEmailPassword();
            # Set the User's ID to a variable.
            $id=$login->getID();
            # Set the new password to a variable.
            $password=$user_obj->getPassword();
            # Set the password confirmation to a variable.
            $password_confirmed=$populator->getPasswordConfirmed();

            # Check if the form has been submitted.
            if(array_key_exists('_submit_check', $_POST) && (isset($_POST['send']) && ($_POST['send']=='Change Password')))
            {
                # Create a session that holds all the POST data (it will be destroyed if it is not needed.)
                $this->setSession();

                # Instantiate FormValidator object
                $fv=new FormValidator();

                # Validate if the display name is empty.
                $empty_password=$fv->validateEmpty('password', 'Please enter a password that is at least 6 characters long and contain at least one number as well as letters. It is good practice to use a mix of CAPITAL and lowercase letters with at least 1 number and/or special characters (ie. !,@,#,$,%,^,&, etc.). For assistance creating a password you may go to: <a href="http://strongpasswordgenerator.com/" target="_blank">StrongPasswordGenerator.com</a>', 6, 64);
                # Check if the password name was not empty.
                if($empty_password===FALSE)
                {
                    $acceptable_password=$fv->validateAlphanum('password', 'Your new password must be at least 6 characters long and contain at least one number as well as letters. It is good practice to use a mix of CAPITAL and lowercase letters with at least 1 number and/or special characters (ie. !,@,#,$,%,^,&, etc.). For assistance creating a password you may go to: <a href="http://strongpasswordgenerator.com/" target="_blank">StrongPasswordGenerator.com</a>');
                }

                # Validate if the password confirmation is empty.
                $empty_password_conf=$fv->validateEmpty('password_confirmed', 'Please confirm your new password.', 6, 64);

                # Validate that the password and password confirmation matches.
                if(($empty_password===FALSE)&&($empty_password_conf===FALSE))
                {
                    if($password!=$password_confirmed)
                    {
                        $fv->setErrors('The passwords you entered did not match. Please try again.');
                    }
                }

                # Check for errors to display so that the script won't go further.
                if($fv->checkErrors()===TRUE)
                {
                    # Create a variable to the error heading.
                    $alert_title='Resubmit the form after correcting the following errors:';
                    # Concatenate the errors to the heading.
                    $error=$fv->displayErrors();
                    # Set the error message to the Document object datamember so that it me be displayed on the page.
                    $doc->setError($error);
                }
                else
                {
                    # Update the User's password.
                    $login->updatePassword($id, $password);

                    $session_message='You password has been updated.';
                    $_SESSION['message']=$session_message;

                    # Check if the USer wanted the password emailed to them.
                    if($email_password=='checked')
                    {
                        $user_obj->sendAccountInfo($email, TRUE);
                    }
                    $doc->redirect(REDIRECT_AFTER_LOGIN);
                }
            }
            return NULL;
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /*** End public methods ***/

    /*** private methods ***/

    /**
     * setSession
     *
     * Creates a session that holds all the POST data (it will be destroyed if it is not needed.)
     *
     * @access    private
     */
    private function setSession()
    {
        try
        {
            # Get the Populator object and set it to a local variable.
            $populator=$this->getPopulator();
            # Get the User object and set it to a local variable.
            $user_obj=$populator->getUserObject();

            # Set the form URL's to a variable.
            $form_url=$populator->getFormURL();
            # Set the current URL to a variable.
            $current_url=FormPopulator::getCurrentURL();
            # Check if the current URL is already in the form_url array. If not, add the current URL to the form_url array.
            if(!in_array($current_url, $form_url)) $form_url[]=$current_url;

            # Create a session that holds all the POST data (it will be destroyed if it is not needed.)
            $_SESSION['form']['password']=
                array(
                    'EmailPassword'=>$populator->getEmailPassword(),
                    'FormURL'=>$form_url
                );
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /*** End private methods ***/
}