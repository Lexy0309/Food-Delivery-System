<?php
/**
 * Stripe Plan Test
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2011, Jeremy Harris
 * @link http://42pixels.com
 * @package stripe
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('StripePlan', 'Stripe.Model');

/**
 * Stripe Plan Test
 *
 * @package stripe
 * @subpackage Stripe.Test.Model
 */
class StripePlanTest extends CakeTestCase {

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Model = new StripePlan();
		$this->Model->setDataSource('stripe_test');
		$sources = ConnectionManager::enumConnectionObjects();
		$this->skipIf(!in_array('stripe_test', array_keys($sources)), '`stripe_test` db config not found');
		$this->Source = $this->Model->getDataSource('stripe_test');
		$this->Source->Http = $this->getMock('HttpSocket', array('request'));
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
 * testValidation
 *
 * @return void
 */
	public function testValidation() {
		$this->Model->set(array('StripePlan' => array()));
		$this->assertFalse($this->Model->validates());

		$this->assertTrue(array_key_exists('id', $this->Model->validationErrors));
		$this->assertTrue(array_key_exists('amount', $this->Model->validationErrors));
		$this->assertTrue(array_key_exists('currency', $this->Model->validationErrors));
		$this->assertTrue(array_key_exists('interval', $this->Model->validationErrors));
		$this->assertTrue(array_key_exists('name', $this->Model->validationErrors));

		$this->assertFalse(array_key_exists('trial_period_days', $this->Model->validationErrors));
	}

/**
 * testFlow
 *
 * @return void
 */
	public function testFlow() {
		$this->Source->Http->response = array(
			'status' => array('code' => 200),
			'body' => json_encode(array(
				'id' => 'Basic',
				'amount' => '500',
			))
		);
		$this->Source->Http->expects($this->any())
			->method('request')
			->will($this->returnValue($this->Source->Http->response['body']));
		$results = $this->Model->save(array(
			'StripePlan' => array(
				'id' => 'Basic',
				'amount' => 500,
				'currency' => 'usd',
				'interval' => 'month',
				'name' => 'Basic Plan'
			)
		));
		$this->assertTrue(($results !== false));

		$id = $this->Model->id;

		$results = $this->Model->read();
		$this->assertEqual($results['StripePlan']['id'], $id);
		$this->assertEqual($results['StripePlan']['amount'], 500);

		$this->assertTrue($this->Model->delete($id));
	}

}