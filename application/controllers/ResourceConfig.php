<?php
class ResourceConfig {

	/**
	 * 返回农场的所有配置类型
	 * @return var
	 */
 	private static $data = array(
			'Cult+'	=>0,	
			'Tech+'	=>0,	
			'Hapy+'	=>0,	
			'Gold-'	=>0,	
			'Cult'	=>1,
			'Tech'	=>2,
			'Hapy'	=>3,
			'Gold'	=>4,
			'Apple'	=>5,
			'Banana'=>5,
			'Wheat' =>5,
			'Corn' =>5,
			'PigFeed' =>5,
	);
	public static function toArray()
	{
		return self::$data;	
	}



}
