<?php
class BuildingConfig {
	/**
	 * 返回config文件中的配置信息转化为一个Array变量,返回的Array变量的格式如下,返回这个格式方便把客户端数据往客户端发送配置数据
	 *
	 * _return Array
	 */
	public static function toArray()
	{
		return self::$data;	
	}


	private static $data = array(
		/*大厅*/
		'Hall'=>array(
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

		/*小麦地*/
		'Field'=>array(
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
		/*农舍:hut是小屋的意思，我们自己生造了一个单词huut来表示游戏中居民居住的房屋*/
		'Huut'=>array(
			'Name'				=>'Hutt',
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
		/*商店:购买和出售物品.全局有多个，玩家完全可以走商业路线*/
		'Shop'=>array(
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

		/*商店:购买和出售物品.全局有多个，玩家完全可以走商业路线*/
		'Barn'=>array(
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

		/*Pigs:猪圈*/
		'Pigs'=>array(
			'Name'				=>'Pigs',
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
		
		/*Mill:磨坊*/
		'Mill'=>array(
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

	);//END OF $data
}
