<?php
class Utility {
	public static function showErrorr($error_text){
		echo $error_text;
		exit(0);
	}
	public static function echoJson($data){
		echo json_encode($data);
	}

	public static function echoMsg($msg_type,$msg_text){
		echo json_encode(array('type'=>$msg_type,'text'=>$msg_text));
	}
	
	public static function retFunc($msg_flag,$msg_text){
		$result['flag'] = $msg_flag;
		$result['func'] = $msg_text;
		return $result;
	}
	
	public static function logInfo($msg_text){
		//$file = fopen("log.txt","w");
		//fwrite($file,$msg_text);
		//fclose($file);
		log_message('debug', 'Some variable was correctly set');
		return $msg_text;
	}

}
