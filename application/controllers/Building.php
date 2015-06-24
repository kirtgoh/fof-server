<?php
/**
 *对Buildee的操作 以及对farm内建筑的操作 增加 移动 删除 修改等
 *@param $fid fof文件对farm实例化时传来的参数农场id 全局变量
 *@param $collection 对数据库操作时的集合名字 farm
 *@param $query 对数据库进行where操作时匹配对应的农场的查询语句
 *在构造函数对fid进行处理，！-1的情况下如过对应的农场不存在 则报错
 *@author 郑菲菲
 *@copyright 2014/9/18
 */
class BuildingState{
	
	const NONE = 0;			//无效状态，当建筑被删除的时候
	const BUILDING = 1;		//建筑在建设过程中
	const BUILT = 2;		//建筑建设成功了，但是还没有揭幕
	const IDLE = 3;			//建筑在空闲状态
	const WORKING = 4;		//建筑在工作状态
	const PUILDING = 5;	//建筑在拆除状态
	const PUILT = 6;		//建筑拆除完了，还没有被确认
}


class Building {
	/**
	 * @var int 建筑的索引，0作为第一级。
	 * */
	private $i;
	/**
	 * type:建筑的种类，字符串
	 * @var string
	 * */
	private $t;
	/**
	 * type:建筑的行列，<R,C>
	 * @var string
	 * */
	private $r;
	private $c;
	/**
	 * @var 建筑的等级，0作为第一级。
	 * */
	private $v;
	/**
	 * @var 建筑的状态，是BuildingState的常量
	 * */
	private $s;
	/**
	 * wait time: 建筑的状态倒计时
	 * @var int
	 * */
	private $w;
	/**
	 * activity id: 建筑的活动id
	 * @var int
	 * */
	private $a;
	/**
	 * 建筑的健康: healthy
	 * @var float
	 * */
	private $h;
	/**
	 * 建筑的从属仓库：store id
	 * @var int
	 * */
	private $d;


	private function __get($name)
	{
		if(isset($name))
		{
			return $this->$name;
		}else{
			return (NULL);
		}
	}
	private function __set($name,$value)
	{
		$this->$name = $value;
	}

    public function load($info)
    {
		$this->t = $info['t'];
		$this->r = $info['r'];
        $this->c = $info['c'];
        $this->v = $info['v'];
        $this->i = $info['i'];
        $this->s = $info['s'];
        $this->a = $info['a'];
        $this->h = $info['h'];
		$this->d = $info['d'];
		$this->x = $info['x'];
		$this->w = $info['w'];
    }
	/**
	 * 把成员变量组合成Array返回
	 *
	 * @return array 成员变量Array
	 *
	 */
	public function toArray()
	{
		$data = array(
			"t"=>$this->t,
			"i"=>$this->i,
            "r"=>$this->r,
            "c"=>$this->c,
            "v"=>$this->v,
            "s"=>$this->s,
            "a"=>$this->a,
            "h"=>$this->h,
			"d"=>$this->d,
			"x"=>$this->x,
            "w"=>$this->w,
		);
		return $data;
	}

	public function init($info)
	{
	    $this->i = $info['i'];
        $this->r = $info['r'];
        $this->c = $info['c'];
		$this->v = $info['v'];
		$this->x = $info['x'];
        $this->s = BuildingState::BUILDING;
        $this->h = 1.0;
        $this->d = -1;
        $this->w = -1;
        $this->x = -1;
		$this->t= $info['t'];
	}


	public function snap()
	{
		$now = time();
        //如果建筑在建造状态
		if($this->s == BuildingState::BUILDING)
		{
			$diff_time  = $now-$this->x;		
			$this->w -= $diff_time;
			if($this->w <= 0)
			{
				$this->s = BuildingState::BUILT;	
				$this->w = -1;
				$this->x = -1;
			}	
		}

		//如果建筑在拆除状态
		if($this->s == BuildingState::PUILDING)
		{
			$diff_time  = $now-$this->x;		
			$this->w -= $diff_time;
			if($this->w <= 0)
			{
				$this->s = BuildingState::PUILT;	
				$this->w = -1;
				$this->x = -1;
			}	
		}
		$this->x = $now;
   	}

