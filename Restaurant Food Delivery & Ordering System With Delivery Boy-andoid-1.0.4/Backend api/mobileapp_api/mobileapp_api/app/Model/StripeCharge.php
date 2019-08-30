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

 public $path = '/charges';
 
 /*
 public $_schema = array(
		'amount' => array('type' => 'integer'),
		'currency' => array('type' => 'string', 'length' => '3'),
		'card' => array('type' => 'string'),
		'number' => array('type' => 'string'),
		'exp_month' => array('type' => 'string', 'length' => '2'),
		'exp_year' => array('type' => 'string', 'length' => '4'),
		'cvc' => array('type' => 'string'),
		'name' => array('type' => 'string'),
		);
	
	public $formatFields = array(
		'card' => array(
			'number',
			'exp_month',
			'exp_year',
			'cvc',
			'name',
			)
	);
   */ 
   
   
    public $_schema = array(
		'amount' => array('type' => 'integer'),
		'currency' => array('type' => 'string', 'length' => '3'),
		'customer' => array('type' => 'string', 'length' => '10')
		);
   
   
    public function getCustomer() {
        $this->save(array(
            'amount' => '500',
            'currency' => 'usd',
            'card' => array(
                'name' => 'Your Name',
                'number' => '4242424242424242',
                'cvc' => '123',
                'exp_month' => '01',
                'exp_year' => '2020',
            ),
        ));
    }
   
   
   
   public function isChargeSuccessfullyHappened($charge_id){

     return $this->find('all',array(
            'conditions' => array(
                'StripeCharge.id' => $charge_id,
            ),

       ));

		}
   
   
}