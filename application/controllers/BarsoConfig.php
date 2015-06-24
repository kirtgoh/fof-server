<?php
class BarsoConfig {
	/**
	 * 返回config文件中的配置信息转化为一个Array变量,返回的Array变量的格式如下,返回这个格式方便把客户端数据往客户端发送配置数据
	 * //因为Litjson的jsonmapper.ToObject不支持双重数字索引嵌套数组解析,所以用'+'把各个工作隔离起来
	 * _return Array
	 */
	public static function toArray()
	{
		return self::$data;	
	}


	private static $data = array(
		/*大厅*/
		'Hall'			=>array(
			'Type'=>'Hall',		'Reqv'=>1,'Maxv'=>1,'Maxn'=>1,'View'=>1,'BuildingName' =>'Hall',			'ActivityName' =>'Null',			'StorageName'  =>'Null','FuncMenu'=>array('Null'),				'EditMenu'=>array('Upgrade+Recycle+Rotate'),		
			'Name'				=>'Hall',
			'ShapeIndex'		=>4,
			'HitPoints'			=>array(1000,1000,1000	,1000	, 1000	,1000	, 1000	, 1000	, 1000	, 1000	),
			'Point'				=>array(
				'hapy'=>array(1,1,2,2,2,4,4,4,4,5),
				'cult'=>array(1,2,2,3,3,4,4,4,4,4),
				'tech'=>array(1,2,2,3,3,4,4,4,4,4),
				'gold'=>array(1,2,2,3,3,4,4,4,4,4),
			),
			'BuildTime'			=>array(30	,30	 ,30	,30		, 30	, 30	, 30	, 30	, 30	, 30	),
			'PuildTime'			=>array(60	,60	 ,60	,60		, 60	, 60	, 60	, 60	, 60	, 60	),

			'BuildCost'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'gold'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'tech'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'BuildGain'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'PuildCost'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'PuildGain'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),

		),
		'Shop'			=>array(
			'Type'=>'Shop',		'Reqv'=>1,'Maxv'=>9,'Maxn'=>1,'View'=>6,'BuildingName' =>'Shop',			'ActivityName' =>'Null',			'StorageName'  =>'Null','FuncMenu'=>array('Null'),				'EditMenu'=>array('Upgrade+Recycle+Rotate'),
			'Name'				=>'Shop',
			'ShapeIndex'		=>4,
			'HitPoints'			=>array(1000,1000,1000	,1000	, 1000	,1000	, 1000	, 1000	, 1000	, 1000	),
			'Point'				=>array(
				'hapy'=>array(1,1,2,2,2,4,4,4,4,5),
				'cult'=>array(1,2,2,3,3,4,4,4,4,4),
				'tech'=>array(1,2,2,3,3,4,4,4,4,4),
				'gold'=>array(1,2,2,3,3,4,4,4,4,4),
			),
			'BuildTime'			=>array(30	,30	 ,30	,30		, 30	, 30	, 30	, 30	, 30	, 30	),
			'PuildTime'			=>array(60	,60	 ,60	,60		, 60	, 60	, 60	, 60	, 60	, 60	),

			'BuildCost'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'gold'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'tech'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'BuildGain'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'PuildCost'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'PuildGain'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
	
		),
		'Barn'			=>array(
			'Type'=>'Storage',	'Reqv'=>1,'Maxv'=>9,'Maxn'=>1,'View'=>7,'BuildingName' =>'Barn',			'ActivityName' =>'Null',			'StorageName'  =>'Null','FuncMenu'=>array('Null'),				'EditMenu'=>array('Upgrade+Recycle+Rotate'),
			'Name'				=>'Barn',
			'ShapeIndex'		=>4,
			'HitPoints'			=>array(1000,1000,1000	,1000	, 1000	,1000	, 1000	, 1000	, 1000	, 1000	),
			'Point'				=>array(
				'hapy'=>array(1,1,2,2,2,4,4,4,4,5),
				'cult'=>array(1,2,2,3,3,4,4,4,4,4),
				'tech'=>array(1,2,2,3,3,4,4,4,4,4),
				'gold'=>array(1,2,2,3,3,4,4,4,4,4),
			),
			'BuildTime'			=>array(30	,30	 ,30	,30		, 30	, 30	, 30	, 30	, 30	, 30	),
			'PuildTime'			=>array(60	,60	 ,60	,60		, 60	, 60	, 60	, 60	, 60	, 60	),

			'BuildCost'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'gold'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'tech'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'BuildGain'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'PuildCost'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'PuildGain'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),


		),
		/*草莓地*/
		'BerryField'	=>array(
			'Type'=>'Field',	'Reqv'=>1,'Maxv'=>1,'Maxn'=>1,'View'=>1,'BuildingName' =>'BerryField',		'ActivityName' =>'YearAcitivity',	'StorageName'  =>'Null','FuncMenu'=>array('WaterField+HarvFied'),'EditMenu'=>array('Upgrade+Recycle+Reseed'),
			'Name'				=>'Field','Type'=>'Field','Maxv'=>9,'View'=>0,'Maxn'=>-1,
			'ShapeIndex'		=>2,
			'HitPoints'			=>array(1000,1000,1000	,1000	, 1000	,1000	, 1000	, 1000	, 1000	, 1000	),
			'Point'				=>array(
				'hapy'=>array(1,1,2,2,2,4,4,4,4,5),
				'cult'=>array(1,2,2,3,3,4,4,4,4,4),
				'tech'=>array(1,2,2,3,3,4,4,4,4,4),
				'gold'=>array(1,2,2,3,3,4,4,4,4,4),
			),
			'BuildTime'			=>array(30	,30	 ,30	,30		, 30	, 30	, 30	, 30	, 30	, 30	),
			'PuildTime'			=>array(60	,60	 ,60	,60		, 60	, 60	, 60	, 60	, 60	, 60	),
			'BuildCost'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'gold'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'tech'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'BuildGain'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'PuildCost'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'PuildGain'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
	
		),

		'Jucier'			=>array(
			'Type'=>'Mill',		'Reqv'=>1,'Maxv'=>1,'Maxn'=>1,'View'=>1,'BuildingName' =>'Mill',			'ActivityName' =>'Null',			'StorageName'  =>'Null','FuncMenu'=>array('Null'),							 'EditMenu'=>array('Upgrade+Recycle+Rotate'),
			'Name'				=>'Mill',
			'ShapeIndex'		=>4,
			'HitPoints'			=>array(1000,1000,1000	,1000	, 1000	,1000	, 1000	, 1000	, 1000	, 1000	),
			'Point'				=>array(
				'hapy'=>array(1,1,2,2,2,4,4,4,4,5),
				'cult'=>array(1,2,2,3,3,4,4,4,4,4),
				'tech'=>array(1,2,2,3,3,4,4,4,4,4),
				'gold'=>array(1,2,2,3,3,4,4,4,4,4),
			),
			'BuildTime'			=>array(30	,30	 ,30	,30		, 30	, 30	, 30	, 30	, 30	, 30	),
			'PuildTime'			=>array(60	,60	 ,60	,60		, 60	, 60	, 60	, 60	, 60	, 60	),

			'BuildCost'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'gold'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'tech'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'BuildGain'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'PuildCost'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'PuildGain'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
		),	
		'Winery'			=>array(
			'Type'=>'Mill',		'Reqv'=>1,'Maxv'=>1,'Maxn'=>1,'View'=>1,'BuildingName' =>'Mill',			'ActivityName' =>'Null',			'StorageName'  =>'Null','FuncMenu'=>array('Null'),							 'EditMenu'=>array('Upgrade+Recycle+Rotate'),
			'Name'				=>'Winery',
			'ShapeIndex'		=>4,
			'HitPoints'			=>array(1000,1000,1000	,1000	, 1000	,1000	, 1000	, 1000	, 1000	, 1000	),
			'Point'				=>array(
				'hapy'=>array(1,1,2,2,2,4,4,4,4,5),
				'cult'=>array(1,2,2,3,3,4,4,4,4,4),
				'tech'=>array(1,2,2,3,3,4,4,4,4,4),
				'gold'=>array(1,2,2,3,3,4,4,4,4,4),
			),
			'BuildTime'			=>array(30	,30	 ,30	,30		, 30	, 30	, 30	, 30	, 30	, 30	),
			'PuildTime'			=>array(60	,60	 ,60	,60		, 60	, 60	, 60	, 60	, 60	, 60	),

			'BuildCost'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'gold'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'tech'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'BuildGain'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'PuildCost'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'PuildGain'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
	
		),

		'Golder'			=>array(
			'Type'=>'Storage',		'Reqv'=>1,'Maxv'=>1,'Maxn'=>1,'View'=>1,'BuildingName' =>'Mill',			'ActivityName' =>'Null',			'StorageName'  =>'Null','FuncMenu'=>array('Null'),							 'EditMenu'=>array('Upgrade+Recycle+Rotate'),
			'Name'				=>'Golder',
			'ShapeIndex'		=>4,
			'HitPoints'			=>array(1000,1000,1000	,1000	, 1000	,1000	, 1000	, 1000	, 1000	, 1000	),
			'Point'				=>array(
				'hapy'=>array(1,1,2,2,2,4,4,4,4,5),
				'cult'=>array(1,2,2,3,3,4,4,4,4,4),
				'tech'=>array(1,2,2,3,3,4,4,4,4,4),
				'gold'=>array(1,2,2,3,3,4,4,4,4,4),
			),
			'BuildTime'			=>array(30	,30	 ,30	,30		, 30	, 30	, 30	, 30	, 30	, 30	),
			'PuildTime'			=>array(60	,60	 ,60	,60		, 60	, 60	, 60	, 60	, 60	, 60	),

			'BuildCost'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'gold'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'tech'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'BuildGain'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'PuildCost'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
			'PuildGain'			=>array(				//0  1    2    3    4    5    6    7    8    9 
				'cult'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
				'hapy'=>array(1000,1200,1400,1600,1800,2000,2200,2400,2600,2800),
			),
	
		),





	);//END OF $data
}
