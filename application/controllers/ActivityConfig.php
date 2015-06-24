<?php
class ActivityConfig {

	/**
	 * 返回农场的所有配置类型
	 * @return var
	 */
    public static function get($type)
	{
		return self::$data[$type];
	}	


	public static function toArray()
	{
		return self::$data;	
	}
	private static $data = array(
		//这个活动维持农场时间的运转,它模拟了每年月份的进度。这个Activity独立存在在游戏全局背景中，唯一份，不依赖与任何物体(e.g. BarsoObject)
		'YearActivity'=>array(
			'name'		=>	'YearActivity',
			'mode'		=>	0,//LOOP
			'iniq'		=>	1,//这个活动的初始队列大小
			'maxq'		=>	1,//这个活动的最大队列大小
		),

		//并行活动，每个jqe是个商队。根据交易的远近，有不同的交易时间(duration).
		'TradeActivity'=>array(
			'name'		=>	'TradeActivity',
			'mode'		=>	2,//loop
			'iniq'		=>	2,//这个活动的初始队列大小
			'maxq'		=>	5,//这个活动的最大队列大小
		),
		//应用于所有串行加工类建筑。每个jqe只是加工队列中的一员。
		'MillActivity'=>array(
			'name'		=>	'MillActivity',
			'mode'		=>	1,//loop
			'iniq'		=>	2,//这个活动的初始队列大小
			'maxq'		=>	5,//这个活动的最大队列大小
		),
		//应用于Pigs,SheepYard,CowYard,HenPen,并行,串行，任务属于可添加
		'StableActivity'=>array(
			'name'		=>	'StableActivity',
			'mode'		=>	3,//once
			'iniq'		=>	2,//这个活动的初始队列大小
			'maxq'		=>	5,//这个活动的最大队列大小
		),

	);


}
