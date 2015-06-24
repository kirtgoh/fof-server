<?php
class StorageConfig {

	/**
	 * 返回农场的所有配置类型
	 * @return var
	 */
    public static function getResourceStorage($name)
	{
		return self::$resourceStorage[$name];
	}	
	public static function getStorageTop($name,$level)
	{
		return self::$data[$name]['tops'][$level];
	}
	private static $resourceStorage = array(
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
			'HenFeed' =>5,
			'CowFeed' =>5,
			'SheepFeed' =>5,
	);
	public static function toArray()
	{
		return self::$data;	
	}

	private static $data = array(
		//逻辑仓储，储存游戏有关的逻辑变量,全局就这一个storage,而且它一定是第0个，,maxv为1,top为-1.
		'LogicStorage'=>array(
			'name'=>'LogicStorage',
			'maxv'=>1,
			'kind'=>0,
			'tops'=>array(1000,2000,3000,4000,5000,6000),
			'items'=>array('Cult.','Gold.','Hapy.','Tech.'),
		),
		'GoldStorage'=>array(
			'name'=>'GoldStorage',
			'maxv'=>9,
			'kind'=>1,
			'tops'=>array(1000,2000,3000,4000,5000,6000,7000,8000,9000),
			'items'=>array('Gold'),
		),
		'CultStorage'=>array(
			'name'=>'CultStorage',
			'maxv'=>9,
			'kind'=>2,
			'tops'=>array(1000,2000,3000,4000,5000,6000,7000,8000,9000),
			'items'=>array('Cult'),
		),
		'TechStorage'=>array(
			'name'=>'TechStorage',
			'maxv'=>9,
			'kind'=>3,
			'tops'=>array(1000,2000,3000,4000,5000,6000,7000,8000,9000),
			'items'=>array('Tech'),
		),
		'HapyStorage'=>array(
			'name'=>'HapyStorage',
			'maxv'=>9,
			'kind'=>4,
			'tops'=>array(1000,2000,3000,4000,5000,6000,7000,8000,9000),
			'items'=>array('Hapy'),
		),
		'FoodStorage'=>array(
			'name'=>'FoodStorage',
			'maxv'=>9,
			'kind'=>5,
			'tops'=>array(1000,2000,3000,4000,5000,6000,7000,8000,9000),
			'items'=>array('Apple','Banana','Pear','Wheat','Corn','HenFeed','CowFeed','PigFeed'),
		),
	);


}
