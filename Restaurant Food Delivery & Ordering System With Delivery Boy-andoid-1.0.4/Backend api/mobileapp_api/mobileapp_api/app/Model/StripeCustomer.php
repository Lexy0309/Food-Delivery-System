<?php
/**
 * Stripe credit card model
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
 * StripeCustomer
 *
 * @package stripe.models
 */
class StripeCustomer extends StripeAppModel {

/**
 * API path
 *
 * @var string
 */
	public $path = '/customers';

/**
 * Credit Card schema
 *
 * @var array
 */
	public $_schema = array(
		'id' => array('type' => 'integer', 'length' => '12'),
		'email' => array('type' => 'string'),
		'card' => array('type' => 'string'),
		'number' => array('type' => 'string'),
		'exp_month' => array('type' => 'string', 'length' => '2'),
		'exp_year' => array('type' => 'string', 'length' => '4'),
		'cvc' => array('type' => 'string'),
		'name' => array('type' => 'string'),
		'address_line_1' => array('type' => 'string'),
		'address_line_2' => array('type' => 'string'),
		'address_zip' => array('type' => 'string'),
		'address_state' => array('type' => 'string'),
		'address_country' => array('type' => 'string')
	);

/**
 * Validation rules
 *
 * @var array
 */
/**
 * Formats data for Stripe
 *
 * Fields within a key will be moved into that key when sent to Stripe. Everything
 * else will remain intact.
 *
 * @var array
 */
	public $formatFields = array(
		'card' => array(
			'number',
			'exp_month',
			'exp_year',
			'cvc',
			'name',
			'address_line_1',
			'address_1ine_2',
			'address_zip',
			'address_state',
			'address_country'
		)
	);

	 public function getCardDetails($stripe_id){
        return $this->find('all',array(
            'conditions' => array(
                'StripeCustomer.id' => $stripe_id,
            ),

       ));

		}
	

}