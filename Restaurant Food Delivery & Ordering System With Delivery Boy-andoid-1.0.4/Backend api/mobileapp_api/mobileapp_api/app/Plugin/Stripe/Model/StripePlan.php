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
class StripePlan extends StripeAppModel {

/**
 * API path
 *
 * @var string
 */
	public $path = '/plans';

/**
 * Plan schema
 *
 * @var array
 */
	public $_schema = array(
		'id' => array('type' => 'string', 'length' => '45'),
		'amount' => array('type' => 'integer'),
		'currency' => array('type' => 'string', 'length' => '3'),
		'interval' => array('type' => 'string', 'length' => '5'),
		'name' => array('type' => 'string'),
		'trial_period_days' => array('type' => 'integer')
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'id' => array(
			'notempty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter an id.',
				'required' => true
			)
		),
		'amount' => array(
			'notempty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter an amount.',
				'required' => true
			),
			'numeric'=> array(
				'rule' => array('numeric'),
				'message' => 'Amount must be a number.',
				'required' => true
			)
		),
		'currency' => array(
			'notempty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a currency type.',
				'required' => true
			),
			'between '=> array(
				'rule' => array('inList', array('usd')),
				'message' => 'Please enter a valid currency code.',
				'required' => true
			)
		),
		'interval' => array(
			'notempty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a billing interval.',
				'required' => true
			),
			'between '=> array(
				'rule' => array('inList', array('month', 'year')),
				'message' => 'Please enter a valid billing interval.',
				'required' => true
			)
		),
		'name' => array(
			'notempty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter an name.',
				'required' => true
			)
		),
		'trial_period_days' => array(
			'notempty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter an trial period.'
			),
			'numeric'=> array(
				'rule' => array('numeric'),
				'message' => 'Trial period must be an amount of days.'
			)
		)
	);

/**
 * No such thing as updating a plan in the Stripe API
 *
 * @return boolean True
 */
	public function beforeSave($options = array()) {
		$this->id = null;
		return true;
	}

}