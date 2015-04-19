<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


# Get the User Class
require_once Utility::locateFile(MODULES.'User'.DS.'User.php');


/**
 * Login
 *
 * The Login Class is used to login in and out users as well as checking various login privileges.
 */
class Login Extends User
{
	/*** data members ***/

	//protected $email=NULL;
	protected $email_conf=NULL;
	protected $error='';
	//protected $password=NULL;
	protected $password_conf=NULL;
	protected $post_login=NULL;
	protected $recaptcha_error;
	protected $remember=NULL;
	//protected $username=NULL;
	protected $wp_password=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setEmailConf
	 *
	 * Sets the data member $email_conf.
	 *
	 * @param	$email_conf			The User's email.
	 * @access	public
	 */
	public function setEmailConf($email_conf)
	{
		# Check if the value is empty.
		if(!empty($email_conf))
		{
			# Clean it up and set the data member.
			$email_conf=trim($email_conf);
		}
		else
		{
			# Explicitly set it to NULL.
			$email_conf=NULL;
		}
		# Set the data member.
		$this->email_conf=$email_conf;
	} #==== End -- setEmailConf

	/**
	 * setError
	 *
	 *  Sets the data member $error.
	 *
	 *  @param	$error (The error string to set.)
	 *  @access	public
	 */
	public function setError($error)
	{
		$error=trim($error);
		$this->error=$error;
	} #==== End -- setError

	/**
	 * setPasswordConf
	 *
	 * Sets the data member $password_conf.
	 *
	 * @param	$password_conf			The User's password.
	 * @access	public
	 */
	public function setPasswordConf($password_conf)
	{
		# Check if the value is empty.
		if(!empty($password_conf))
		{
			# Clean it up and set the data member.
			$password_conf=trim($password_conf);
		}
		else
		{
			# Explicitly set it to NULL.
			$password_conf=NULL;
		}
		# Set the data member.
		$this->password_conf=$password_conf;
	} #==== End -- setPasswordConf

	/**
	 * setPostLogin
	 *
	 * Sets the data member $post_login.
	 *
	 * @param	$url 		(The url to redirect the User to.)
	 * @access	public
	 */
	public function setPostLogin($url)
	{
		if(!empty($url))
		{
			$this->post_login=trim($url);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->post_login=NULL;
		}
	} #==== End -- setPostLogin

	/**
	 *  setReCaptchaError
	 *
	 *  Sets the data member $recaptcha_error.
	 *
	 *  @param	$error 		(The error string to set.)
	 *  @access	protected
	 */
	protected function setReCaptchaError($error)
	{
		$error=trim($error);
		$this->recaptcha_error=$error;
	} #==== End -- setReCaptchaError

	/**
	 *  setRemember
	 *
	 *  Sets the data member $remember.
	 *
	 *  @param	$remember (TRUE to remember the login.)
	 *  @access	public
	 */
	public function setRemember($remember)
	{
		if($remember!==TRUE)
		{
			$remember===FALSE;
		}
		$this->remember=$remember;
	} #==== End -- setRemember

	/**
	 * setWPPassword
	 *
	 * Sets the data member $wp_password.
	 *
	 * @param	$wp_password			The User's encoded password.
	 * @access	public
	 */
	public function setWPPassword($wp_password)
	{
		# Check if the value is empty.
		if(!empty($wp_password))
		{
			# Clean it up and set the data member.
			$wp_password=trim($wp_password);
		}
		else
		{
			# Explicitly set it to NULL.
			$wp_password=NULL;
		}
		# Set the data member.
		$this->wp_password=$wp_password;
	} #==== End -- setWPPassword

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getEmailConf
	 *
	 * Returns the data member $email_conf.
	 *
	 * @access	public
	 */
	public function getEmailConf()
	{
		return $this->email_conf;
	} #==== End -- getEmailConf

	/**
	 *  getError
	 *
	 *  Returns the data member $error. Returns NULL if empty or not set.
	 *
	 *  @access	public
	 */
	public function getError()
	{
		if(isset($this->error) && !empty($this->error))
		{
			return $this->error;
		}
		else { return NULL; }
	} #==== End -- getError

	/**
	 * getPasswordConf
	 *
	 * Returns the data member $password_conf.
	 *
	 * @access	public
	 */
	public function getPasswordConf()
	{
		return $this->password_conf;
	} #==== End -- getPasswordConf

	/**
	 *  getPostLogin
	 *
	 *  Returns the data member $post_login. Throws an error on failure.
	 *
	 *  @access	public
	 */
	public function getPostLogin()
	{
		return $this->post_login;
	} #==== End -- getPostLogin

	/**
	 *  getReCaptchaError
	 *
	 *  Returns the data member $recaptcha_error.
	 *
	 *  @access	public
	 */
	public function getReCaptchaError()
	{
		return $this->recaptcha_error;
	} #==== End -- getReCaptchaError

	/**
	 *  getRemember
	 *
	 *  Returns the data member $remember.
	 *
	 *  @access	public
	 */
	public function getRemember()
	{
		return $this->remember;
	} #==== End -- getRemember

