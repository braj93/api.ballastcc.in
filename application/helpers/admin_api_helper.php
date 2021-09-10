<?php

/**
* log Info for debugging
*/

if ( ! function_exists('logInfo')) {  

	function logInfo($data) {
		if ( is_array($data) && is_object($data) ) {
			echo "<pre>";
			print_r($data);
			echo "</pre>";
		} else {
			var_dump($data);
		}
	 	die;
	}

}

/**
* Get error messages
*/

if ( ! function_exists('getErrorMessages')) {  

	function getErrorMessages($expectedParams) {

		$CI =& get_instance();
		$post = $CI->post();
		$post = ( !empty($post) && ( count($post) === count($expectedParams) ) ) ? $post : $expectedParams;
		$errors = array();
		// $errorArray = $CI->form_validation->error_array();
		// $errorString = $CI->form_validation->error_string();
		if ( !empty($post) ) {
			foreach ($post as $fieldName => $fieldValue) {
				$error = $CI->form_validation->error($fieldName);
				if (!empty($error)) {
					$errors[trim($fieldName)] = strip_tags($error);
				}
			}
		}
		if ( empty( $errors ) ) {
			return array( 'unknown' => 'Some thing went wrong, please try again later' );
		} else {
			return $errors;
		}

	}

}

/**
* Generate unique token
*/

if ( ! function_exists('generateAccessToken')) {  

	function generateAccessToken($userId) {
		return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 3) . '-' . time() . '-' . $userId;
	}

}

/**
* Set response manually
*/
if ( ! function_exists('setResponseManually')) {  
	function setResponseManually($resp, $httpStatusCode = 200){
		$CI =& get_instance();
		$CI->output
                ->set_status_header($httpStatusCode)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($resp, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                ->_display();
        exit;
	}
}