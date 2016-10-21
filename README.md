# Opauth-Stripe-Connect

Opauth strategy for Stripe Connect authentication.

Implemented based on https://stripe.com/docs/connect/authentication

## Getting started

1. Install Opauth-Facebook

```shell
cd path_to_opauth/Strategy
git clone https://github.com/Otakumesi/opauth-stripe-connect.git StripeConeect
```

2. Create Stripe Connect application at https://dashboard.stripe.com/account/applications/settings

- Click at 'Connect' Tab
- Select the 'Platform Settings'
- fill in the webSite info and the callback_url
  - Make sure that redirect URI is set to actual OAuth 2.0 callback URL, usually http://path_to_opauth/stripeconnect/int_callback

## Strategy configuration
Required parameters:

```php
<?php
'StripeConnect' =>
  ['client_id' => 'YOUR_STRIPE_CONNECT_CLIENT KEY',
   'app_secret' => 'YOUR_STRIPE_KEY_SECRET']
```

Even though scope is an optional configuration parameter for Opauth-Stripe-Connect, for most cases you would like to explicitly define it.

Refer to [Stripe docs](https://stripe.com/docs) for [Connecting users](https://stripe.com/docs/connect/standalone-accounts#connecting-users) of Stripe Connect..

## Lincense
Opauth-Stripe-Connect is MIT Licensed.  
Copyright © 2016 Otakumesi (https://twitter.com/otakumesi)
