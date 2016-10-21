<?php
/**
 * Stripe Connect strategy for Opauth
 * based on https://stripe.com/docs/connect/authentication,
 *          https://stripe.com/docs/connect/standalone-accounts
 *
 * More information on Opauth: http://opauth.org
 */

class StripeConnectStrategy extends OpauthStrategy {

  /**
   * Compulsory cofig keys, listed as un associative arrays
   */
  public $expects = array('client_id', 'app_secret');

  /**
   * Optional config keys with respocetive default values, listed as associtive arrays
   */
  public $defaults = array(
    'redirect_uri' => '{complete_url_to_strategy}int_callback',
    'scope' => 'read_write',
  );

  /**
   * Auth request
   */
   public function request() {
     $url = 'https://connect.stripe.com/oauth/authorize';
     $params = [
       'client_id' => $this->strategy['client_id'],
       'scope' => $this->strategy['scope'],
       'redirect_uri' => $this->strategy['redirect_uri'],
       'response_type' => 'code',
     ];

     $this->clientGet($url, $params);
   }

   /**
    * Internal callback, after Stripe Connect's OAuth
    */
    public function int_callback(){
      if (array_key_exists('code', $_GET) && !empty($_GET['code'])) {
        $url = 'https://connect.stripe.com/oauth/token';
        $params = [
          'client_secret' => $this->strategy['app_secret'],
          'code' => $_GET['code'],
          'grant_type' => 'authorization_code',
        ];

        $response = $this->serverPost($url, $params, null, $headers);
        $results = json_decode($response, true);

        if(!empty($results) && !array_key_exists('error', $results)) {
            $this->auth = [
              'provider' => 'StripeConnect',
              'uid' => $results['stripe_user_id'],
              'info' => [
                'token_type' => $results['token_type'],
                'livemode' => $results['livemode'],
                'stripe_publish_key' => $results['stripe_publish_key'],
                'scope' => $results['scope'],
              ],
              'credentials' => [
                'token' => $results['access_token'],
                'refresh_token' => $results['refresh_token'],
              ],
              'raw' => $results
            ];
            $this->callback();
        } else {
          $error = [
            'provider' => 'StripeConnect',
            'raw' => $headers,
          ];
          $error['error'] = empty($results['error']) ? 'empty response' : $results['error'];
          $error['description'] = empty($results['error']) ? 'empty description' : $results['description'];
          exit();
          $this->errorCallback($error);
        }

      }
    }
}
