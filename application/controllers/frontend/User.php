<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct() {
        parent::__construct();
        $this->load->model(array('User_model'));
    	$this->load->helper('string');
    	$this->load->library('pagination');
    }

	public function login()
	{
		$this->form_validation->set_rules('email', 'Email' , 'required|xss_clean');
		$this->form_validation->set_rules('password', 'password' , 'required|xss_clean');

		if($this->form_validation->run())
		{
			$check_email = $this->User_model->check_email($_POST['email']);

			if(!empty($check_email))
			{
				$result = $this->User_model->login($_POST['email'],$_POST['password']);
				if(!empty($result)) {
					$session_data = array(
						'userid' => $result['id'],
						'useremail' => $result['email'],
						'cart_value' => 0
					);
					$this->session->set_userdata($session_data);
					redirect('frontend/products');
				} else {
					$this->session->set_flashdata("error_login", "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>You have entered wrong password.</div>");
					$data['view'] = 'frontend/login';
					$this->load->view('frontend/layout/base_layout',$data);
				}
			}
			else
			{
				$this->session->set_flashdata('message', '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>The email id entered is not registered.</div>');
				$data['view'] = 'frontend/login';
				$this->load->view('frontend/layout/base_layout',$data);
			}


		}
		else
		{
			$data['view'] = 'frontend/login';
			$this->load->view('frontend/layout/base_layout',$data);
		}
		

	}

	public function login_guest() {
		if($_POST) {
			$this->form_validation->set_rules('email', 'Email' , 'required|xss_clean');
			$this->form_validation->set_rules('user_mobile', 'Mobile No.' , 'required|xss_clean');

			$name = '';
			$email = $_POST['email'];
			$mobile = $_POST['user_mobile'];

			$registration_data = array(
				'full_name' => $name,
				'email' => $email,
				'password' => '',
				'user_mobile' => $mobile,
				'user_type' => 'guest',
				'user_created_at' => date('Y-m-d H:i:s'),
				'user_login_at' => date('Y-m-d H:i:s'),
				'ip' => $_SERVER['REMOTE_ADDR']
			);
			$result = $this->User_model->register_user($registration_data);
			$session_data = array(
				'userid' => $result,
				'useremail' => $email,
				'user_mobile' => $mobile,
				'cart_value' => 0
			);
			$this->session->set_userdata($session_data);
			redirect('frontend/products');

		} else {
			$data['view'] = 'frontend/login_guest';
			$this->load->view('frontend/layout/base_layout',$data);
		}
	}

	public function registration() {
		if($_POST) {
			$this->form_validation->set_rules('full_name', 'Name' , 'required|xss_clean');
			$this->form_validation->set_rules('email', 'Email' , 'required|xss_clean');
			$this->form_validation->set_rules('user_mobile', 'Mobile No.' , 'required|xss_clean');

			$name = $_POST['full_name'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$mobile = $_POST['user_mobile'];

			$registration_data = array(
				'full_name' => $name,
				'email' => $email,
				'password' => md5($password),
				'user_mobile' => $mobile,
				'user_type' => 'registered',
				'user_created_at' => date('Y-m-d H:i:s'),
				'user_login_at' => '',
				'ip' => $_SERVER['REMOTE_ADDR']
			);
			$this->User_model->register_user($registration_data);
			redirect('home');

		} else {
			$data['view'] = 'frontend/registration_form';
			$this->load->view('frontend/layout/base_layout',$data);
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		$this->session->set_userdata(array('userid' => '', 'useremail' => '', 'cart_value' => ''));
		redirect('home');
		//exit;
	}

	public function script()
	{
		$unverified = $this->db->get_where('users',array('email_verification_status'=>0))->result_array();
		$fp = fopen(FCPATH.'resources/contact.csv', 'w');
		echo FCPATH.'resources/contact.csv';
		// if(file_exists(FCPATH.'resources/contact.csv')){echo's';exit;}
		foreach ($unverified as $key => $value) {
			fputcsv($fp, array($value['email'],$value['full_name']));
			// pr($value);
		}
		$fo = fclose($fp);
	}

	public function register()
	{
		$this->form_validation->set_rules('full_name', 'Full Name' , 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Email' , 'required|is_unique[users.email]|valid_email|xss_clean');

		$this->form_validation->set_rules('age-month', 'Month' , 'required|xss_clean');
		$this->form_validation->set_rules('age-date', 'Date' , 'required|xss_clean');
		$this->form_validation->set_rules('age-year', 'Year' , 'required|xss_clean');
		$this->form_validation->set_message('is_unique', 'Already registered email,try another.');

		if($this->form_validation->run())
		{

			$profile_id = $this->create_profile_id();
			// echo $profile_id;
			// exit();
			$age = strtotime($_POST['age-date'].'-'.$_POST['age-month'].'-'.$_POST['age-year']);

			$params = array('full_name' => $_POST['full_name'],
    						'email' => $_POST['email'],
    						'password' => $_POST['password'],
    						'age' => $age,
    						'profile_id' => $profile_id,
    						'gender' => $_POST['gender'],
    						'verification_id'=> random_string('alnum', 50),
    						'user_created_at' => date('Y-m-d H:i:s'));
			$verification_id = $params['verification_id'];
			$user_id = $this->User_model->add_user($params);
			// $profile = $this->db->select('profile_id')->from('users')->get()->result();
			// $profile_id =  'EN_'.count($profile);
			// exit();

			//email verification
			if($user_id)
			{
				// $this->load->library('email');
			 //    $config['protocol']     = 'smtp';
			 //    $config['smtp_host']    = 'bh-33.webhostbox.net';
			 //    $config['smtp_port']    = '587';
			 //    $config['smtp_user']    = 'info@easynikah.in';
			 //    $config['smtp_pass']    = 'Tech!1234';
			 //    $config['charset']     = 'utf-8';
			 //    $config['newline']     = "\r\n";
			 //    $config['mailtype']  = 'html'; // or html
			 //    $config['validation']  = TRUE; // bool whether to validate email or not

			 //    $this->email->initialize($config);
				// $this->email->set_newline("\n\r");

				$subject = 'EasyNikah.in Profile Activation';

				// $this->email->from('info@easynikah.in','Admin - Easy Nikah');
				// $this->email->to($params['email']);
				// $this->email->subject('Easy Nikah Profile Verification');
				// $this->email->message($message);



				$message = "As salaamu alaikum wa rehmatullahe wa barakatuhu ".$_POST['full_name']."<br><br>JazakAllahu khairan for registering on EasyNikah.in<br><br>Your Profile ID: ".$profile_id."<br><br>Please <a href='".base_url()."frontend/user/email_verification/".$verification_id."'>click</a> on this link to complete your registration.<br><br><br> Note: Without email verification you wont be able to login in your account to proceed <br><br> Best Regards<br><br>Admin - EasyNikah.in - A Muslim Matrimony Site for Indian Muslims<br><a href='www.easynikah.in'>www.easynikah.in</a>";


				send_email($_POST['email'],$_POST['full_name'],$subject,$message);


				// $this->email->from('info@easynikah.in','Admin - Easy Nikah');
				// $this->email->to($params['email']);
				// $this->email->subject('Easy Nikah Profile Verification');
				// $this->email->message($message);

				// if($this->email->send())
				// {
					// echo "you email was sent";
					// $data['view'] = 'frontend/verification';
					// $this->load->view('frontend/layout/base_layout',$data);
					$this->session->set_flashdata('message', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>An Email has been sent to your mail id for your account registration. Kindly click on verification link to confirm the same.</div>');
					redirect('home');
				// }
				// else
				// {
				// 	$this->session->set_flashdata('message', '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>We have encountered an issue. Please come back tomorrow to register.</div>');
				// 	redirect('frontend/user/register');
				// 	// show_error($this->email->print_debugger());
				// }

			}


			// if($params['gender']=='male')
			// {
			// 	redirect('frontend/groom/edit_profile/'.$user_id);
			// }
			// else if($params['gender']=='female')
			// {
			// 	redirect('frontend/bride/edit_profile/'.$user_id);
			// }
		}
		else
		{
			$data['view'] = 'frontend/register';
			$this->load->view('frontend/layout/base_layout',$data);
		}
	}

	public function ajax_get_all_states($country_id=0)
	{

		$states = $this->Location_model->get_states_by_country_id($country_id);
		// pr($states);
		if(isset($_POST['valid']) && $_POST['valid']==true){
			$states = $this->Location_model->get_valid_states($country_id);
		}
		if(!empty($states))
		{
			$response['rc'] = true;
			$response['states'] = $states;

		}
		else
		{
			$response['rc'] = false;
		}
		echo json_encode($response);
	}

	public function ajax_get_all_cities($state_id=0)
	{
		$cities = $this->Location_model->get_cities_by_state($state_id);
		if(isset($_POST['valid']) && $_POST['valid']==true){
			$cities = $this->Location_model->get_valid_cities($state_id);
		}
		if(!empty($cities))
		{
			$response['rc'] = true;
			$response['cities'] = $cities;

		}
		else
		{
			$response['rc'] = false;
		}
		echo json_encode($response);
	}

	public function ajax_delete_contact_person($contact_person_id=0)
	{
		$result = $this->User_model->delete_user_contact_person($contact_person_id);
		if($result)
		{
			$response['rc'] = true;
		}
		else
		{
			$response['rc'] = false;
		}
		echo json_encode($response);
	}

	public function ajax_delete_family($family_id=0)
	{
		$result = $this->User_model->delete_user_family($family_id);
		if($result)
		{
			$response['rc'] = true;
		}
		else
		{
			$response['rc'] = false;
		}
		echo json_encode($response);
	}

		/* first email start */
		public function ajax_email_contact_person($contact_person_email=0)
	{
	    $this->load->library('session');
	    $this->session->unset_userdata('email_otp');
	    $this->session->unset_userdata('email_otp_status');
	    //echo $email = $this->input->post('contact_person_email');
		//$result = $this->User_model->delete_user_contact_person($contact_person_id);
		$email = urldecode($contact_person_email);
		$email_otp = random_string('alnum', 6);
		$name='Contact Person';
		//$email='dev1.webster@gmail.com';
		$subject = 'Easy Nikah Contact Email Verify';

				$message = "As salaamu alaikum wa rehmatullahe wa barakatuhu  <br><br>Please verify your contact email with OTP : ".$email_otp."<br><br> Best Regards<br>Admin - Easy Nikah";

				send_email($email,$name,$subject,$message);
$result=$email;

		if($result)
		{
			$response['rc'] = true;

			$this->load->library('session');
			$this->session->set_userdata('email_otp', $email_otp);
			$email_otp_check=$this->session->userdata('email_otp');
			$response['email_otp'] = $email_otp_check;
		}
		else
		{
			$response['rc'] = false;
		}
		echo json_encode($response);
	}

	public function ajax_email_otp_contact_person($contact_person_email_otp=0)
	{
	    	$this->session->unset_userdata('email_otp_status');

			$email_otp_check=$this->session->userdata('email_otp');

		$email_otp_user = $contact_person_email_otp;



		if($email_otp_check==$email_otp_user)
		{
			$response['rc'] = true;


			$email_status=1;
	    $this->session->set_userdata('email_otp_status', $email_status);
		$email_otp_status=$this->session->userdata('email_otp_status');
			$response['email_otp_status'] = $email_otp_status;
		}
		else
		{
			$response['rc'] = false;
		}
		echo json_encode($response);
	}

	/* first email end */

	/* second email start */
		public function ajax_email_contact_person_second($contact_person_email=0)
	{
	    $this->load->library('session');
	    $this->session->unset_userdata('second_email_otp');
	    $this->session->unset_userdata('second_email_otp_status');

		$email = urldecode($contact_person_email);
		$email_otp = random_string('alnum', 6);
		$name='Contact Person';
		//$email='dev1.webster@gmail.com';
		$subject = 'Easy Nikah Contact Second Email Verify';

				$message = "As salaamu alaikum wa rehmatullahe wa barakatuhu  <br><br>Please verify your contact email with OTP : ".$email_otp."<br><br> Best Regards<br>Admin - Easy Nikah";

				send_email($email,$name,$subject,$message);
$result=$email;

		if($result)
		{
			$response['rc'] = true;

			$this->load->library('session');
			$this->session->set_userdata('second_email_otp', $email_otp);
			$email_otp_check=$this->session->userdata('second_email_otp');
			$response['second_email_otp'] = $email_otp_check;
		}
		else
		{
			$response['rc'] = false;
		}
		echo json_encode($response);
	}

	public function ajax_email_otp_contact_person_second($contact_person_email_otp=0)
	{
	    	$this->session->unset_userdata('second_email_otp_status');

			$email_otp_check=$this->session->userdata('second_email_otp');

		$email_otp_user = $contact_person_email_otp;



		if($email_otp_check==$email_otp_user)
		{
			$response['rc'] = true;


			$email_status=1;
	    $this->session->set_userdata('second_email_otp_status', $email_status);
		$email_otp_status=$this->session->userdata('second_email_otp_status');
			$response['second_email_otp_status'] = $email_otp_status;
		}
		else
		{
			$response['rc'] = false;
		}
		echo json_encode($response);
	}

	/* second email end */

		/* third email start */
		public function ajax_email_contact_person_third($contact_person_email=0)
	{
	    $this->load->library('session');
	    $this->session->unset_userdata('third_email_otp');
	    $this->session->unset_userdata('third_email_otp_status');

		$email = urldecode($contact_person_email);
		$email_otp = random_string('alnum', 6);
		$name='Contact Person';
		//$email='dev1.webster@gmail.com';
		$subject = 'Easy Nikah Contact Third Email Verify';

				$message = "As salaamu alaikum wa rehmatullahe wa barakatuhu  <br><br>Please verify your contact email with OTP : ".$email_otp."<br><br> Best Regards<br>Admin - Easy Nikah";

				send_email($email,$name,$subject,$message);
$result=$email;

		if($result)
		{
			$response['rc'] = true;

			$this->load->library('session');
			$this->session->set_userdata('third_email_otp', $email_otp);
			$email_otp_check=$this->session->userdata('third_email_otp');
			$response['third_email_otp'] = $email_otp_check;
		}
		else
		{
			$response['rc'] = false;
		}
		echo json_encode($response);
	}

	public function ajax_email_otp_contact_person_third($contact_person_email_otp=0)
	{
	    	$this->session->unset_userdata('third_email_otp_status');

			$email_otp_check=$this->session->userdata('third_email_otp');

		$email_otp_user = $contact_person_email_otp;



		if($email_otp_check==$email_otp_user)
		{
			$response['rc'] = true;


			$email_status=1;
	    $this->session->set_userdata('third_email_otp_status', $email_status);
		$email_otp_status=$this->session->userdata('third_email_otp_status');
			$response['third_email_otp_status'] = $email_otp_status;
		}
		else
		{
			$response['rc'] = false;
		}
		echo json_encode($response);
	}

	/* third email end */

	/* mobile */
	public function ajax_mobile_contact_person($contact_person_mobile=0)
	{
	    $this->load->library('session');
	    $this->session->unset_userdata('mobile_otp');
	    $this->session->unset_userdata('mobile_otp_status');

		$mobile = $contact_person_mobile;
		$mobile_otp = random_string('numeric', 4);

		// send sms
		/*sms gateway username password*/
$username = 'asifdange';
$password = '7996';

/*
* Your phone number, only 10 digit number, i.e. 8107887472 in this case:
*/
$numbers = $mobile;

/*
* your 6 characters Sender ID
*/
$sender = 'ENikah';

/*
* A SMS can contain 160 characters equal 1 credit.
*/
$message = "Your Mobile OTP is ".$mobile_otp;

/*
* for $_GET method please use message in urlencode().
*/
$message = urlencode($message);

/*
* Please see the FAQ regarding HTTPS (port 443) and HTTP (port 80/5567)
*/
$url = "http://ipublicity.co.in/api/pushsms.php";
$port = 80;
$api_url = $url."?username=".urlencode($username)."&password=".urlencode($password)."&sender=". $sender ."&message=". $message."&numbers=".$numbers;

$ch = curl_init( );
curl_setopt ( $ch, CURLOPT_URL, $api_url );
curl_setopt ( $ch, CURLOPT_PORT, $port );
curl_setopt ( $ch, CURLOPT_POST, 1 );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
// Allowing cUrl funtions 20 second to execute
//curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
// Waiting 20 seconds while trying to connect
//curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 20 );
$response_string = curl_exec( $ch );


$response_string;

		$result=$mobile;

		if($result)
		{
			$response['rc'] = true;

			$this->load->library('session');
			$this->session->set_userdata('mobile_otp', $mobile_otp);
			$mobile_otp_check=$this->session->userdata('mobile_otp');
			$response['mobile_otp'] = $mobile_otp_check;
		}
		else
		{
			$response['rc'] = false;
		}
		echo json_encode($response);
	}



	public function ajax_mobile_otp_contact_person($contact_person_mobile_otp=0)
	{
	    	$this->session->unset_userdata('mobile_otp_status');

			$mobile_otp_check=$this->session->userdata('mobile_otp');

		$mobile_otp_user = $contact_person_mobile_otp;



		if($mobile_otp_check==$mobile_otp_user)
		{
			$response['rc'] = true;


			$mobile_status=1;
	    $this->session->set_userdata('mobile_otp_status', $mobile_status);
		$mobile_otp_status=$this->session->userdata('mobile_otp_status');
			$response['mobile_otp_status'] = $mobile_otp_status;
		}
		else
		{
			$response['rc'] = false;
		}
		echo json_encode($response);
	}

	/* whatsapp */
	public function ajax_whatsapp_contact_person($contact_person_whatsapp=0)
	{
	    $this->load->library('session');
	    $this->session->unset_userdata('whatsapp_otp');
	    $this->session->unset_userdata('whatsapp_otp_status');

		$whatsapp = $contact_person_whatsapp;
		$whatsapp_otp = random_string('numeric', 4);

		// send sms
		/*sms gateway username password*/
$username = 'asifdange';
$password = '7996';

/*
* Your phone number, only 10 digit number, i.e. 8107887472 in this case:
*/
$numbers = $whatsapp;

/*
* your 6 characters Sender ID
*/
$sender = 'ENikah';

/*
* A SMS can contain 160 characters equal 1 credit.
*/
$message = "Your Whatsapp OTP is ".$whatsapp_otp;

/*
* for $_GET method please use message in urlencode().
*/
$message = urlencode($message);

/*
* Please see the FAQ regarding HTTPS (port 443) and HTTP (port 80/5567)
*/
$url = "http://ipublicity.co.in/api/pushsms.php";
$port = 80;
$api_url = $url."?username=".urlencode($username)."&password=".urlencode($password)."&sender=". $sender ."&message=". $message."&numbers=".$numbers;

$ch = curl_init( );
curl_setopt ( $ch, CURLOPT_URL, $api_url );
curl_setopt ( $ch, CURLOPT_PORT, $port );
curl_setopt ( $ch, CURLOPT_POST, 1 );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
// Allowing cUrl funtions 20 second to execute
//curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
// Waiting 20 seconds while trying to connect
//curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 20 );
$response_string = curl_exec( $ch );


$response_string;

		$result=$whatsapp;

		if($result)
		{
			$response['rc'] = true;

			$this->load->library('session');
			$this->session->set_userdata('whatsapp_otp', $whatsapp_otp);
			$whatsapp_otp_check=$this->session->userdata('whatsapp_otp');
			$response['whatsapp_otp'] = $whatsapp_otp_check;
		}
		else
		{
			$response['rc'] = false;
		}
		echo json_encode($response);
	}



	public function ajax_whatsapp_otp_contact_person($contact_person_whatsapp_otp=0)
	{
	    	$this->session->unset_userdata('whatsapp_otp_status');

			$whatsapp_otp_check=$this->session->userdata('whatsapp_otp');

		$whatsapp_otp_user = $contact_person_whatsapp_otp;



		if($whatsapp_otp_check==$whatsapp_otp_user)
		{
			$response['rc'] = true;


			$whatsapp_status=1;
	    $this->session->set_userdata('whatsapp_otp_status', $whatsapp_status);
		$whatsapp_otp_status=$this->session->userdata('whatsapp_otp_status');
			$response['whatsapp_otp_status'] = $whatsapp_otp_status;
		}
		else
		{
			$response['rc'] = false;
		}
		echo json_encode($response);
	}


	function create_profile_id()
	{
		$profile = $this->db->query('SELECT id,profile_id FROM users ORDER BY id DESC LIMIT 1')->row_array();
		// $profile = $this->db->select('profile_id')->from('users')->get()->result();

		// echo "<pre>";
		// echo print_r($profile);
		// exit();
		if(empty($profile))
		{
			return 'EN_1';
		}
		else{

			// $id = explode('_',$profile['profile_id']);
			// echo $id['1'];

			// $profile_id = $id['1']+1;

			$profile_id= $profile['id']+1;
			return 'EN_'.$profile_id;
		}
		// echo count($profile);
		// exit();
	}

	function email_verification($id)
	{
		// $profile_id = $this->create_profile_id();

		$email_verification_status = $this->User_model->change_email_status($id);

		if($email_verification_status)
		{



		// $user = $this->User_model->get_user_details($id);
		// $email = $user->email;
		// $name = $user->full_name;

		// $this->load->library('email');
	 //    $config['protocol']     = 'smtp';
	 //    $config['smtp_host']    = 'bh-33.webhostbox.net';
	 //    $config['smtp_port']    = '587';
	 //    $config['smtp_user']    = 'contact@easynikah.in';
	 //    $config['smtp_pass']    = 'Tech!1234';
	 //    $config['charset']     = 'utf-8';
	 //    $config['newline']     = "\r\n";
	 //    $config['mailtype']  = 'html'; // or html
	 //    $config['validation']  = TRUE; // bool whether to validate email or not

	 //    $this->email->initialize($config);
		// $this->email->set_newline("\n\r");


		// $message = $name."<br><br>Alhamdulillah!!! Your profile has been verified successfully. Please login and complete your detailed profile.<br><br>All the services at Easy Nikah are absolutely FREE  and will always be In sha Allah with no charges during registration, search or after you find your match In sha Allah.<br><br>We sincerely hope you find your desired match at Easy Nikah and your search here is fruitful. <br><br>All the very best!!! <br><br> Best Regards<br>Admin - Easy Nikah";



		// $this->email->from('contact@easynikah.in','Admin - Easy Nikah');
		// $this->email->to($email);
		// $this->email->subject('Easy Nikah Profile successfully verified');
		// $this->email->message($message);

		// if($this->email->send())
		// {
			$this->session->set_flashdata('message', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your email has been verified. Please login and complete your profile by filling up the detailed form.</div>');
			redirect('login');

			}
			else
			{
				echo "some error are occured";
			}
		// }
		// else
		// {
		// 	show_error($this->email->print_debugger());
		// }

		// $gender = $user->gender;

		// if($gender == 'male')
		// 	{
		// 		redirect('frontend/groom/edit_profile/'.$user->id);
		// 	}
		// 	else if($params['gender']=='female')
		// 	{
		// 		redirect('frontend/bride/edit_profile/'.$user->id);
		// 	}
	}

	//opening the view of forgot password
	public function forgot_password()
	{
		$data['view'] = 'frontend/forgot_password';
		$this->load->view('frontend/layout/base_layout',$data);
	}

	// sending the forgot password link to given mail id.
	public function change_password_request()
	{
		$email = $this->input->post('email');

		$check_email_exist = $this->User_model->check_email_exist($email);
		$name = $check_email_exist->full_name;
		$count = count($check_email_exist);

		if($count == 1)
		{
			$password_reset_id = random_string('alnum', 50);

			$user = $this->User_model->forgot_password_request($email, $password_reset_id);

			if($user)
			{
				// $this->load->library('email');
			 //    $config['protocol']     = 'smtp';
			 //    $config['smtp_host']    = 'bh-33.webhostbox.net';
			 //    $config['smtp_port']    = '587';
			 //    $config['smtp_user']    = 'info@easynikah.in';
			 //    $config['smtp_pass']    = 'Tech!1234';
			 //    $config['charset']     = 'utf-8';
			 //    $config['newline']     = "\r\n";
			 //    $config['mailtype']  = 'html'; // or html
			 //    $config['validation']  = TRUE; // bool whether to validate email or not

			 //    $this->email->initialize($config);
				// $this->email->set_newline("\n\r");

				$subject = 'Easy Nikah Forgot Password';

				$message = "As salaamu alaikum wa rehmatullahe wa barakatuhu ".$check_email_exist->full_name." <br><br>Please <a href='".base_url()."frontend/user/reset_password/".$password_reset_id."'>click</a> on this link to reset your password <br><br> Best Regards<br>Admin - Easy Nikah";

				send_email($email,$name,$subject,$message);

				// $this->email->from('info@easynikah.in','Admin - Easy Nikah');
				// $this->email->to($email);
				// $this->email->subject('Easy Nikah Forgot Password');
				// $this->email->message($message);

				// if($this->email->send())
				// {
					$this->session->set_flashdata('message', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please check your email for the password reset link</div>');
					redirect('home');
				// }
				// else
				// {
				// 	$this->session->set_flashdata('message', '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>We have encountered an issue. Please try again.</div>');
				// 	redirect('frontend/user/forgot_password');
				// 	// show_error($this->email->print_debugger());
				// }

			}
		}
		else
		{
			$this->session->set_flashdata('message', '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>email id does not exist.</div>');
					redirect('forgot_password');
		}
	}

	//opening the reset password view.
	public function reset_password($id)
	{
		$data['token_id'] = $id;
		$data['view'] = 'frontend/reset_password';
		$this->load->view('frontend/layout/base_layout',$data);
	}

	//reset the password.
	public function reset_password_request()
	{
		$token_id = $this->input->post('token_id');
		$password = $this->input->post('password');

		$reset_password = $this->User_model->reset_password($token_id, $password);
		if($reset_password)
		{
			$this->session->set_flashdata('message', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your password has been successfully reset.</div>');
				redirect('login');
		}
	}

	// showing new profile between the date last login and current login
	public function new_profile($days)
	{
		if($this->session->userdata('userid'))
		{
			$user_id = $this->session->userdata('userid');
			$user = $this->session->userdata('user_detail');
			if($user == 'groom')
			{
				$data['groom'] = $this->Groom_model->get_groom($user_id);
			}
			else if( $user == 'bride')
			{
				$data['bride'] = $this->Bride_model->get_bride($user_id);
			}

			// echo $this->session->userdata('userid');

			$user_gender = $this->User_model->get_user_gender($user_id);
			if($user_gender->gender == 'male')
			{
				$gender_condition = 'female';
			}
			if($user_gender->gender == 'female')
			{
				$gender_condition = 'male';
			}
			$from_date = date('Y-m-d H:i:s', strtotime('-'.$days.' days'));

			$current_date =  date('Y-m-d H:i:s');

			$count = count($this->User_model->get_new_users_count($current_date,$from_date,$gender_condition));

			if($count > 0)
			{
				$config = array();
		        $config["base_url"] = base_url() . "new_profile/".$days;
		        $total_row = $count;
		        $config["total_rows"] = $total_row;
		        $config["per_page"] = 12;

		        $config['full_tag_open'] = "<ul class='pagination'>";
		        $config['full_tag_close'] = "</ul>";

		        $config['last_tag_open'] = "<li>";
		        $config['last_tag_close'] = "</li>";

		        $config['next_tag_open'] = "<li>";
		        $config['next_tag_close'] = "</li>";

		        $config['prev_tag_open'] = "<li>";
		        $config['prev_tag_close'] = "</li>";

		        $config['num_tag_open'] = "<li>";
		        $config['num_tag_close'] = "</li>";

		        $config['cur_tag_open'] = "<li class='active'><a>";
		        $config['cur_tag_close'] = "</a></li>";

		        // $config['display_pages'] = FALSE;
		        $config['first_link'] = FALSE;
		        $config['last_link'] = FALSE;

		        $config['next_link'] = 'Next';
		        $config['prev_link'] = 'Previous';

		        $this->pagination->initialize($config);
		        if($this->uri->segment(3))
		        {
		            $page = ($this->uri->segment(3));
		        }
		        else
		        {
		               $page = 0;
		        }

				$result = $this->User_model->get_new_users($current_date,$from_date,$gender_condition,$config["per_page"], $page);
				// echo "<pre>";
				// print_r($result);
				 // echo $this->db->last_query();
				// exit();
				$data['result'] = $result;
			}

			$data['view'] = 'frontend/new_profile';
			$this->load->view('frontend/layout/base_layout',$data);
		}
		else
		{
			redirect('login?new_profile=new_profile');
			exit;
		}
	}

	public function active_profile($days)
	{
		if($this->session->userdata('userid'))
		{
			$user_id = $this->session->userdata('userid');

			$user = $this->session->userdata('user_detail');
			if($user == 'groom')
			{
				$data['groom'] = $this->Groom_model->get_groom($user_id);
			}
			else if( $user == 'bride')
			{
				$data['bride'] = $this->Bride_model->get_bride($user_id);
			}

			$user_gender = $this->User_model->get_user_gender($user_id);

			$from_date = strtotime(date('Y-m-d H:i:s', strtotime('-'.$days.' days')));

			if($user_gender->gender == 'male')
			{
				$gender_condition = 'female';
			}
			if($user_gender->gender == 'female')
			{
				$gender_condition = 'male';
			}

			$current_date =  strtotime(date('Y-m-d H:i:s'));

			$count = count($this->User_model->get_updated_users_count($current_date,$from_date,$gender_condition));

	        if($count > 0)
			{
				$config = array();
		        $config["base_url"] = base_url() . "active_profile/".$days;
		        $total_row = $count;
		        $config["total_rows"] = $total_row;
		        $config["per_page"] = 12;

		        $config['full_tag_open'] = "<ul class='pagination'>";
		        $config['full_tag_close'] = "</ul>";

		        $config['last_tag_open'] = "<li>";
		        $config['last_tag_close'] = "</li>";

		        $config['next_tag_open'] = "<li>";
		        $config['next_tag_close'] = "</li>";

		        $config['prev_tag_open'] = "<li>";
		        $config['prev_tag_close'] = "</li>";

		        $config['num_tag_open'] = "<li>";
		        $config['num_tag_close'] = "</li>";

		        $config['cur_tag_open'] = "<li class='active'><a>";
		        $config['cur_tag_close'] = "</a></li>";

		        // $config['display_pages'] = FALSE;
		        $config['first_link'] = FALSE;
		        $config['last_link'] = FALSE;

		        $config['next_link'] = 'Next';
		        $config['prev_link'] = 'Previous';

		        $this->pagination->initialize($config);
		        if($this->uri->segment(3))
		        {
		            $page = ($this->uri->segment(3));
		        }
		        else
		        {
		               $page = 0;
		        }

				$result = $this->User_model->get_updated_users($current_date,$from_date,$gender_condition,$config["per_page"], $page);
				// echo "<pre>";
				// echo $this->db->last_query();
				// print_r($result);
				// exit();
				$data['result'] = $result;

			}

			$data['view'] = 'frontend/active_profile';
			$this->load->view('frontend/layout/base_layout',$data);
		}
		else
		{
			redirect('login?active_profile=active_profile');
			exit;
		}
	}

	// showing the preferred matches given by user
	public function preferred_matches()
	{
		$user_id = $this->session->userdata('userid');

     	$last_login = $this->User_model->get_user_login($user_id);

        if(!empty($last_login))
		{
			$current_date =  date('d-m-Y h:i A');
		    $last_login_time=$last_login['last_login'];
			// $last_login_date = date('d-m-Y h:i A', $last_login_time);
			$last_login_date = '10-09-2017 04:00 AM';


			$result = $this->User_model->get_preferred_matches($current_date,$last_login_date);
			// echo "<pre>";
			// print_r($result);
			// exit();
			$data['result'] = $result;

		}

		$data['view'] = 'frontend/new_profile';
		$this->load->view('frontend/layout/base_layout',$data);
	}
}
