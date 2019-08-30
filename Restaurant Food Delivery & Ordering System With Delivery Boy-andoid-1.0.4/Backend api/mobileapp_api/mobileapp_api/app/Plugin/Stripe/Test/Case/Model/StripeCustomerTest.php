<?php
/**
 * Stripe Customer Test
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2011, Jeremy Harris
 * @link http://42pixels.com
 * @package stripe
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Test card: 4242424242424242
 */

App::uses('StripeCustomer', 'Stripe.Model');

/**
 * Stripe Customer Test
 *
 * @package stripe
 * @subpackage Stripe.Test.Model
 */
class StripeCustomerTest extends CakeTestCase {

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Model = new StripeCustomer();
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
 * testCreate
 *
 * @return void
 */
	public function testCreate() {
		$this->Source->Http->response = array(
			'status' => array('code' => 200),
			'body' => json_encode(array(
				'id' => '1234',
			))
		);
		$this->Source->Http->expects($this->once())
			->method('request')
			->with(
				$this->equalTo(array(
					'uri' => array(
						'host' => 'api.stripe.com',
						'scheme' => 'https',
						'path' => '/v1/customers/1234'
					),
					'method' => 'POST',
					'body' => array(
						'email' => 'jeremy@42pixels.com',
						'description' => 'Jeremy Harris',
						'card' => array(
							'number' => '4242424242424242',
							'exp_month' => '11',
							'exp_year' => date('Y', strtotime('next year')),
							'cvc' => '123',
						),
					),
				))
			)
			->will($this->returnValue($this->Source->Http->response['body']));
		$this->Model->create();
		$result = $this->Model->save(array(
			'StripeCustomer' => array(
				'id' => '1234',
				'number' => '4242424242424242',
				'exp_month' => '11',
				'exp_year' => date('Y', strtotime('next year')),
				'cvc' => '123',
				'email' => 'jeremy@42pixels.com',
				'description' => 'Jeremy Harris',
			),
		));
		$this->assertTrue(($result !== false));
	}

/**
 * testUpdate
 *
 * @return void
 */
	public function testUpdate() {
		$this->Source->Http->response = array(
			'status' => array('code' => 200),
			'body' => json_encode(array(
				'id' => '1234',
			))
		);
		$this->Source->Http->expects($this->once())
			->method('request')
			->with(
				$this->equalTo(array(
					'uri' => array(
						'host' => 'api.stripe.com',
						'scheme' => 'https',
						'path' => '/v1/customers/1234'
					),
					'method' => 'POST',
					'body' => array(
						'description' => 'Not Jeremy Harris',
					),
				))
			)
			->will($this->returnValue($this->Source->Http->response['body']));
		$this->Model->id = '1234';
		$result = $this->Model->save(array(
			'StripeCustomer' => array(
				'description' => 'Not Jeremy Harris',
			),
		));
		$this->assertTrue(($result !== false));
	}

/**
 * testDelete
 *
 * @return void
 */
	public function testDelete() {
		$this->Source->Http->response = array(
			'status' => array('code' => 200),
			'body' => json_encode(array(
				'id' => '1234',
			))
		);
		$this->Source->Http->expects($this->once())
			->method('request')
			->with(
				$this->equalTo(array(
					'uri' => array(
						'host' => 'api.stripe.com',
						'scheme' => 'https',
						'path' => '/v1/customers/1234'
					),
					'method' => 'DELETE',
				))
			)
			->will($this->returnValue($this->Source->Http->response['body']));
		$this->Model->delete('1234');
	}

}