<?php
class JobConfig {

	/*
	 *  JobConfig里描述了barso物体菜单中一个按钮中的配置属性
	 *  type:按钮的主要种类，如upgrade,rotate,dwgrade,litjob,addjob,incjqe,decjqe,
	 *  name:按钮动作的名字，如makecake,make**,make**,make**
	 *
	 * Item和Sprite大部分场合都一样，表示这个sprite代表的item是谁。因为sprite名字牵扯美工工序可能要加的后缀，例如item是“Apple”,sprite是“Apple_px128” 
	 *
	 * */
	private static $data = array(
		/*
		 * 小麦生长,田地里的生长
		 * */
		'WheatGrow'		=>array('name'=>'WheatGrow','type'=>'AddJob','cd'=>30,'st'=>0,'duration'=>120,'phase'=>12,'item'=>'Water','sprite'=>'Seasons','costs'=>array(array('Water'=>3)),'gains'=>array(array('Wheat'=>7)		,)),
		'CornGrow'		=>array('name'=>'WheatGrow','type'=>'AddJob','cd'=>30,'st'=>0,'duration'=>120,'phase'=>12,'item'=>'Water','sprite'=>'Seasons','costs'=>array(array('Water'=>3)),'gains'=>array(array('Corn'=>7)		,)),
		'RiceGrow'		=>array('name'=>'WheatGrow','type'=>'AddJob','cd'=>30,'st'=>0,'duration'=>120,'phase'=>12,'item'=>'Water','sprite'=>'Seasons','costs'=>array(array('Water'=>3)),'gains'=>array(array('Rice'=>7)		,)),
		'CaneGrow'		=>array('name'=>'WheatGrow','type'=>'AddJob','cd'=>30,'st'=>0,'duration'=>120,'phase'=>12,'item'=>'Water','sprite'=>'Seasons','costs'=>array(array('Water'=>3)),'gains'=>array(array('Cane'=>7)		,)),
		'IndigoGrow'	=>array('name'=>'WheatGrow','type'=>'AddJob','cd'=>30,'st'=>0,'duration'=>120,'phase'=>12,'item'=>'Water','sprite'=>'Seasons','costs'=>array(array('Water'=>3)),'gains'=>array(array('Indigo'=>7)	,)),
		'PumpkinGrow'	=>array('name'=>'WheatGrow','type'=>'AddJob','cd'=>30,'st'=>0,'duration'=>120,'phase'=>12,'item'=>'Water','sprite'=>'Seasons','costs'=>array(array('Water'=>3)),'gains'=>array(array('Pumpkin'=>7)	,)),
		'PepperGrow'	=>array('name'=>'WheatGrow','type'=>'AddJob','cd'=>30,'st'=>0,'duration'=>120,'phase'=>12,'item'=>'Water','sprite'=>'Seasons','costs'=>array(array('Water'=>3)),'gains'=>array(array('Pepper'=>7)	,)),
		'PotatoGrow'	=>array('name'=>'WheatGrow','type'=>'AddJob','cd'=>30,'st'=>0,'duration'=>120,'phase'=>12,'item'=>'Water','sprite'=>'Seasons','costs'=>array(array('Water'=>3)),'gains'=>array(array('Potato'=>7)	,)),
		'TomatoGrow'	=>array('name'=>'WheatGrow','type'=>'AddJob','cd'=>30,'st'=>0,'duration'=>120,'phase'=>12,'item'=>'Water','sprite'=>'Seasons','costs'=>array(array('Water'=>3)),'gains'=>array(array('Tomato'=>7)	,)),
		'BerryGrow'		=>array('name'=>'WheatGrow','type'=>'AddJob','cd'=>30,'st'=>0,'duration'=>120,'phase'=>12,'item'=>'Water','sprite'=>'Seasons','costs'=>array(array('Water'=>3)),'gains'=>array(array('Berry'=>7)		,)),
		'WaterField'	=>array('name'=>'WheatGrow','type'=>'LitJob','cd'=>30,'st'=>0,'duration'=>120,'phase'=>12,'item'=>'Water','sprite'=>'Seasons','costs'=>array(array('Water'=>3)),'gains'=>array(array('Wheat'=>7)	   	,)),
	);

	public static function getJob($name)
	{
		return self::$data[$name];
	}

	public static function toArray()
	{
		return self::$data;	
	}

}
