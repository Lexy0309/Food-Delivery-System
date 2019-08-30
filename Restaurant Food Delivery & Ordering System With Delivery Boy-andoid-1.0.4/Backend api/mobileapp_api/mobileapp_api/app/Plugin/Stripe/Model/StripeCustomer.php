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
		'address_country' => array('type' => 'string'),
		'email' => array('type' => 'string'),
		'description' => array('type' => 'string'),
		'plan' => array('type' => 'string'),
		'trial_end' => array('type' => 'string'),
		'coupon' => array('type' => 'string')
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'number' => array(
			'notempty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter your credit card number.',
				'required' => true,
				'on' =>'create'
			),
			'credit_card' => array(
				'rule' => array('cc', array('visa', 'mc', 'amex', 'disc', 'jcb')),
				'message' => 'Invalid credit card number.'
			)
		),
		'exp_month' => array(
			'notempty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter your expiration month.',
				'required' => true,
				'on' =>'create'
			),
			'between '=> array(
				'rule' => array('between', 1, 12),
				'message' => 'Please enter a valid month.'
			)
		),
		'exp_year' => array(
			'notempty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter your expiration year.',
				'required' => true,
				'on' =>'create'
			),
			'between '=> array(
				'rule' => array('between', 4, 4),
				'message' => 'Please enter a valid year.'
			)
		),
		'cvc' => array(
			'notempty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter your CVC.',
				'required' => true,
				'on' =>'create'
			),
			'number' => array(
				'rule' => 'numeric',
				'message' => 'Please enter a valid CVC.'
			)
		),
		'address_zip' => array(
			'rule' => array('postal', null, 'us'),
			'message' => 'Please enter a valid zipcode.'
		)
	);

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

}