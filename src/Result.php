<?php
/**
 * Result
 *
 *
 * @author Anton Desin anton.desin@gmail.com
 * @copyright (c) Anton Desin
 * @link https://desin.name
 */

namespace Desin;


class Result {
	
	/**
	 * @var array Массив сообщений об ошибках
	 */
	public $error = array();
	
	/**
	 * @var array|null Данные результата
	 */
	public $data = null;
	
	/**
	 * @var boolean Статус результата
	 */
	public $success = false;
	
	/**
	 * @var integer Код ответа сервера
	 */
	public $responseCode = 200;
	
	/**
	 * Метод проверяет, имеет ли результат статус "Успешно"
	 *
	 * @return boolean
	 */
	public function isSuccess(){
		return $this->success;
	}
	
	/**
	 * Метод проверяет, имеет ли результат статус "Ошибка"
	 *
	 * @return boolean
	 */
	public function isError(){
		return !empty($this->error);
	}
	
	/**
	 * Установка статуса "Успешно"
	 *
	 * @param array|null $data Данные результата
	 * @return void
	 */
	public function setSuccess($data = null){
		$this->success = true;
		if($data) $this->data = $data;
	}
	
	/**
	 * Установка статуса "Ошибка"
	 *
	 * @param string $message Сообщение об ошибке
	 */
	public function setError($message){
		$this->setResponseCode(500);
		
		$this->success = false;
		$this->error[] = $message;
	}
	
	public function setData($data){
		if($data) $this->data = $data;
	}
	
	public function setResponseCode ($code) {
		$this->responseCode = $code;
	}
	
	/**
	 * Метод возвращает текст последней ошибки
	 *
	 * @return string Текст ошибки
	 */
	public function lastError(){
		$strError = end($this->error);
		if(!$strError) $strError = '';
		return $strError;
	}
	
	/**
	 * Метод возвращает все имеющиеся ошибки
	 *
	 * @param boolean $string Если параметр равен true, то метод вернёт ошибкт в виде строки, через запятую
	 * @return array|string
	 */
	public function getErrors($string=false){
		if($string){
			return @implode(', ', $this->error);
		}else{
			return $this->error;
		}
	}
	
	/**
	 * Вернуть содержимое результата в виде массива вида:
	 *	    array(
	 *	        'success' => true|false,
	 *	        'message' => ...,
	 *	        'data' => ...
	 *	    )
	 *
	 * @return array Содержимое результата
	 */
	public function getArray(){
		$result = array(
			'success' => $this->success,
			'message' => $this->lastError(),
			'data' => $this->data,
			'code' => $this->responseCode,
		);
		
		return $result;
	}
	
	/**
	 * Вернуть содержимое результата в формате JSON
	 * Содержимое JSON аналогично результату, возвращаемому методом \Bureau\Site\Result::getArray()
	 *
	 * @return type
	 */
	public function getJSON(){
		return json_encode($this->getArray(), JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);
	}
	
	/**
	 * Вывод результата
	 */
	public function display () {
		http_response_code($this->responseCode);
		
		echo $this->getJSON();
		exit();
	}
}