	/**
	 * getWPPassword
	 *
	 * Returns the data member $wp_password.
	 *
	 * @access	public
	 */
	public function getWPPassword()
	{
		return $this->wp_password;
	} #==== End -- getWPPassword

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 *  isLoggedIn
	 *
	 *  Checks if user is logged in or not. Returns TRUE if logged in, FALSE if not.
	 *
	 *  @param	none
	 *  @access	public
	 *  @return boolean		(TRUE/FALSE)
	 */
	public function isLoggedIn()
	{
		if(!isset($_SESSION['user_logged_in']))
		{
			# Check if we have a cookie
			if(isset($_COOKIE['cookie_id']))
			{
				try
				{
					$this->setID($_COOKIE['cookie_id']);
				}
				catch(Exception $e)
				{
					unset($_COOKIE['user_ip']);
					unset($_COOKIE['athenticate']);
					unset($_COOKIE['cookie_id']);
					return FALSE;
				}
				# Get the User class.
				$id=$this->getID();
				# Set variables
				$password=$this->findPassword($this->findUsername($id));
				$ip=$this->findIP();

				# Let's see if we pass the validation.
				$authenticate=md5($password);
				if(($_COOKIE['authenticate']==$authenticate) && (md5($ip)==$_COOKIE['ip']))
				{
					# Set the sessions so we don't repeat this step over and over again.
					try
					{
						# Get the user's data.
						$this->findUserData();
						# Set variables.
						$display=$this->findDisplayName();
						$fname=$this->findFirstName();
						$lname=$this->findLastName();
						$title=$this->findTitle();
						$registered=$this->findRegistered();
						$last_login=$this->findLastLogin();

						$this->setLoginSessions($id, $display, $password, $fname, $lname, $title, $registered, $last_login, TRUE, TRUE);
						return TRUE;
					}
					catch(Exception $e)
					{
						throw $e;
					}
				}
				else
				{
					unset($_COOKIE['user_ip']);
					unset($_COOKIE['athenticate']);
					unset($_COOKIE['cookie_id']);
					return FALSE;
				}
			}
			else
			{
				return FALSE;
			}
		}
		elseif($_SESSION['user_logged_in']===TRUE)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- isLoggedIn

	/**
	 *  checkLogin
	 *
	 *  Applies restrictions to visitors based on membership and level access
	 *  Also handles cookie based "remember me" feature
	 *
	 *  @param	$levels (The access_level number(s) to accept - ie. '1 2 5')
	 *  @access	public
	 */
	public function checkLogin($levels)
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();

		# Check the user's access.
		$access=$this->checkAccess($levels);

		#  If $access is FALSE, send them to the login page (Prevents login page from looping continuously.).
		if(($access===FALSE) && (strpos(Utility::removeIndex(LOGIN_PAGE), Utility::removeIndex(FULL_URL))===FALSE))
		{
			# Check if the user is logged in already.
			if($this->isLoggedIn()===FALSE)
			{
				# Send them to the login page
				$doc->redirect(REDIRECT_TO_LOGIN);
			}
			else
			{
				# Let the user know why they were redirected.
				$_SESSION['message']='You do not have permission to access that page.';
				# Redirect them.
				$doc->redirect(REDIRECT_AFTER_LOGIN);
			}
		}
		# Prevent login page from being accessed while logged in.
		elseif(($access===TRUE) && (strpos(Utility::removeIndex(LOGIN_PAGE), Utility::removeIndex(FULL_URL))!==FALSE))
		{
			$doc->redirect(REDIRECT_AFTER_LOGIN);
		}
	} #==== End -- checkLogin

	/**
	 *  checkAccess
	 *
	 *  Checks the user's level and compares it to the passed access levels.
	 *
	 *  @param	string	$access_levels (The level number(s) to accept - ie. '1 2 5')
	 *  @access	public
	 */
	public function checkAccess($access_levels, $id=NULL)
	{
		# Split the access level string at spaces(' ') and set each piece to the $level_a array.
		$level_a=explode(' ', $access_levels);

		# Assume access is FALSE. Make the method prove it.
		$access=FALSE;

		# Check if the User is logged in.
		if($this->isLoggedIn()===TRUE)
		{
			try
			{
				# Check the User's level access.
				$levels=$this->findUserLevel($id);

				# Loop through the User's levels.
				foreach($levels as $level)
				{
					# Set the general_level variable to a crazy default that will NEVER match.
					$general_level='Orange Apples';
					# Check if the level is more than one digit.
					if(strlen($level)>1)
					{
						$general_level=substr($level, 0, -1).'0';
					}
					# Check if the User's level is in the level array ($level_a).
					if(in_array($level, $level_a) OR in_array($general_level, $level_a))
					{
						# Grant the User access.
						$access=TRUE;
					}
				}
			}
			catch(Exception $e)
			{
				throw $e;
			}
		}
		return $access;
	} #==== End -- checkAccess

	/**
	 *  isAdmin
	 *
	 *  Determines if the logged in user is an admin
	 *
	 *  @access	public
	 *  @param	string $field (May be the user ID or email. NULL assumes the user is logged in.)
	 *  @return	Boolean
	 */
	public function isAdmin($field=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Assume the User is not and admin. Make the method prove it.
		$admin=FALSE;
		# Get the User's access levels.
		$levels=$this->findUserLevel($field);
		# Split the Admin levels string at spaces(' ') and set the pieces to the admin levels array.
		$admin_levels=explode(' ', ADMIN_USERS);
		# loop throught the User's levels.
		foreach($levels as $level)
		{
			# Check if the User's level is in the admin levels array.
			if(in_array($level, $admin_levels)===TRUE)
			{
				# The User is an Admin.
				$admin=TRUE;
			}
		}
		return $admin;
	} #==== End -- isAdmin

	/**
	 * checkRemember
	 *
	 * Checks if the user selected "remember me" upon logging in. Returns TRUE or FALES.
	 *
	 * @access	public
	 */
	public function checkRemember()
	{
		if($this->getRemember()!==NULL)
		{
			$remember=$this->getRemember();
		}
		else
		{
			# Is the remember value stored in a session?
			if(isset($_SESSION['remember']))
			{
				$this->setRemember($_SESSION['remember']);
				$remember=$this->getRemember();
			}
			else
			{
				$remember=FALSE;
				$_SESSION['remember']=$remember;
				$this->setRemember($remember);
			}
		}
		return $remember;
	} #==== End -- checkRemember