	/**
	 * 建筑的取消 建造/升级 的函数
	 *
	 * @return bool true:OK false:APP_SYNC_FAIL
	 *
	 * */
	public function cancel()
	{
		if($this->v > 0 ){
			$this->v--;
			$this->s = BuildingState::IDLE;
			return 'ok';
		}else{
			return 'cancel error';
		}	
	}
	/**
	 * 建筑的 建造/升级 的函数
	 * 对于建筑的建造和升级，我们采用的是事先升级（把v++），然后计算倒计时，倒计时完毕，切换状态。这样很合理，因为建造第一级建筑的时候，本身他就是第一级。
	 *
	 * @return string 'ok'/error text
	 * */
	public function upgrade()
	{
		$maxv = BuildingConfig::data[$this->t]['Maxv'];
		if($this->v+1 >= $maxv){
			return 'upgrade error';
		}else{
			$this->v++;
			$this->s = BuildingState::BUILDING;
			$this->x = time();
			return 'ok';
		}
			
	}

	/**
	 * 客户端发来的消息，提醒服务器，这个建筑加速阶段结束,但是，此时，经过snap的建筑
	 * 1:可能已经结束了boost状态
	 * 2:可能还没有结束boost状态
	 * 服务器上的这种不确定状态是因为客户端和服务器通讯的延迟不确定导致的。
	 *
	 * @return string 'ok'/error text
	 * */
	public function upgradeOver()
	{
		$this->s = BuildingState::IDLE;
		$this->x = -1;
		return 'ok';
	}


	/**
	 * 瞬时完成建筑的建造.注意，buildee里面的这个boost的实现仅仅是修改建筑的s和s_due,
	 * 至于建筑的具体的因为finish，不在考虑范围。那是建筑自己根据状态的变化反应
	 *
	 * @return string 'ok'/error text
	 * */
	public function finish()
	{
		if($this->s == BuildingState::BUILDING){
			$this->s = BuildingState::BUILT;
			$this->x = -1;
		}else if( $this->s == BuildingState::PUILDING){
			$this->s = BuildingState::PUILT;
			$this->x = -1;
		}else{
			return 'finish error: cannot finish a building not in building';
		}
			
	}

	/**
	 * 建筑的 回收.注意，buildee里面的这个rcl的实现仅仅是修改建筑的s和s_due,
	 * 至于建筑的具体的因为rcl的行为改变，不在考虑范围。那是建筑自己根据状态的变化反应
	 * 在完美的设计中，服务器应该把该建筑的信息清空，但是为了后面获取建筑信息建筑id与数组下表不一致而引发各种问题，
	 * 这里仅仅是把s置为"none",以后添加建筑的时候，可以先找s为none的添加。
	 * fixme: 这种回收策略有个缺陷，例如，某个高级玩家升到最高级后，把所有的城墙都回收了。那么每次读，写数据库都会牵扯大量的不必要的负载。
	 * 建筑的回收条件如下
	 * 1：quiet			可以回收
	 * 2：building		可以回收
	 * 3：boosting		可以回收
	 * 4：recycling		不可以回收
	 * 5：none			不可以回收（严重出错）
	 * 
	 * @return string 'ok'/error text
	 * */
	public function puildBuilding()
	{
		if($this->s == BuildingState::NONE)
		{
			return 'Fatal error: try to recycle a building already removed.';
		}

		if($this->s == BuildingState::RECYCLING)
		{
			return 'recycle error: cannot recycle a building already in recycling';
		}
	
		$this->s = BuildingState::PUILT;
		$this->x = -1;
		return 'ok';
	}
	/**
	 * 建筑的 回收.注意，buildee里面的这个rclover是客户端在线判断建筑回收完成时发出的消息,
	 * 但是，此时，经过snap的建筑
	 * 1:可能已经结束了recycling状态
	 * 2:可能还没有结束recycling状态
	 * 服务器上的这种不确定状态是因为客户端和服务器通讯的延迟不确定导致的。
	 * 在这种不确定情况下，我们的对测试，只要两边的时间误差不大，那么就以这个消息此时的时间为准,把
	 * 1：s设为'none'
	 * 2: s_due设为'-1'
	 *
	 * @return string 'ok'/error text
	 * */
	public function puildOver()
	{
		if($this->s != BuildingState::PUILDING)
		{
			return 'recycle error: cannot recycle a building not in recycling';
		}else{
			$this->s = BuildingState::PUILT;
			return 'ok';
			}
		}
	}
	
	/**
	 * 建筑的 加速.注意，buildee里面的这个boost的实现仅仅是修改建筑的s和s_due,
	 * 至于建筑的具体的因为boost的行为加速，不在考虑范围。那是建筑自己根据状态的变化反应
	 *
	 * @return string 'ok'/error text
	 * */
	public function move($new_r, $new_c)
	{
		$this->r = $new_r;
		$this->c = $new_c;
		return 'ok';		
	}


}
