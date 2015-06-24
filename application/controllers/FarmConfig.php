<?php
include_once 'BuildingConfig.php';//静态类用include_once
include_once 'ActivityConfig.php';//静态类用include_once
include_once 'StorageConfig.php';//静态类用include_once
include_once 'ResourceConfig.php';//静态类用include_once
include_once 'BarsoConfig.php';//静态类用include_once
class FarmConfig {

	/**
	 * 返回农场的配置数据
	 *
	 *
	 * @return var
	 */
    public static function toArray()
	{
		$farm_config['BuildingConfig']  = BuildingConfig::toArray();
		$farm_config['ActivityConfig']  = ActivityConfig::toArray();
		$farm_config['StorageConfig']   = StorageConfig::toArray();
		$farm_config['ResourceConfig']  = ResourceConfig::toArray();
		$farm_config['JobConfig']	= JobConfig::toArray();
		$farm_config['BarsoConfig']	= BarsoConfig::toArray();
		//$farm_config['hall']   = HallConfig::toAllArray();
		//$farm_config['hall']   = HallConfig::toAllArray();
		//$farm_config['shop']   = ShopConfig::toAllArray();
		//$farm_config['wall']   = WallConfig::toAllArray();
		//$farm_config['gate']   = GateConfig::toAllArray();
		//$farm_config['golder'] = GolderConfig::toAllArray();
		//$farm_config['fooder'] = FooderConfig::toAllArray();
		//$farm_config['foodar'] = FoodarConfig::toAllArray();
		//$farm_config['farmar'] = FarmarConfig::toAllArray();
		//$farm_config['YearLong'] = self::getWorldConfig('YearLong');
		//$farm_config['SeasonLong'] = self::getWorldConfig('SeasonLong');
		//$farm_config['DayLong'] = self::getWorldConfig('DayLong');
		return $farm_config;
	}	


	public static function getWorldConfig($name)
	{
		switch($name){
		case 'YearLong':
			return 21600;
			break;
		case 'SeasonLong':
			return 5400;
			break;
		case 'DayLong':
			return 600;
			break;
		default:;	
		}
	
	}



	/**
	 * 返回建筑的配置数据
	 *
	 * @param string $name 建筑名
	 * @param int	 $level 等级
	 * @param string $attr 配置属性名
	 */
	public static function getBuildingConfig($name,$attr,$level)
	{
		switch($name){
		case 'hall':
			return	HallConfig::getLevelData($attr,$level);
			break;
		case 'wall':
			return	WallConfig::getLevelData($attr,$level);
			break;
		case 'gate':
			return	GateConfig::getLevelData($attr,$level);
			break;
		case 'shop':
			return	ShopConfig::getLevelData($attr,$level);
			break;
		case 'golder':
			return  GolderConfig::getLevelData($attr,$level);
			break;
		case 'fooder':
			return  FooderConfig::getLevelData($attr,$level);
			break;
		case 'farmar':
			return  FarmarConfig::getLevelData($attr,$level);
			break;
		case 'foodar':
			return  FooderConfig::getLevelData($attr,$level);
			break;
		default:break;
		}	
	}

	public static function getLevelConfig($name)
	{
		return 9;
	}
	/**
	 *服务器和客户端网络通讯导致的不一致的秒数，可以容忍的秒数。
	 *
	 */ 
	public static function getMaxNetDelay()
	{
		return 5;
	}

	/**
	 * 返回水果的配置数据
	 *
	 * @param string $name 水果名
	 * @param string $attr 配置属性名
	 * @return int 相应配置
	 */
	public static function getFoodConfig($food,$attr)
	{
		return FoodConfig::getData($food,$attr);
	}
}
