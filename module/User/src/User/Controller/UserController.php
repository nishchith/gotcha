<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\Mail\Message;

use Core\Uri;

class UserController extends AbstractActionController
{
	protected $userTable;

	public function getUserTable()
	{
		if (!$this->userTable) {
			$sm = $this->getServiceLocator();
			$this->userTable = $sm->get('User\Model\UserTable');
		}
		return $this->userTable;
	}

	public function indexAction()
	{
		return new JsonModel(array(
			'RESPONSE' => "Welcome to DutchLady. You are seeing this message probably beause you have hit a wrong URL! Please contact our service team.",
		));
	}

	public function registerAction()
	{
		try
		{
			$params = json_decode(stripslashes($_POST['DATA']), false, 512, JSON_BIGINT_AS_STRING);

			$userData = array(
				// 'id' 			=> $params->cloudId,
				'firstName' 		=> $params->firstName,
				'lastName' 			=> $params->lastName,
				'gender' 			=> $params->gender,
				'email' 			=> strtolower($params->email),
				'mobile' 			=> $params->mobile,
				'dob' 				=> $params->dob,
				'preganancyStatus' 	=> $params->preganancyStatus,
				'idNumber' 			=> $params->idNumber,
				// 'addressId' 		=> $params->addressId,
				'addressId' 		=> '1000001',
				'username' 			=> strtolower($params->username),  
				'dateAdded' 		=> time()*1000,
				'dateLastUpdated' 	=> time()*1000,
				'active' 			=> true,
			); 

			$id = $this->getUserTable()->insert($userData);

			if ($id)
			{
				$result = array(
					'status' => true,
					'msg' => "User Registered Successfully",
					'cloudId' => $id,
					);
			}
			else
			{
				$result = array(
					'status' => false,
					'msg' => "User Registration Failed",
					);
			}
		}
		catch (\Exception $e)
		{				
			$result = array(
				'status' => false,
				'msg' => "Exception: " .$e->getMessage(),
				);
		}

		return new JsonModel(array(
			'RESPONSE' => $result,
			));
	}

	public function loginAction()
	{
		try 
		{
			$params = json_decode(stripslashes($_POST['DATA']));

			$userData = array(
				'username' 		=> $params->username,
				'accountType' 	=> $params->accountType,
				'validationKey'	=> $params->validationKey,
			); 

			$response = $this->redirect(Uri::USER_LOGIN_API, $userData);

			if ($response->status)
			{
				// 1. Format $data if user data returned

				$data = "User Data";

				$result = array(
					'status' => true,
					'msg' => "Success",
					'data' => $data,
					);
			} 
			else
			{
				$result = array(
					'status' => false,
					'msg' => "Invalid UserId Or Password",
					);
			}
		} 
		catch (\Exception $e) 
		{
			$result = array(
				'status' => false,
				'msg' => "Exception: " .$e->getMessage(),
				);
		}

		return new JsonModel(array(
			'RESPONSE' => $result,
			));
	}

	public function resetPasswordAction()
	{
		try 
		{
			$params = json_decode(stripslashes($_POST['DATA']));

			$userData = array(
				'email'	=> strtolower($params->email),
				); 

			//$isRegistered = $this->getUserTable()->isUserExist($userData['email']))

			if ($isRegistered)
			{
				$password = $this->generatePassword(8,2,2);

				//$response = $this->getUserTable()->updatePassword($password);

				if ($response)
				{
					$this->sendPasswordByEmail($userData['email'], $password);

					$result = array(
						'status' => true,
						'msg' => "Email Sent Successfully. Please Check Your Inbox",
						'data' => $response,
						);
				}
				else
				{
					$result = array(
						'status' => false,
						'msg' => "Please Try Again Later",
						);
				}
			}
			else
			{
				$result = array(
					'status' => false,
					'msg' => "Email Is Not Registered. Please Sign Up!",
					);
			}
		} 
		catch (\Exception $e) 
		{
			$result = array(
				'status' => false,
				'msg' => "Exception: " .$e->getMessage(),
				);
		}

		return new JsonModel(array(
			'RESPONSE' => $result,
			));
	}

