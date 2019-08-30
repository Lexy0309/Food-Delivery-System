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