<?php
/**
 * Stripe datasource
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2011, Jeremy Harris
 * @link http://42pixels.com
 * @package stripe
 * @subpackage stripe.models.datasources
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Imports
 */
App::uses('HttpSocket', 'Network/Http');
App::uses('CakeLog', 'Log');

/**
 * StripSource
 *
 * @package stripe
 * @subpackage stripe.models.datasources
 */
class StripeSource extends DataSource {

/**
 * HttpSocket
 *
 * @var HttpSocket
 */
	public $Http = null;

/**
 * Start quote
 *
 * @var string
 */
	public $startQuote = '';

/**
 * End quote
 *
 * @var string
 */
	public $endQuote = '';

/**
 * Holds last Stripe error
 *
 * @var string
 */
	public $lastError = null;

/**
 * Constructor. Sets API key and throws an error if it's not defined in the
 * db config
 *
 * @param array $config
 */
	public function __construct($config = array()) {
		parent::__construct($config);

		if (empty($config['api_key'])) {
			throw new CakeException('StripeSource: Missing api key');
		}

		$this->Http = new HttpSocket();
	}

/**
 * Creates a record in Stripe
 *
 * @param Model $model The calling model
 * @param array $fields Array of fields
 * @param array $values Array of field values
 * @return boolean Success
 */
	public function create(Model $model, $fields = array(), $values = array()) {
		$request = array(
			'uri' => array(
				'path' => $model->path
			),
			'method' => 'POST',
			'body' => $this->reformat($model, array_combine($fields, $values))
		);
		$response = $this->request($request);
		if ($response === false) {
			return false;
		}
		$model->setInsertId($response['id']);
		$model->id = $response['id'];
		return true;
	}

/**
 * Reads a Stripe record
 *
 * @param Model $model The calling model
 * @param array $queryData Query data (conditions, limit, etc)
 * @param int $recursive Recursive
 * @return mixed `false` on failure, data on success
 */
	public function read(Model $model, $queryData = array(), $recursive = null) {
		// If calculate() wants to know if the record exists. Say yes.
		if ($queryData['fields'] == 'COUNT') {
			return array(array(array('count' => 1)));
		}
		if (empty($queryData['conditions'][$model->alias.'.'.$model->primaryKey])) {
			$queryData['conditions'][$model->alias.'.'.$model->primaryKey] = $model->id;
		}
		$request = array(
			'uri' => array(
				'path' => trim($model->path, '/').'/'.$queryData['conditions'][$model->alias.'.'.$model->primaryKey]
			)
		);
		$response = $this->request($request);
		if ($response === false) {
			return false;
		}
		$model->id = $response['id'];
		return array(
			array(
				$model->alias => $response
			)
		);
	}

/**
 * Updates a Stripe record
 *
 * @param Model $model The calling model
 * @param array $fields Array of fields to update
 * @param array $values Array of field values
 * @param array $conditions Array of conditions
 * @return mixed `false` on failure, data on success
 */
	public function update(Model $model, $fields = array(), $values = array(), $conditions = array()) {
		$data = array_combine($fields, $values);
		if (!isset($data['id'])) {
			$data['id'] = $model->id;
		}
		$id = $data['id'];
		unset($data['id']);
		$request = array(
			'uri' => array(
				'path' => trim($model->path, '/').'/'.$id
			),
			'method' => 'POST',
			'body' => $this->reformat($model, $data)
		);

		$response = $this->request($request);
		if ($response === false) {
			return false;
		}
		$model->id = $id;
		return array($model->alias => $response);
	}

/**
 * Deletes a Stripe record
 *
 * @param Model $model The calling model
 * @param array $conditions Conditions
 * @return boolean Success
 */
	public function delete(Model $model, $conditions = array()) {
		$request = array(
			'uri' => array(
				'path' => trim($model->path, '/').'/'.$conditions[$model->alias.'.'.$model->primaryKey]
			),
			'method' => 'DELETE'
		);
		$response = $this->request($request);
		if ($response === false) {
			return false;
		}
		return true;
	}

/**
 * Submits a request to Stripe. Requests are merged with default values, such as
 * the api host. If an error occurs, it is stored in `$lastError` and `false` is
 * returned.
 *
 * @param array $request Request details
 * @return mixed `false` on failure, data on success
 */
	public function request($request = array()) {
		$this->lastError = null;
		$this->request = array(
			'uri' => array(
				'host' => 'api.stripe.com',
				'scheme' => 'https',
				'path' => '/',
			),
			'method' => 'GET',
		);
		$this->request = Set::merge($this->request, $request);
		$this->request['uri']['path'] = '/v1/' . trim($this->request['uri']['path'], '/');
		$this->Http->configAuth('Basic', $this->config['api_key'], '');

		try {
			$response = $this->Http->request($this->request);
			switch ($this->Http->response['status']['code']) {
				case '200':
					return json_decode($response, true);
				break;
				case '400':
					$error = json_decode($response, true);
					$this->lastError = $error['error']['message'];
					return false;
				break;
				case '402':
					$error = json_decode($response, true);
					$this->lastError = $error['error']['message'];
					return false;
				break;
				default:
					$this->lastError = 'Unexpected error.';
					CakeLog::write('stripe', $this->lastError);
					return false;
				break;
			}
		} catch (CakeException $e) {
			$this->lastError = $e->message;
			CakeLog::write('stripe', $e->message);
		}
	}

/**
 * Formats data for Stripe based on `$formatFields`
 *
 * @param Model $model The calling model
 * @param array $data Data sent by Cake
 * @return array Stripe-formatted data
 */
	public function reformat($model, $data) {
		if (!isset($model->formatFields)) {
			return $data;
		}
		foreach ($data as $field => $value) {
			foreach ($model->formatFields as $key => $fields) {
				if (in_array($field, $fields)) {
					$data[$key][$field] = $value;
					unset($data[$field]);
				}
			}
		}
		return $data;
	}

/**
 * For checking if record exists. Return COUNT to have read() say yes.
 *
 * @param Model $Model
 * @param string $func
 * @return true
 */
	public function calculate(Model $Model, $func) {
		return 'COUNT';
	}

/**
 * Don't use internal caching
 *
 * @return null
 */
	public function listSources($data = null) {
		return null;
	}

/**
 * Descibe with schema. Check the model or use nothing.
 *
 * @param Model $Model
 * @return array
 */
	public function describe($Model) {
		if (isset($Model->_schema)) {
			return $Model->_schema;
		} else {
			return null;
		}
	}

}