	public function changePasswordAction()
	{
		try 
		{
			$params = json_decode(stripslashes($_POST['DATA']));

			$userData = array(
				'username' 		=> $params->username,
				'password' 		=> $params->password,
				'resetPassword'	=> $params->resetPassword,
			); 

			// $response = Call External API

			if ($response->status)
			{
				$result = array(
					'status' => true,
					'msg' => "Password Successfully Changed",
					// 'data' => $data,
					);
			} 
			else
			{
				$result = array(
					'status' => false,
					'msg' => "Invalid UserId Or Password",
					);
			}
		} 
		catch (\Exception $e) 
		{
			$result = array(
				'status' => false,
				'msg' => "Exception: " .$e->getMessage(),
				);
		}

		return new JsonModel(array(
			'RESPONSE' => $result,
			));
	}

	public function checkAclAction()
	{
		// TBD
	}

	public function redirect($url, $data)
	{
		$request = new \Zend\Http\Request();

		// $request->getHeaders()->addHeaders(['Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8']);
		$request->setMethod(\Zend\Http\Request::METHOD_POST);
		$request->setUri($url);

		// $postData = "username={$username}&password={$password}";
		$postData = new JsonModel($data);
		$request->setContent($postData);

		$client = new \Zend\Http\Client();
		$client->setAdapter(new \Zend\Http\Client\Adapter\Curl());

		$response = $client->dispatch($request);

		return $response;
	}

	/**
	 * This method generates random string of given length of 
	 * total length of the string and
	 * total count of charecters, numbers and symbols 
	 * to be included in the string
	 */
	public function generatePassword($l = 8, $c = 0, $n = 0, $s = 0) 
	{
		// get count of all required minimum special chars
		$count = $c + $n + $s;
		$out = '';
		// sanitize inputs; should be self-explanatory
		if(!is_int($l) || !is_int($c) || !is_int($n) || !is_int($s)) {
			trigger_error('Argument(s) not an integer', E_USER_WARNING);
			return false;
		}
		elseif($l < 0 || $l > 20 || $c < 0 || $n < 0 || $s < 0) {
			trigger_error('Argument(s) out of range', E_USER_WARNING);
			return false;
		}
		elseif($c > $l) {
			trigger_error('Number of password capitals required exceeds password length', E_USER_WARNING);
			return false;
		}
		elseif($n > $l) {
			trigger_error('Number of password numerals exceeds password length', E_USER_WARNING);
			return false;
		}
		elseif($s > $l) {
			trigger_error('Number of password capitals exceeds password length', E_USER_WARNING);
			return false;
		}
		elseif($count > $l) {
			trigger_error('Number of password special characters exceeds specified password length', E_USER_WARNING);
			return false;
		}

		// all inputs clean, proceed to build password

		// change these strings if you want to include or exclude possible password characters
		$chars = "abcdefghijklmnopqrstuvwxyz";
		$caps = strtoupper($chars);
		$nums = "0123456789";
		$syms = "!@#$%^&*()-+?";

		// build the base password of all lower-case letters
		for($i = 0; $i < $l; $i++) {
			$out .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}

		// create arrays if special character(s) required
		if($count) {
			// split base password to array; create special chars array
				$tmp1 = str_split($out);
				$tmp2 = array();

			// add required special character(s) to second array
				for($i = 0; $i < $c; $i++) {
					array_push($tmp2, substr($caps, mt_rand(0, strlen($caps) - 1), 1));
				}
				for($i = 0; $i < $n; $i++) {
					array_push($tmp2, substr($nums, mt_rand(0, strlen($nums) - 1), 1));
				}
				for($i = 0; $i < $s; $i++) {
					array_push($tmp2, substr($syms, mt_rand(0, strlen($syms) - 1), 1));
				}

			// hack off a chunk of the base password array that's as big as the special chars array
				$tmp1 = array_slice($tmp1, 0, $l - $count);
			// merge special character(s) array with base password array
				$tmp1 = array_merge($tmp1, $tmp2);
			// mix the characters up
				shuffle($tmp1);
			// convert to string for output
				$out = implode('', $tmp1);
		}

		return $out;
	}

	/**
	 *  Sends SMTP Mail
	 */
	public function sendPasswordByEmail($email, $password)
	{
		$transport = $this->getServiceLocator()->get('mail.transport');
		$message = new Message();
		$this->getRequest()->getServer();  //Server vars
		$message->addTo($email)
				->addFrom('MyHPCL@hpcl.co.in')
				->setSubject('Your password has been changed!')
				->setBody("Your password for MyHPCL Mobile App " . 
					$this->getRequest()->getServer('HTTP_ORIGIN') 
					.'has been changed. Your new password is: ' 
					.$password
				);
		$transport->send($message);     
	}
}
