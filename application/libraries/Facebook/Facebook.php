<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

// Autoload the required files
require_once( APPPATH . 'libraries/Facebook/vendor/autoload.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/FacebookRedirectLoginHelper.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/FacebookSession.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/FacebookSDKException.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/FacebookRequestException.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/FacebookAuthorizationException.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/FacebookRequest.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/FacebookResponse.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/GraphObject.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/GraphSessionInfo.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/Entities/AccessToken.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/HttpClients/FacebookHttpable.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/HttpClients/FacebookCurlHttpClient.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/HttpClients/FacebookCurl.php' );

use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;

class Facebook
{
    const MAX_YEARS = 10;
    var $ci;
    var $helper;
    var $session;
    var $permissions;

    public function __construct()
    {

        $this->ci = &get_instance();
        $this->permissions = $this->ci->config->item('permissions', 'facebook');


        // Initialize the SDK
        FacebookSession::setDefaultApplication($this->ci->config->item('appId'), $this->ci->config->item('secret'));

        // Create the login helper and replace REDIRECT_URI with your URL
        // Use the same domain you set for the apps 'App Domains'
        // e.g. $helper = new FacebookRedirectLoginHelper( 'http://mydomain.com/redirect' );
        $this->helper = new FacebookRedirectLoginHelper($this->ci->config->item('redirect_url', 'facebook'));


        if ($this->ci->session->userdata('fb_token'))
        {
            $this->session = new FacebookSession($this->ci->session->userdata('fb_token'));

            // Validate the access_token to make sure it's still valid
            try
            {
                if (!$this->session->validate())
                {
                    $this->session = null;
                }
            } catch (Exception $e)
            {
                // Catch any exceptions
                $this->ci->session->unset_userdata('fb_token');
                $this->session = null;
            }
        } else
        {
            // No session exists
            try
            {
                $this->session = $this->helper->getSessionFromRedirect();
            } catch (FacebookRequestException $ex)
            {
                // When Facebook returns an error
            } catch (Exception $ex)
            {
                // When validation fails or other local issues
            }
        }

        if ($this->session)
        {
            $this->ci->session->set_userdata('fb_token', $this->session->getAccessToken());

            $this->session = new FacebookSession($this->session->getAccessToken());
        }
    }

    /**
     * Returns the login URL.
     */
    public function login_url()
    {
        return $this->helper->getLoginUrl($this->permissions);
    }

    /**
     * Returns the current user's info as an array.
     */
    public function get_posts()
    {   

        
        if ($this->session)
        {
            $posts = array();
            /**
             * Retrieve User’s Profile Information
             */
            // Graph API to request user data
            for ($i = 1; $i < Facebook::MAX_YEARS+1; $i++)
            {
                $years_ago_today = date('Y-m-d',strtotime(date("Y-m-d", time()) . " - $i year"));
                
                $request = ( new FacebookRequest($this->session, 'GET', '/me/feed?since='.strtotime($years_ago_today." 00:00:00").'&until='.strtotime($years_ago_today." 23:59:00")));
                $response = $request->execute();
                // Get response as an array
                $posts[]= $response->getGraphObject()->asArray();
            }

            return $posts;
        }
        return false;
    }

}