	/**
	 *  setLoginSessions
	 *
	 *  Sets the login sessions.
	 *
	 *  @access	public
	 *  @param	$user_id
	 *  @param	$password
	 *  @param	$remember
	 *  @return	none
	 */
	public function setLoginSessions($user_id=NULL, $display_name=NULL, $password=NULL, $fname=NULL, $lname=NULL, $title=NULL, $registered=NULL, $last_login=NULL, $logged_in=NULL, $remember=FALSE, $secure=FALSE)
	{
		# Check if the user is logged in.
		if($this->isLoggedIn()===TRUE);
		{
			if($user_id===NULL)
			{
				try
				{
					# Get the User's data.
					$this->findUserData();
					$user_id=$this->getID();
					$display_name=$this->getDisplayName();
					$title=$this->getTitle();
					$fname=$this->getFirstName();
					$lname=$this->getLastName();
					$password=$this->getPassword();
					$registered=$this->getRegistered();
					$last_login=$this->getLastLogin();
					$logged_in=TRUE;
					$remember=$this->checkRemember();
				}
				catch(Exception $e)
				{
					throw $e;
					die;
				}
			}
		}
		# Reset the time on the session cookie.
		if(isset($_COOKIE[SESSIONS_NAME]))
		{
			setcookie(SESSIONS_NAME, $_COOKIE[SESSIONS_NAME], time()+(($remember!==TRUE) ? LOGIN_LIFE_SHORT : LOGIN_LIFE), COOKIE_PATH, '.'.DOMAIN_NAME, $secure);
		}
		# Set the User's login sessions.
		$_SESSION['user_id']=$user_id;
		$_SESSION['user_display_name']=$display_name;
		$_SESSION['user_title']=$title;
		$_SESSION['user_fname']=$fname;
		$_SESSION['user_lname']=$lname;
		$_SESSION['user_registered']=$registered;
		$_SESSION['user_last_login']=$last_login;
		$_SESSION['user_logged_in']=$logged_in;
		$_SESSION['remember']=$remember;
		# Do we have "remember me"?
		if($remember===TRUE)
		{
			# Get the User's IP address and encrypt it.
			$ip=md5($this->findIP());
			# Encrypt the password.
			$authenticate=md5($password);
			# Set the cookies.
			setcookie('cookie_id', $user_id, time()+LOGIN_LIFE, COOKIE_PATH, '.'.DOMAIN_NAME);
			setcookie('authenticate', $authenticate, time()+LOGIN_LIFE, COOKIE_PATH, '.'.DOMAIN_NAME);
			setcookie('user_ip', $ip, time()+LOGIN_LIFE, COOKIE_PATH, '.'.DOMAIN_NAME);
		}
	} # ----End setLoginSessions

	/**
	 *  logout
	 *
	 *  Logs the User out.
	 *
	 *  @access	public
	 */
	public function logout()
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();

		# Unset the sessions (all of them - array given)
		unset($_SESSION);
		# Destroy what's left.
		session_destroy();

		# If WordPress is installed, log the user out of WordPress.
		$this->clearWP_Cookies();

		/* Uncomment the following line if you wish to remove all cookies (don't forget to comment or delete the following 2 lines if you decide to use the clearCookies method) */
		$this->clearCookies();
		# setcookie('cookie_id', '', time() -KEEP_LOGGED_IN_FOR, COOKIE_PATH, ".".DOMAIN_NAME);
		# setcookie('authenticate', '', time() -KEEP_LOGGED_IN_FOR, COOKIE_PATH, ".".DOMAIN_NAME);
		# setcookie('ip', '', time() -KEEP_LOGGED_IN_FOR, COOKIE_PATH, ".".DOMAIN_NAME);

