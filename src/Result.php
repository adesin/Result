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
	public $error = [];
	
	public $message = [];
	
	/**
	 * @var array Данные результата
	 */
	public $data = [];
	
	/**
	 * @var boolean Статус результата
	 */
	public $success = false;
	
	/**
	 * @var integer Код ответа сервера
	 */
	public $responseCode = null;
	
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
	public function setSuccess(array $data=[]){
		$this->success = true;
		if(!$this->responseCode){
			$this->setResponseCode(200);
		}
		$this->setData($data);
	}
	
	/**
	 * Установка статуса "Ошибка"
	 *
	 * @param string $message Сообщение об ошибке
	 */
	public function setError(string $message, $setErrorCode=false){
		if($setErrorCode===true){
			$this->setResponseCode(400);
		}else{
			$this->setResponseCode(200);
		}
		
		$this->success = false;
		$this->error[] = $message;
	}
	
	/**
	 * Добавление сообщения, не относящегося к статусу "Ошибка"
	 * @param string $message Текст сообщения
	 */
	public function addMessage(string $message){
		$this->message[] = $message;
	}
	
	/**
	 * Метод записывает данные для результата
	 * @param array $data Массив данных
	 */
	public function setData(array $data){
		$this->data = array_merge($this->data, $data);
	}
	
	/**
	 * Метод устанавливает код HTTP ответа сервера
	 * @param $code Код ответа
	 */
	public function setResponseCode ($code) {
		$this->responseCode = $code;
	}
	
	/**
	 * Метод возвращает текст последней ошибки
	 *
	 * @return string Текст ошибки
	 */
	public function lastMessage(){
		$strMessage = $this->success?end($this->message):end($this->error);
		if(!$strMessage) $strMessage = '';
		return $strMessage;
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
			'message' => $this->lastMessage(),
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