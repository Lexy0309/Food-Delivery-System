<?php
/**
 * Stripe App Test
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2011, Jeremy Harris
 * @link http://42pixels.com
 * @package stripe
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('StripeAppModel', 'Stripe.Model');

/**
 * Stripe App Test
 *
 * @package stripe
 * @subpackage Stripe.Test.Model
 */
class StripeAppTest extends CakeTestCase {

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Model = new StripeAppModel();
		$this->Model->setDataSource('stripe_test');
		$sources = ConnectionManager::enumConnectionObjects();
		$this->skipIf(!in_array('stripe_test', array_keys($sources)), '`stripe_test` db config not found');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Model);
	}

/**
 * testGetStripeError
 *
 * @return void
 */
	public function testGetStripeError() {
		$ds = ConnectionManager::getDataSource('stripe_test');
		$ds->lastError = 'Some error! Ack something broke!';

		$this->assertEqual($this->Model->getStripeError(), $ds->lastError);
	}

}