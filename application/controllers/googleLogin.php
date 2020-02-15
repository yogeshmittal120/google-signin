<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class googleLogin extends CI_Controller {

public function __construct()
{
	parent::__construct();
	require_once APPPATH.'third_party/src/Google_Client.php';
	require_once APPPATH.'third_party/src/contrib/Google_Oauth2Service.php';
}
	
	public function index()
	{
		$this->load->view('login');
	}
	
	public function google_login()
	{
		$clientId = '305828239458-at21lpa5987ihiv2oavom3qn7m9mj48m.apps.googleusercontent.com'; //Google client ID
		$clientSecret = '986FxcTHsj6jzhiNyWl7mV96'; //Google client secret
		$redirectURL = base_url() . 'googleLogin/google_login';
		
		//Call Google API
		$gClient = new Google_Client();
		$gClient->setApplicationName('gennext');
		$gClient->setClientId($clientId);
		$gClient->setClientSecret($clientSecret);
		$gClient->setRedirectUri($redirectURL);
		
		$google_oauthV2 = new Google_Oauth2Service($gClient);
		
		if(isset($_GET['code']))
		{
			
			$gClient->authenticate($_GET['code']);
			$_SESSION['token'] = $gClient->getAccessToken();
			header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
		}

		if (isset($_SESSION['token'])) 
		{
			
			$gClient->setAccessToken($_SESSION['token']);
		}
		
		if ($gClient->getAccessToken()) {
            $userProfile = $google_oauthV2->userinfo->get();
			echo "<pre>";
			print_r($userProfile);
			
			
        } 
		else 
		{
			echo "<pre>";
		// print_r($google_oauthV2);
		// die;
			$url = $gClient->createAuthUrl();
			print_r($url);
			// die;
		    header("Location: $url");
            exit;
        }
	}	
}
