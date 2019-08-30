<?php
/**
 * Stripe plan model
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2011, Jeremy Harris
 * @link http://42pixels.com
 * @package stripe
 * @subpackage stripe.models
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('StripeAppModel', 'Stripe.Model');

/**
 * StripePlan
 *
 * This model is a bit special because Stripe does not have an API call to
 * modify a plan. Therefore, calling an `update` will fail.
 *
 * @package stripe.models
 */
class StripeCharge extends StripeAppModel {

 public $path = '/charge'; // Specify the API URL pattern

    public function charge($data = null) {
        $this->save(array(
            'amount' => '5',
            'currency' => 'usd',
            'card' => array(
                'name' => 'Ghulam Abbas',
                'number' => '5308175520115781',
                'cvc' => '338',
                'exp_month' => '10',
                'exp_year' => '2018',
            ),
        ));
    }
}