		# Redirect the user to the default "logout" page.
		$doc->redirect(REDIRECT_ON_LOGOUT);
		exit;
	} #==== End -- logout

	/**
	 *  capturePostLogin
	 *
	 *  Captures post(after) login data sent from the previous page.
	 *
	 *  @access	public
	 */
	public function capturePostLogin()
	{
		# Create an empty variable to hold post login.
		$post_login=NULL;

		# Is it in a session?
		if(isset($_SESSION['_post_login']) && !empty($_SESSION['_post_login']))
		{
			# Get the post login from a session.
			$post_login=$_SESSION['_post_login'];
		}

		# Is it in POST data?
		if(isset($_POST['_post_login']))
		{
			# Get the post login from POST data (This has precedence over $_SESSION['post_login'].)
			$post_login=$_POST['_post_login'];
		}

		# Get the PayPal Class.
		require_once Utility::locateFile(MODULES.'PayPal'.DS.'PayPal.php');
		# Create a new PayPal object.
		$paypal=new PayPal();
		# If user is coming from a PayPal button, catch the POST data so we can redirect to PayPal after logging in. This has precedence over $_POST['post_login'].
		if($paypal->getPayPalPOST()!==FALSE)
		{
			$post_login=$paypal->getPayPalPOST();
		}
		$this->setPostLogin($post_login);
	} #==== End -- capturePostLogin

	/**
	 *  updateLastLogin
	 *
	 *  Updates the date of the User's last login in the Database.
	 *
	 *  @param	$user_id (The User's ID.)
	 *  @access	public
	 */
	public function updateLastLogin($user_id)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			$db->query('UPDATE `'.DBPREFIX.'users` SET `lastlogin` = '.$db->quote($db->escape(YEAR_MM_DD)).' WHERE `ID` = '.$db->quote($user_id));
		}
		catch(ezDB_Error $e)
		{
			throw new Exception('There was an error updating the user\'s lastlogin date: '.$e->error.', code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
	} #==== End -- updateLastLogin

	/**
	 *  processLogin
	 *
	 *  Checks if the Login has been submitted and processes it.
	 *
	 *  @access	public
	 */
	public function processLogin()
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		global $alert_title;

		if($this->isLoggedIn()===TRUE)
		{
			$pl='http://'.$this->getPostLogin();
			$_SESSION['message']='You are already logged in.';
			$doc->redirect(((empty($pl)) ? REDIRECT_AFTER_LOGIN : $pl));
			die;
		}

		# Check if the form has been submitted.
		if(array_key_exists('_submit_check', $_POST))
		{
			# If the form has been submitted, we don't need some previous data. Unset the post login session data.
			unset($_SESSION['_post_login']);

			# Get the FormValidator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');
			# Instantiate a FormValidator object
			$validate=new FormValidator();

			$empty_username=$validate->validateEmpty('username','Please enter your username.', 5, 64);
			$empty_password=$validate->validateEmpty('password','Please enter your password.');

			# Check for errors.
			if($validate->checkErrors()===TRUE)
			{
				# Display errors
				$error='<h3>Correct the following errors then click "Login":</h3>';
				$error.=$validate->displayErrors();
				$this->setError($error);
			}
			else
			{
				try
				{
					# Capture the POST Data.
					$this->setUsername($db->sanitize($_POST['username'], 2));
					$username=$this->getUsername();
					$password=trim($_POST['password']);
					try
					{
						# Check if the user is in the Database
						$this->findPassword($username);
						if($this->validatePassword($password)!==TRUE)
						{
							$this->setError('The password was incorrect.');
						}
						else
						{
							if($this->findUserData($username)!==FALSE)
							{
								$this->setLoginSessions($this->getID(), $this->getDisplayName(), $this->getPassword(), $this->getFirstName(), $this->getLastName(), $this->getTitle(), $this->getRegistered(), $this->getLastLogin(), TRUE, (isset($_POST['remember']) ? TRUE : FALSE));
								$this->updateLastLogin($this->getID());
								$doc->redirect((($this->getPostLogin()===NULL) ? REDIRECT_AFTER_LOGIN : (('http://'.$this->getPostLogin()==ERROR_PAGE.'404.php') ? REDIRECT_AFTER_LOGIN : 'http://'.$this->getPostLogin())));
							}
							else
							{
								$this->setError('I was unable to retrieve the user "'.$username.'". Please check your username and password and try again. If you are still having trouble, use the "<a href="'.LOGIN_PAGE.'LostPassword/" title="Lost Password">Lost Password</a>" feature.');
							}
						}
					}
					catch(Exception $e)
					{
						if($e->getCode()==E_USER_NOTICE)
						{
							$this->setError('The username: "'.$username.'" was not found in the system.');
						}
						else
						{
							throw $e;
							die;
						}
					}
				}
				catch(Exception $e)
				{
					throw $e;
					die;
				}
			}
		}
	} #==== End -- processLogin

	/**
	 *  resendActivation
	 *
	 *  Resends the activation email originally sent at registration.
	 *
	 *  @access	public
	 */
	public function resendActivation()
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		if($this->isLoggedIn()===TRUE)
		{
			$doc->redirect(REDIRECT_AFTER_LOGIN);
		}

		# Check if the form has been submitted.
		if(array_key_exists('_submit_check', $_POST))
		{
			# Get the FormValidator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');
			# Instantiate a FormValidator object
			$validate=new FormValidator();
			$empty_email=$validate->validateEmpty('email', 'Please enter the email address you entered when you registered at '.DOMAIN_NAME.'.', 4);
			if(($empty_email===FALSE))
			{
				$valid_email=$validate->validateEmail('email', 'Please enter the email address you entered when you registered at '.DOMAIN_NAME.'.');
				if($valid_email===TRUE)
				{
					$email=$db->sanitize($_POST['email'], 2);
					try
					{
						$row=$db->get_row('SELECT `ID`, `display`, `random`, `active` FROM '.DBPREFIX.'users WHERE `email` = '.$db->quote($db->escape($email)).' LIMIT 1');
						if($row!==NULL)
						{
							if($row->active==0)
							{
								# Send the confirmation email.
								$subject="Activation email from ".DOMAIN_NAME;
								$to_address=trim($_POST['email']);
								$message=$row->display.','."<br />\n<br />\n".
								'This email has been sent from <a href="'.APPLICATION_URL.'">'.DOMAIN_NAME.'</a>.'."<br />\n<br />\n".
								'You have received this email because this email address was used during registration for our site.'."<br />\n".
								'If you did not register at '.DOMAIN_NAME.', please disregard this email. You do not need to unsubscribe or take any further action.'."<br />\n<br />\n".
								'---------------------------'."<br />\n".
								' Activation Instructions'."<br />\n".
								'---------------------------'."<br />\n<br />\n".
								'Thank you for registering.'."<br />\n".
								'We require that you "validate" your registration to ensure that the email address you entered was correct. This protects against unwanted spam and malicious abuse.'."\n\n".
								'To activate your account, simply click on the following link:'."<br />\n<br />\n".
								'<a href="'.REDIRECT_TO_LOGIN.'confirm.php?ID='.$row->ID.'&key='.$row->random.'">'.REDIRECT_TO_LOGIN.'confirm.php?ID='.$row->ID.'&key='.$row->random.'</a>'."<br />\n<br />\n".
								'(You may need to copy and paste the link into your web browser).'."<br />\n<br />\n".
								'Once you confirm your status, you may login at <a href="'.REDIRECT_TO_LOGIN.'">'.REDIRECT_TO_LOGIN.'</a>.';
								try
								{
									$doc->sendEmail($subject, $to_address, $message);
									$_SESSION['message']='Activation sent. Please check your email for details. The email may not arrive instantly in your email inbox. Please give it some time. Please make sure to check your "junk mail" folder in case the email gets routed there. After your account is activated, you may sign in to '.DOMAIN_NAME.'. Once signed in, you will be able to access special features and download content.';
									$doc->redirect(REDIRECT_TO_LOGIN);
									exit;
								}
								catch(Exception $e)
								{
									$_SESSION['message']='I couldn\'t send the activation email. Please contact the admin at: <a href="mailto:'.ADMIN_EMAIL.'">'.ADMIN_EMAIL.'</a>';
									$doc->redirect(REDIRECT_TO_LOGIN);
								}
							}
							elseif($row->active==2)
							{
								$_SESSION['message']='That account is suspended.';
								$doc->redirect(DEFAULT_REDIRECT);
							}
							else
							{
								$_SESSION['message']='Your account has already been activated. You simply need to log in.';
								$doc->redirect(REDIRECT_TO_LOGIN);
							}
						}
						else
						{
							$validate->setErrors('The user was not found. Please check the email address you entered.');
						}
					}
					catch(ezDB_Error $ez)
					{
						throw new Exception('There was an error retrieving the "random" field for the user with the email address"'.$email.'" from the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
					}
				}
			}
			# Check for errors.
			if($validate->checkErrors()===TRUE)
			{
				# Display errors
				$error='<h3>Correct the following errors then click "Send":</h3>';
				$error.=$validate->displayErrors();
				$doc->setError($error);
			}
		}
	} #==== End -- resendActivation

	/**
	 *  activateAccount
	 *
	 *  Activates new user account.
	 *
	 *  @access	public
	 */
	public function activateAccount()
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		if($this->isLoggedIn()===TRUE) { $doc->redirect(REDIRECT_AFTER_LOGIN); }

		# Check if there is GET Data and that we have valid ID and random key.
		if((strtoupper($_SERVER['REQUEST_METHOD'])=='GET') && !empty($_GET['ID']) && ($validator->isNumber($_GET['ID'])==TRUE) && (isset($_GET['key']) && (strlen($_GET['key'])==32) && ($validator->isAlphanum($_GET['key'])==TRUE)))
		{
			$id=(int)$_GET['ID'];
			# Get user data from the DB
			$username=$this->findUsername($id);
			if($this->findUserData($username)!==FALSE)
			{
				try
				{
					$row=$db->get_row('SELECT `random` FROM `'. DBPREFIX.'users` WHERE `ID` = '.$db->quote($id).' LIMIT 1');
				}
				catch(Exception $ez)
				{
					throw new Exception('There was an error retrieving the "random" field for '.$username.' from the Database: '.$ez->error.', code: '.$ez->errno.'<br />
					Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
					die;
				}
				if($row !== NULL)
				{
					# Does the random key sent match the one we have in the DB?
					if($row->random!=$_GET['key'])
					{
						$validator->setError('The confirmation key that was generated for this account does not match with the one entered!');
						$doc->redirect(DEFAULT_REDIRECT);
						exit;
					}
					# Does the user have "inactive" status (0)?
					elseif(($this->getActive()===0))
					{
						try
						{
							# Update user status to "active" (1)
							$update=$db->query('UPDATE `'.DBPREFIX.'users` SET `active` = '.$db->quote(1).' WHERE `ID` = '.$db->quote($id).' LIMIT 1');
							# Do we send them somewhere else after confirming them?
							if(REDIRECT_AFTER_CONFIRMATION==TRUE)
							{
								# Log the user in.
								$this->setLoginSessions($id, $this->findDisplayName(), $this->findPassword(), $this->findFirstName(), $this->findLastName(), $this->findTitle(), $this->findRegistered(), $this->findLastLogin(), TRUE);
								$_SESSION['message']='Congratulations! You just confirmed your registration with '.DOMAIN_NAME.'!<br />
								You are now signed in and ready to enjoy the site.<br />
								Being signed in allows you to access special content and downloads!';
								$doc->redirect(REDIRECT_AFTER_LOGIN);
							}
							# They're not logging in and redirecting to some type of member's page, let's give them a nice message and send them to the login page.
							else
							{
								$_SESSION['message']='Congratulations! You just confirmed your registration with '.DOMAIN_NAME.'!<br />
								You may now sign in and enjoy the site.<br />
								Being signed in allows you to access special content and downloads!';
								$doc->redirect(REDIRECT_TO_LOGIN);
							}
						}
						catch(ezDB_Error $ez)
						{
							throw new Exception('There was an error activating '.$username.'\'s account in the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
						}
						catch(Exception $e)
						{
							throw $e;
						}
					}
					elseif($this->getActive()===1)
					{
						$_SESSION['message']='You have already been confirmed!<br />
						All you need to do is sign in.';
						$doc->redirect(REDIRECT_TO_LOGIN);
					}
				}
				else
				{
					$_SESSION['message']='User not found!';
					$doc->redirect(DEFAULT_REDIRECT);
				}
			}
			else
			{
				$_SESSION['message']='User not found!';
				$doc->redirect(DEFAULT_REDIRECT);
			}
		}
		else
		{
			$_SESSION['message']='There was an error processing your activation. Please copy the the activation link that was sent to you in your email and paste it into your browser if clicking on the link isn\'t working. If you are still having issues, write to the <a href="'.APPLICATION_URL.'webSupport/" title="Write to webSupport.">webmaster by clicking here</a>. Please give details as to what you are seeing (or not seeing) and any errors that may be displayed.';
			$doc->redirect(DEFAULT_REDIRECT);
		}
	} #==== End -- activateAccount

	/**
	 *  sendAccountInfo
	 *
	 *  Sends account info in an email to the user.
	 *
	 *  @access	public
	 */
	public function sendAccountInfo()
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		if($this->isLoggedIn()===TRUE) { $doc->redirect(REDIRECT_AFTER_LOGIN); }

		# Check if the form has been submitted.
		if(array_key_exists('_submit_check', $_POST))
		{
			# Get the FormValidator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');
			# Instantiate a FormValidator object
			$validate=new FormValidator();
			$empty_email=$validate->validateEmpty('email', 'Please enter the email address you entered when you registered at '.DOMAIN_NAME.'.', 4);
			if(($empty_email===FALSE))
			{
				$valid_email=$validate->validateEmail('email', 'Please enter the email address you entered when you registered at '.DOMAIN_NAME.'.');
				if($valid_email===TRUE)
				{
					$email=$db->sanitize($_POST['email'], 2);
					try
					{
						$row=$db->get_row('SELECT `password`, `display`, `username` FROM '.DBPREFIX.'users WHERE `email` = '.$db->quote($db->escape($email)).' LIMIT 1');
						if($row!==NULL)
						{
							require_once Utility::locateFile(MODULES.'Encryption/Encryption.php');
							$encrypt=new Encryption(MYKEY);
							$password=$encrypt->deCodeIt($row->password);
							# Send the confirmation email.
							$subject="Important email from ".DOMAIN_NAME;
							$to_address=trim($_POST['email']);
							$message=$row->display.','."<br />\n<br />\n".
							'This email has been sent from <a href="'.APPLICATION_URL.'">'.DOMAIN_NAME.'</a>.'."<br />\n<br />\n".
							'You have received this email because this email address was used during registration for our site.'."<br />\n".
							'If you did not register at '.DOMAIN_NAME.', please disregard this email. You do not need to unsubscribe or take any further action.'."<br />\n<br />\n".
							'---------------------------'."<br />\n".
							' Account Info'."<br />\n".
							'---------------------------'."<br />\n<br />\n".
							'You or someone at this email address has requested your password for <a href="'.APPLICATION_URL.'">'.DOMAIN_NAME.'</a>.'."<br />\n".
							'Your username is: <strong>'.$row->username.'</strong>'."<br />\n\r".
							'Your password is: <strong>'.$password.'</strong>'."<br />\n\r<br />\n\r".
							'You may login at <a href="'.REDIRECT_TO_LOGIN.'">'.REDIRECT_TO_LOGIN.'</a>.';
							try
							{
								$doc->sendEmail($subject, $to_address, $message);
								$_SESSION['message']='Account info sent. Please check your email for details. The email may not arrive instantly in your email inbox. Please give it some time. Please make sure to check your "junk mail" folder in case the email gets routed there. After your account is activated, you may sign in to '.DOMAIN_NAME.'. Once signed in, you will be able to access special features and download content.';
								$doc->redirect(REDIRECT_TO_LOGIN);
							}
							catch(Exception $e)
							{
								$_SESSION['message']='I couldn\'t send the activation email. Please contact the admin at: <a href="mailto:'.ADMIN_EMAIL.'">'.ADMIN_EMAIL.'</a>';
								$doc->redirect(REDIRECT_TO_LOGIN);
							}
						}
						else
						{
							$validate->setErrors('The user was not found. Please check the email address you entered.');
						}
					}
					catch(ezDB_Error $ez)
					{
						throw new Exception('There was an error retrieving the "random" field for the user with the email address"'.$email.'" from the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
					}
				}
			}
			# Check for errors.
			if($validate->checkErrors()===TRUE)
			{
				# Display errors
				$error='<h3>Correcting the following errors then click "Send":</h3>';
				$error.=$validate->displayErrors();
				$doc->setError($error);
			}
		}
	} #==== End -- sendAccountInfo

	/**
	 * changePassword
	 *
	 * Changes the User's password.
	 *
	 * @access	public
	 */
	public function changePassword($id=NULL)
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Check if the form has been submitted.
		if(array_key_exists('_submit_check', $_POST))
		{
			# Check if the user's ID was passed.
			if(!empty($id))
			{
				$user=new User();
				$user->setID($id);
				$id=$user->getID();
				$message_pre='The';
			}
			else
			{
				$id=$this->findUserID();
				$message_pre='Your';
			}
			# Get the FormValidator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');
			# Instantiate a FormValidator object
			$validate=new FormValidator();

			# Clean the POST Data.
			$password=trim($_POST['password']);
			$password_conf=trim($_POST['confirmed_password']);

			$empty_password=$validate->validateEmpty('password', 'Please enter a password that is at least 6 characters long and contain at least one number as well as letters. It is good practice to use a mix of CAPITAL and lowercase letters with at least 1 number and/or special characters (ie. !,@,#,$,%,^,&, etc.). For assistance creating a password you may go to: <a href="http://strongpasswordgenerator.com/" target="_blank">StrongPasswordGenerator.com</a>', 6, 64);

			if($empty_password===FALSE)
			{
				$acceptable_password=$validate->validateAlphanum('password', 'Your new password must be at least 6 characters long and contain at least one number as well as letters. It is good practice to use a mix of CAPITAL and lowercase letters with at least 1 number and/or special characters (ie. !,@,#,$,%,^,&, etc.). For assistance creating a password you may go to: <a href="http://strongpasswordgenerator.com/" target="_blank">StrongPasswordGenerator.com</a>');
			}

			$empty_password_conf=$validate->validateEmpty('confirmed_password', 'Please confirm your new password.', 6, 64);

			if(($empty_password===FALSE)&&($empty_password_conf===FALSE))
			{
				if($password!=$password_conf)
				{
					$validate->setErrors('The passwords you entered did not match. Please try again.');
				}
			}

			# Check for errors.
			if($validate->checkErrors()===TRUE)
			{
				# Display errors
				$error='<h3>Resubmit the form after correcting the following errors:</h3>';
				$error.=$validate->displayErrors();
				$doc->setError($error);
			}
			else
			{
				# If WordPress is installed add the user the the WordPress users table.
				if(WP_INSTALLED===TRUE)
				{
					# Format the password
					$wp_password=$this->ecodeWP_Password($password);
					$username=$this->findUsername($id);
					try
					{
						# Get the WordPressUser class.
						require_once Utility::locateFile(MODULES.'User'.DS.'WordPressUser.php');
						# Instantiate a new WordPressUser object.
						$wp_user=new WordPressUser();
						# Update the WordPress password.
						$wp_user->updateWP_Password($username, $wp_password);
					}
					catch(ezDB_Error $ez)
					{
						throw new Exception('There was an error updating the WordPress password for "'.$username.'" into the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
					}
				}
				# Get the Encryption Class.
				require_once Utility::locateFile(MODULES.'Encryption'.DS.'Encryption.php');
				# Instantiate a new Encryption object.
				$encrypt=new Encryption(MYKEY);
				$encrypted_password=$encrypt->enCodeIt($password);
				try
				{
					$update_password=$db->query('UPDATE `'.DBPREFIX.'users` SET `password` = '.$db->quote($db->escape($encrypted_password)).' WHERE `ID` = '.$db->quote($id).' LIMIT 1');
				}
				catch(ezDB_Error $ez)
				{
					throw new Exception('There was an error updating the password for user: '.$username.' in the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
				}
				# Create an empty variable to hold any extra text to include in the message to the User.
				$message='';
				if(isset($_POST['email_password'])&&($_POST['email_password']=='on'))
				{
					$to=$this->findEmail($id);
					$to=htmlspecialchars_decode($to, ENT_QUOTES);
					$subject='Important information about your '.DOMAIN_NAME.' account.';
					$body='Per your request, your password has been changed to: '.$password.''."<br />\n<br />\n";
					$body.='You may log in to your account at <a href="'.REDIRECT_TO_LOGIN.'">'.REDIRECT_TO_LOGIN.'</a>'."<br />\n";
					$sent=$doc->sendEmail($subject, $to, $body);
					# Check if the email was successfully sent.
					if($sent===TRUE)
					$message=' and an email has been sent to '.$to;
					else
					$message=' but there was an error sending the confirmation email to '.$to;
				}
				$_SESSION['message']=$message_pre.' password was successfully changed'.$message.'.';
			}
		}
	} #==== End -- changePassword

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 *  clearCookies
	 *
	 *  Clears the cookies
	 *  Not used by default but present if needed
	 *
	 *  @access private
	 */
	private function clearCookies()
	{
		# Unset cookies
		if(isset($_SERVER['HTTP_COOKIE']))
		{
			$cookies=explode(';', $_SERVER['HTTP_COOKIE']);
			# Loop through the array of cookies and set them in the past
			foreach($cookies as $cookie)
			{
				$parts=explode('=', $cookie);
				$name=trim($parts[0]);
				setcookie($name, '', time() -LOGIN_LIFE);
				setcookie($name, '', time() -LOGIN_LIFE, COOKIE_PATH, '.'.DOMAIN_NAME);
			}
		}
	} # ----End clearCookies

	/**
	 * validatePassword
	 *
	 * Determines if the passed password matches the encoded password on file.
	 * Returns FALSE if the password didn't match.
	 *
	 * @param	$password (The password to check.)
	 * @access	private
	 */
	private function validatePassword($password)
	{
		$valid=FALSE;
		$real_password=$this->getPassword();
		# Get the Encryption Class.
		require_once Utility::locateFile(MODULES.'Encryption'.DS.'Encryption.php');
		# Instantiate a Encryption object.
		$encrypt=new Encryption(MYKEY);
		$encrypted_password=$encrypt->enCodeIt($password);
		if($encrypted_password==$real_password)
		{
			# The password didn't match.
			$valid=TRUE;
		}
		return $valid;
	} #==== End -- validatePassword

	/**
	 *  randomString
	 *
	 *  Create a Random String (Useful for generating passwords or hashes.)
	 *
	 *  @param	$type 	(The type of random string.  Options: alunum, numeric, nozero, unique)
	 *  @param	$len		(The string length. Default is 8 characters.)
	 *  @access public
	 */
	public function randomString($type='alnum', $len=8)
	{
		switch($type)
		{
			case 'alnum':
			case 'numeric':
			case 'nozero':
				switch($type)
				{
					case 'alnum'	:
						$pool='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						break;
					case 'numeric':
						$pool='0123456789';
						break;
					case 'nozero':
						$pool='123456789';
						break;
				}

				$str='';
				for($i=0; $i < $len; $i++)
				{
					$str.=substr($pool, mt_rand(0, strlen($pool) -1), 1);
				}
				return $str;
				break;
			case 'unique':
				return md5(uniqid(mt_rand()));
				break;
		}
	} # ----End randomString

	/**
	 *  clearWP_Cookies
	 *
	 *  Clears the WordPress cookies
	 *
	 *  @access private
	 */
	private function clearWP_Cookies()
	{
		# If WordPress is installed, clear the cookies.
		if(WP_INSTALLED===TRUE)
		{
			# Unset cookies
			setcookie(AUTH_COOKIE, '', time() -LOGIN_LIFE);
			setcookie(AUTH_COOKIE, '', time() - 31536000, ADMIN_COOKIE_PATH, COOKIE_DOMAIN);
			setcookie(SECURE_AUTH_COOKIE, '', time() -LOGIN_LIFE);
			setcookie(SECURE_AUTH_COOKIE, '', time() - 31536000, ADMIN_COOKIE_PATH, COOKIE_DOMAIN);
			setcookie(AUTH_COOKIE, '', time() -LOGIN_LIFE);
			setcookie(AUTH_COOKIE, '', time() - 31536000, PLUGINS_COOKIE_PATH, COOKIE_DOMAIN);
			setcookie(SECURE_AUTH_COOKIE, '', time() -LOGIN_LIFE);
			setcookie(SECURE_AUTH_COOKIE, '', time() - 31536000, PLUGINS_COOKIE_PATH, COOKIE_DOMAIN);
			setcookie(LOGGED_IN_COOKIE, '', time() -LOGIN_LIFE);
			setcookie(LOGGED_IN_COOKIE, '', time() - 31536000, COOKIEPATH, COOKIE_DOMAIN);
			setcookie(LOGGED_IN_COOKIE, '', time() -LOGIN_LIFE);
			setcookie(LOGGED_IN_COOKIE, '', time() - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN);

			# Old cookies
			setcookie(AUTH_COOKIE, '', time() -LOGIN_LIFE);
			setcookie(AUTH_COOKIE, '', time() - 31536000, COOKIEPATH, COOKIE_DOMAIN);
			setcookie(AUTH_COOKIE, '', time() -LOGIN_LIFE);
			setcookie(AUTH_COOKIE, '', time() - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN);
			setcookie(SECURE_AUTH_COOKIE, '', time() -LOGIN_LIFE);
			setcookie(SECURE_AUTH_COOKIE, '', time() - 31536000, COOKIEPATH, COOKIE_DOMAIN);
			setcookie(SECURE_AUTH_COOKIE, '', time() -LOGIN_LIFE);
			setcookie(SECURE_AUTH_COOKIE, '', time() - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN);

			# Even older cookies
			setcookie(USER_COOKIE, '', time() -LOGIN_LIFE);
			setcookie(USER_COOKIE, ' ', time() - 31536000, COOKIEPATH, COOKIE_DOMAIN);
			setcookie(PASS_COOKIE, '', time() -LOGIN_LIFE);
			setcookie(PASS_COOKIE, ' ', time() - 31536000, COOKIEPATH, COOKIE_DOMAIN);
			setcookie(USER_COOKIE, '', time() -LOGIN_LIFE);
			setcookie(USER_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN);
			setcookie(PASS_COOKIE, '', time() -LOGIN_LIFE);
			setcookie(PASS_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN);

			# Settings and Test Cookies
			setcookie('wp-settings-1', '', time() -LOGIN_LIFE);
			setcookie('wp-settings-1', '', time() -LOGIN_LIFE, COOKIEPATH, COOKIE_DOMAIN);
			setcookie('wp-settings-time-1', '', time() -LOGIN_LIFE);
			setcookie('wp-settings-time-1', '', time() -LOGIN_LIFE, SITECOOKIEPATH, COOKIE_DOMAIN);
			setcookie('wp-settings-time-1', '', time() -LOGIN_LIFE);
			setcookie('wp-settings-time-1', '', time() -LOGIN_LIFE, COOKIEPATH, COOKIE_DOMAIN);
			setcookie('settings', '', time() -LOGIN_LIFE);
			setcookie('settings', '', time() -LOGIN_LIFE, SITECOOKIEPATH, COOKIE_DOMAIN);
			setcookie('wordpress_test_cookie', '', time() -LOGIN_LIFE);
			setcookie('wordpress_test_cookie', '', time() -LOGIN_LIFE, SITECOOKIEPATH, COOKIE_DOMAIN);
			setcookie('wordpress_test_cookie', '', time() -LOGIN_LIFE);
			setcookie('wordpress_test_cookie', '', time() -LOGIN_LIFE, COOKIEPATH, COOKIE_DOMAIN);
		}
	} # ----End clearWP_Cookies

	/**
	 *  ecodeWP_Password
	 *
	 *  Encodes a password for WordPress. A wrapper method for HashPassword from the PasswordHash class.
	 *
	 *	@param	string $password		Optional. Used only for Login->changePassword() method.
	 *  @access private
	 */
	private function ecodeWP_Password($wp_password=NULL)
	{
		# Get the PasswordHash Class.
		require_once Utility::locateFile(MODULES.'Encryption'.DS.'PasswordHash.php');
		# Instantiate a PasswordHash object
		$hasher=new PasswordHash(8, TRUE);
		# If $password param is NOT set.
		if($wp_password===NULL)
		{
			# Get the Wordpress password.
			$wp_password=$this->getWPPassword();
		}
		# Format the password.
		$wp_password=$hasher->HashPassword($wp_password);
		# Set the formatted password.
		$this->setWPPassword($wp_password);
		# Return the password (for backwards compatibility).
		return $this->getWPPassword();
	} # ----End ecodeWP_Password

	/**
	 * createWP_User
	 *
	 * Creates a WordPress user in the WordPress Database tables. Assumes a user was just created in the main users table.
	 *
	 * @access	public
	 */
	public function createWP_User()
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Format the password
		$this->ecodeWP_Password();

		# Get the username.
		$username=$this->getUsername();
		# Get the email address.
		$email=$this->getEmail();
		# Get the password.
		$wp_password=$this->getWPPassword();

		try
		{
			$insert_user=$db->query('INSERT INTO `'.WP_DBPREFIX.'users` (`user_login`, `user_pass`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name`) VALUES ('.
				$db->quote($db->escape($username)).
				', '.$db->quote($db->escape($wp_password)).
				', '.$db->quote($db->escape($username)).
				', '.$db->quote($db->escape($email)).
				', '.$db->quote($db->escape('')).
				', '.$db->quote($db->escape(YEAR_MM_DD_TIME)).
				', '.$db->quote($db->escape('')).
				', '.$db->quote($db->escape('0')).
				', '.$db->quote($db->escape($username)).
				')');
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error inserting the new WordPress user info for "'.$username.'" into the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		$row=$db->get_row('SELECT `ID` FROM `'.WP_DBPREFIX.'users` WHERE `user_login` = '.
				$db->quote($db->escape($username)).' LIMIT 1');

		$wp_user_id=$row->ID;
		$meta_data=array(WP_DBPREFIX.'user_level'=>0, WP_DBPREFIX.'capabilities'=>'a:1:{s:10:"subscriber";b:1;}', 'nickname'=>$username, 'rich_editing'=>'true', 'comment_shortcuts'=>'false', 'admin_color'=>'fresh', 'use_ssl'=>0, 's2_excerpt'=>'excerpt', 's2_format'=>'text');
		try
		{
			foreach($meta_data as $meta_key => $meta_value)
			{
				$insert_user_meta=$db->query('INSERT INTO `'.WP_DBPREFIX.'usermeta` (`user_id`, `meta_key`, `meta_value`) VALUES ('.
					$db->quote($wp_user_id).
					', '.$db->quote($db->escape($meta_key)).
					', '.$db->quote($db->escape($meta_value)).
					')');
			}
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error inserting the new WordPress usermeta info for "'.$username.'" into the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
	} #==== End -- createWP_User

	/*** End private methods ***/

} # End Login class.