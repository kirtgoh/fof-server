<?php
/**
 *对farm的操作 以及对farm内建筑的操作 增加 移动 删除 修改等
 *@param $fid fof文件对farm实例化时传来的参数农场id 全局变量
 *@param $collection 对数据库操作时的集合名字 farm
 *@param $query 对数据库进行where操作时匹配对应的农场的查询语句
 *在构造函数对fid进行处理，！-1的情况下如过对应的农场不存在 则报错
 *@author 郑菲菲
 *@copyright 2014/9/18
 */
include_once 'FarmLayout.php';
include_once 'FarmConfig.php';
include_once 'Utility.php';
include_once 'Activity.php';
include_once 'JobConfig.php';
include_once 'StorageConfig.php';
include_once 'Storage.php';

class Farm extends CI_Controller {

    private $fid;
    private $collection;
    private $query;
	private $querys;
	/**
	 * 农场的数据，从数据库读出来的array数据，以后的所有操作，都应该从farm_data中进行，这样读内存而不是硬盘
	 */
	public $farm_data;
	/**
	 * 农场经过的天数，每天结束后，农场就算经过一天。因为农场结算是在每一天的最后一秒结算，所以这个变量也是农场结算过点数。
	 * 这个天数是从UTC-0秒然后加上clock_off计算的。而不是从农场的创建时间计算的。
	 * var int
	 */
	private $days_gone;

	/**
	 *农场的时钟相对于UTC 0秒的偏移,单位为秒，这个偏移值在是对DayLong求模之后的。
	 *var int
	 */
	private $clock_off;


	private $acts;
	/**
	 *php中构造函数用__construct()，当找不到这个方法时，会寻找跟类名相同的方法作为构造函数。
	 *
	 * Farm的构造函数，根据$fid，获取farm里面的第一级变量,并给之赋值
	 * 1:数据库读出的farm的Array结构数据,并去掉了"_id"的开头
	 * 
	 * @param ulong $farm_id 农场的id(ulong 类型) 
	 *
	 * @return void 
	 */

    public function __construct($fid)
    {
        parent::__construct();
        $this->load->library('Mongo_db');
        $this->fid = $fid;
        $this->collection ="farm";
		$this->query = array("fid"=>$this->fid);
		//判断农场是否存在
		$farms = $this->mongoGet($this->query,$this->collection);
		if(!$farms)
        {
			Utility::echoMsg('APP_SYNC_FAIL',"the id ".$fid." of farm does't exist!");
            exit(0);
		}
		//如果存在，给farm_data赋值，（去掉数据库自动加入的_id项）
        unset($farms[0]['_id']);		
		$this->farm_data = $farms[0];

		//给农场直属的一级成员变量赋值
		$this->days_gone = $this->farm_data['days_gone'];
		$this->clock_off = $this->farm_data['clock_off'];	

	}

	/*
	 *自动加载类，当项目比较大，类比较多的时候每次调用一个类都要include，
	 *
	 *自动加载类会在脚本引擎寻找这个类失败之前最后一次机会加载所有的类，参数就是要加载的类名
	 *
	 */
    function __autoload($classname)
	{
		require_once $classname.'.php';
	}

	private function checkExist($name,$oid)
	{
		//$querys = array("fid"=>$this->fid,"{$name}.tid"=>$oid);
        $exist = $this->mongo_db->where($this->query)->get($this->collection);
        if(!$exist)
        {
            $response = array("type"=>"APP_SYNC_FAIL","text"=>"the fid{$this->fid}  doesn't exists!");
            echo json_encode($response);
            exit(0);
		}
		$object = $exist[0][$name];
		if(!array_key_exists($oid,$object))
		{
            //$response = array("type"=>"APP_SYNC_FAIL","text"=>"the  o_id{$oid} of {$name}  exists!");
            //echo json_encode($response);
			//exit(0);
			return false;
		
		}else{
			return true;
		}
	}

	private	function mongoUpdate($querys,$data)
	{
		$this->mongo_db->where($querys)->update($this->collection,$data);
	}

	private function mongoGet($querys,$collection)
	{
	   $data = $this->mongo_db->where($querys)->get($collection);
	   return $data;
	}
	private function mongoInsert($collection,$data)
	{
		$this->mongo_db->insert($collection,$data);
	}
	private function mongoPush($querys,$data)
	{
		$this->mongo_db->where($querys)->update_push($this->collection,$data);
	}

	/**
	 * 从数据库中读出本农场对应的建筑的array数据
	 *
	 * @param string $name	建筑的名字
	 * @param int	 $oid	建筑的id
	 *
	 * @return array
	 *
	 * */
	private function readObject($name,$oid)
	{
		if(!$this->checkExist($name,$oid))
		{
            $response = array("type"=>"APP_SYNC_FAIL","o_id{$oid} of {$name} doesn't exists!");
            echo json_encode($response);
            exit(0);
		}
		//$querys = array("fid"=>$this->fid,"{$name}.id"=>$oid);
		$result = $this->mongoGet($this->query,$this->collection);
		if(!$result)
		{
			echo "数据库查询异常";
			exit(0);
		}else{
			$result = $result[0];
			$data = $result[$name][$oid];
			return $data;
		}	
	}

	/**
	 * 往数据库中存入本农场对应的建筑的array数据
	 *
	 * @param string $name	建筑的名字
	 * @param int	 $oid	建筑的id
	 * @param array  $data	建筑的数据
	 *
	 * @return array
	 *
	 * */
	private function storeObject($name,$oid,$data)
	{
		$querys = array("fid"=>$this->fid,"{$name}.i"=>$oid);
		$result = $this->mongoGet($querys,$this->collection);
		$update_data = array("{$name}.$"=>$data);
		$result = $this->mongoUpdate($querys,$update_data);
	}

	/**
	 * 发来的activity消息，没有initime字段和snapTime字段
	 *
	 */
	public function formatActivity($data)
	{
		$id = $data['id'];
		$type = $data['type'];
		$cost = $data['cost'];
		$gain = $data['gain'];
		$oid = $data['oid'];
		$order = $data['order'];
		$initime = -1;
		$playMode = $data['playMode'];
		$duration = $data['duration'];
		$state = $data['state'];
		$count = $data['count'];
		$gainRatio = $data['gainRatio'];
		$gainRate = $data['gainRate'];
		$action = $data['action'];
		$snapTime = time();	
		$toArray = array('id'=>$id,'type'=>$type,'cost'=>$cost,'gain'=>$gain,
						'oid'=>$oid,'order'=>$order,'initime'=>$initime,'playMode'=>$playMode,
						'duration'=>$duration,'state'=>$state,'count'=>$count,'gainRatio'=>$gainRatio,
						'gainRate'=>$gainRate,'action'=>$action,'snapTime'=>$snapTime);
		return $toArray;	
	}

	/**
	 * 增加一个新活动，这个activity里面的几个属性默认值如下
	 * 1：initime = -1，表示未知
	 * 3: snaptTime = -1,表示未知
	 */
	public function addActivity($tid,$act)
	{
		//$commond = $act[];
		//$info = $act[];
		//step1:判断tid的合法性。这个tid一定不存在在acts里面
		$check = $this->checkExist('activity',$tid);
		if($check)
		{
			die("the tid $tid of acts exists!");
		}
		$this->formatActivity($act);
		//step2:根据order判断这个activity是否立刻执行。如果order为-1，立刻执行，如果order为0，立刻执行
		//		立刻执行要修改initime和state，并且执行cost操作
		$acts = $this->mongoGet($this->query,$this->collection);
		$acts = $acts[0];
		if($act['order']==0 ||  $act['order']==-1)
		{
			$act['state'] = 'doing';
			//执行？函数？
			$this->$commond($info);
			$act['initime'] = time();
			$cost = $act['cost'];
			$this->doCost($cost);	
		}
		//step3:把这个activity加入列表
		$data = array("activity.$tid"=>$act);
		$this->mongoUpdate($this->query,$data);
		return 'ok';
	}

	/**
	 * 这个cost里含有一系列的资源描述,下面就是一个$cost的描述，它是一个数组，每一项表明从哪个仓储里消耗多少个什么资源。
	 * array(
	 *     0=>array('sid'=>1,'name'=>'gold','count'=>1300),
	 *     1=>array('sid'=>3,'name'=>'pear','count'=>1000),
	 *  ),
	 *
	 */
	public function doCost($cost)
	{
		if(!is_array($cost))
		{
			die("cost error:the cost is not an array!tid $this->tid ");
		}
		foreach($cost as $key=>$value)
		{
			$sid = StorageConfig::getResourceStorage($key);
			$storage = $this->getObject('storage',$sid);
			$storage->dec($key,$value);
			$this->setObject('storage',$sid,$storage);
		}
		return;
	}

	public function doGain($gain)
	{
		foreach($gain as $key=>$value)
		{
			$sid = StorageConfig::getResourceStorage($key);
			$storage = $this->getObject('storage',$sid);
			$storage->add($key,$value);
			$this->setObject('storage',$sid,$storage);

		}
		return;
	}

	/**
	 * 在一个Activity里增加一个工作队列项(JQE)Job Queue Element.
	 * @return array 返回函数执行的结果array,result['flag']为布尔型，执行成功为true. result['text']为string,返回的结果标注。
	 * */
	public function addJqe($name,$tid,$qid)
	{
		$activityObj = $this->getObject('activity',$tid);
		$result = $activityObj->addJqe($name,$qid);
		if($result['flag'])
		{
			$this->setObject('activity',$tid,$activity);
			return Utility::retFunc(true,"OK");;
		}else{
			return $result;
		}
	}




	/**
	 * 在一个Activity里增加count个名为name的job.
	 * @return array 成员变量Array
	 */
	public function addJob($name,$tid,$qid,$count)
	{
		$activityObj = $this->getObject('activity',$tid);
		$activityObj->addJob($name,$tid,$qid,$count);
		$this->setObject('activity',$tid,$activityObj);
		return Utility::retFunc(true,"OK");;
	}

	/**
	 * 在一个Activity里增加count个名为name的job.传来的Form消息有可能来自于html表单，
	 * 所以在这个地方把传来的消息中的整形数据转化为intval
	 *
	 * name: job的名字
	 * tid:  添加到activity的id
	 * qid:  添加到activity的哪个queue
	 * count:添加多少个这样的job
	 * 
	 * 这个函数要完成的功能如下：
	 * 在activity[tid].jobs[qid]的这个数组项一定存在，并且它的名字是name.
	 * 对它的count加count。针对下面几种情况处理
	 * 各种模式下的功能如下：
	 * LOOP	:非法
	 * SERL :仅仅把count增加以下，其它什么都不做。 
	 * PARA	:仅仅把count增加以下，其它蛇呢么都不做。
	 *
	 * @return array 成员变量Array
	 */

	public function incJob($name,$tid,$qid,$count)
	{	
		return Utility::retFunc(false,"incJob CurrentLy Not Implemented");;
	}



	/**
	 * 在一个Activity里删除名为name的job.
	 * 传来的Form消息有可能来自于html表单，
	 * 所以在这个地方把传来的消息中的整形数据转化为intval
	 *
	 * name: job的名字
	 * tid:  activity的id
	 * qid:  activity的哪个queue
	 * count:这个是验证数据，表示删除时，这个job有count个。删除的函数要做这个验证。
	 * 
	 * 这个函数要完成的功能如下：
	 * 在activity[tid].j[qid]的这个数组项一定存在，并且它的名字是name.然后删除这一项。
	 * 各种模式下的功能如下：
	 * LOOP	:非法
	 * SONCE:删除这样job后，然后启动排在后面的job.
	 * PONCE:删除掉。
	 * SLEEP:非法
	 * @return array 成员变量Array
	 */
	public function delJob($tid,$qid,$name,$count)
	{
		return Utility::retFunc(false,"delJob CurrentLy Not Implemented");;
	}


	/**
	 * 在一个Activity里减少count个名为name的job.传来的Form消息有可能来自于html表单，
	 * 所以在这个地方把传来的消息中的整形数据转化为intval
	 *
	 * name: job的名字
	 * tid:  activity的id
	 * qid:  activity的哪个queue
	 * count:减少多少个这样的job
	 * vcount:验证数据，减少完后剩下多少个这样的job.
	 *
	 * 这个函数要完成的功能如下：
	 * 在activity[tid].j[qid]的这个数组项一定存在，并且它的名字是name.
	 * 对它的count加count。针对下面几种情况处理
	 * 各种模式下的功能如下：
	 * LOOP	:非法
	 * SONCE:仅仅把count增加以下，其它什么都不做。 
	 * PONCE:仅仅把count增加以下，其它蛇呢么都不做。
	 * SLEEP:把count增加，如果增加前是sleep状态，那么增加后进入doing状态。
	 *
	 * @return array 成员变量Array
	 */

	public function decJob($tid,$qid,$name,$count)
	{	
		return Utility::retFunc(false,"decJob CurrentLy Not Implemented");;
	}

	/**
	 * 在一个Activity里加速名为name的job的工作的当前阶段，所谓加速就是把job的progress往前提secs秒数。
	 * 并且置ct=time().
	 * 并且置cd=Jobconfig::get(jobName)['cd']
	 * 并且置cc++;
	 *
	 * name: job的名字
	 * tid:  activity的id
	 * qid:  activity的哪个queue
	 * secs: 把progress往前提secs秒
	 * prog: 新prog(验证数据)
	 * 
	 * 这个函数要完成的功能如下：
	 * 在activity[tid].j[qid]的这个数组项一定存在，并且它的名字是name.
	 * 各种模式下的功能如下：
	 * LOOP	:非法
	 * SERL :把progress往前提secs秒，修改progress，验证是否和消息发来的数据一致。
	 * PARA :如SONCE.
	 * @return array 成员变量Array
	 */

	public function spdJob($tid,$qid)
	{
		$tid = intval($info['tid']);
		$jid = intval($info['jid']);
		$secs = intval($info['secs']);
		$new_prog = intval($info['prog']); 
		$act = $this->getObject('activity',$tid);
		$mode = $act->m;
		if(!array_key_exists($jid,$act->j))
		{
			die("the act of $tid doesn't contain the job id $jid");
		}	
		switch($mode)
		{
			case ActivityMode::LOOP:
			{
				die("SPD_JOB doesn't match the mode $mode");
			}
			case ActivityMode::SONCE:
			case ActivityMode::PONCE:
			case ActivityMode::SLEEP:
			{
				$act->spdJob($jid,$secs,$new_prog); 
				break;
			}
			default:
			{
				die("the mode $mode is wrong!");
			}
		}
		$this->setObject('activity',$tid,$act);
	
	}

	/**
	 * 在一个Activity里加色名为name的job的工作的当前阶段，所谓加色就是把
	 * job的ct置为当前时间,
	 * job的cd=Jobconfig::get(jobName)['cd']
	 * job的cc++;
	 * 注意：
	 * 传来的Form消息有可能来自于html表单，
	 * 所以在这个地方把传来的消息中的整形数据转化为intval
	 *
	 * name: job的名字
	 * tid:  activity的id
	 * qid:  activity的哪个queue
	 * 
	 * 这个函数要完成的功能如下：
	 * 在activity[tid].j[qid]的这个数组项一定存在，并且它的名字是name.
	 * 各种模式下的功能如下：
	 * LOOP	:非法
	 * SONCE:如上注释.
	 * PONCE:如SONCE.
	 * SLEEP:如SONCE.
	 * @return array 成员变量Array
	 */

	public function litJob($info)
	{
	
		$tid = intval($info['tid']);
		$jid = intval($info['jid']);
		$act = $this->getObject('activity',$tid);
		$mode = $act->m;
		if(!array_key_exists($jid,$act->j))
		{
			die("the act of $tid doesn't contain the job id $jid");
		}	
		$act->litJob($jid);	
		$this->setObject('activity',$tid,$act);
	
	}

	/**
	 * 在一个Activity里完成count个名为name的job.传来的Form消息有可能来自于html表单，
	 * 所以在这个地方把传来的消息中的整形数据转化为intval
	 *
	 * name: job的名字
	 * tid:  activity的id
	 * qid:  activity的哪个queue
	 * count:多少个这样的job
	 * 
	 * 这个函数要完成的功能如下：
	 * 在activity[tid].j[qid]的这个数组项一定存在，并且它的名字是name.
	 * 对它的count做完成。针对下面几种情况处理
	 * 各种模式下的功能如下：
	 * LOOP	:重置它的progress,count,从新开始运行。
	 * SONCE:减少count,如果减到0，需要删除job,如果后面还有，那么运行。
	 * PONCE:减少count,如果见到0，删除job.
	 * SLEEP:
	 * @return array 成员变量Array
	 */

	public function doaJob($info)
	{
		$ctime = time();
		$tid =intval( $info['tid']);
		$jid =intval( $info['jid']);
		$count = intval($info['count']);
		$vcount = intval($info['vcount']);
		$act = $this->getObject('activity',$tid);
		if($act->j[$jid]['count']<$count || $act->j[$jid]['count']-$count != $vcount)
		{
			die("the CS data inconsistency!count of job $jid");
		}
		$mode = $act->m;
		$stime = $act->j[$jid]['initime']+$act->j[$jid]['duration']*$count;
		if($act->j[$jid]['state']!=JobState::DOING)
		{
			die("state error:the state must be doing in doajob!");
		}
		$act->doaJob($jid,$count);	
		//step2:如果这个活动的mode是loop,那么就应该之清算gain和cost,清算完就return。
		$this->setObject('activity',$tid,$act);
		return strval($ctime-$stime);
	
	}






	public function startActivity($tid)
	{
		if(!array_key_exists($tid,$this->acts))
		{
			die("the tid $tid does not exist!");
		}
		$act = $this->getObject('activity',$tid);
		$act->start($tid);
		$this->setObject('activity',$tid,$act);
		
	}
		
	/**
	 * 增加一个活动的count
	 */
	public function appendActivity($tid,$count,$order)
	{

		//step1:判断tid的合法性。这个tid一定存在在acts里面
		if(!array_key_exists($tid,$this->acts))
		{
			die("the tid $tid does not exist!");
		}
		//step2:仅仅修改count域
		$act = $this->getObject('activity',$tid);
		$act->append($count);
		$this->setObject('activity',$tid,$act);
	}
	/**
	 * 取消一个活动的count
	 */
	public function cancelActivity($tid,$count)
	{
		//step1:判断tid的合法性。这个tid一定存在在acts里面
		if(!array_key_exists($tid,$this->acts)||$this->acts['count']<$count)
		{
			die("the  tid $tid does not exist or the count less than you need!");
		}
		//step2:减少活动的count
		//fixme:这个地方是否要判断减少到0以下呢
		$act = $this->getObject('activity',$tid);
		$this->cancel($count);
		$this->setObject('activity',$tid,$act);
	}

	public function doneActivity($tid)
	{

		if(!array_key_exists($tid,$this->acts))
		{
			die("the tid $tid does not exist!");
		}
		//step2:如果这个活动的playMode是loop,那么就应该之清算gain和cost,清算完就return。
		$act = $this->getObject('activity',$tid);
		$act->done();
		$this->setObject('activity',$tid,$act);
		//$action = $act->action;
		//$this->doAction($action);
		//$this->setObject($name,$oid,$building);	
	}
	/**
	 * 完成一个活动。
	 */
	public function finishActivity($tid)
	{

		if(!array_key_exists($tid,$this->acts))
		{
			die("the tid $tid does not exist!");
		}
		//step2:如果这个活动的playMode是loop,那么就应该之清算gain和cost,清算完就return。
		$act = $this->getObject('activity',$tid);
		$act->finish();
		$this->setObject('activity',$tid,$act);
		//$action = $act->action;
		//$this->doAction($action);
		//$this->setObject($name,$oid,$building);	
	}

	/**
	 * 动作的入口函数
	 * 
	 */
	public function doAction($action)
	{
		$name = $action['name'];
		$attr = $action['attr'];

		switch($name){
		case 'MoveBuilding':{
			$bid = $attr['bid'];
			$r	 = $attr['r'];
			$c	 = $attr['c'];
			$building = $this->getObject('building',$bid);
			$building->move($r,$c);
			//--------
			$this->setObject('building',$bid,$building);	
			break;
		}
		
		/*
		* array(
		*     0=>array('ssid'=>1,'dsid'=>2,'name'=>'gold','count'=>1300),
		*     1=>array('ssid'=>3,'dsid'=>2,'name'=>'pear','count'=>1000),
		*  ),
		*/
		case 'TransferStorage':{
			foreach($attr as $key)
			{
				$ssid = $attr[$key]['ssid'];
				$dsid = $attr[$key]['dsid'];
				$type = $attr[$key]['name'];
				$count= $attr[$key]['count'];
				$sstorage = $this->getObject('storage',$ssid);
				$sstorage->dec($type,$count);
				$dstorage = $this->getObject('storage',$ssid);
				$dstorage->add($type,$count);
				$this->setObject('storage',$ssid,$sstorage);
				$this->setObject('storage',$dsid,$dstorage);

			}
			break;
		}

		case 'UpgradeOver':{
			$bid = $attr['bid'];
			$building = $this->getObject('building',$bid);
			$building->upgradeOver();
			$this->setObject('building',$bid,$building);
	
		}	
		case 'Upgrade':{
			$bid = $attr['bid'];
			$building = $this->getObject('building',$bid);
			$building->upgrade();
			$this->setObject('building',$bid,$building);
	
		}


		
		
		
		}
	
	}

	/**
	 * 对整个农场做快照的函数，它在登录的时候被getFarm调用,它的动作如下
	 * 
	 * 1: 通过read_farm把farmdata读到内存。
	 * 2：对farmdata里的所有建筑进行快照操作，操作完的值都要存在farmdata中。
	 * 3: 通过store_farm把farmdata存入到数据库
	 * 
	 * @param ulong $farm_id 农场的id(ulong 类型) 
	 *
	 * @return array
	 */
	public function snapActivity()
	{
		$farm_data = &$this->farm_data;
		$acts = &$farm_data['activity'];
		$act_count = count($acts);
		foreach($acts as $tid=>$value)
		{
			$act = $this->getObject('activity',$tid);
			if(count($act->j)>0)
			{
				//$ret = $act->snap();
				$acts[$tid] = $act->toArray();
				$this->setObject('activity',$tid,$act);
			}
		}
		return $farm_data;
	}

	public function snapFarm()
	{
		$farm_data = &$this->farm_data;
		//step1:对hall做快照,虽然全局只有一个hall，但我们的代码兼容对多个hall的处理,从而给予未来策划足够的自由度

		
		//-----------------------------------------
		//-----------农场平顶+平底
		//-----------------------------------------
		
		$this->mongoUpdate($this->query,$farm_data);
		return $farm_data;
	}

	/**
	 * 在线状态下客户端发来的日结消息引发的函数。农场清算里面的：大厅生产文化（增加），果仓水果（减少），金库金币（减少）。
	 * 函数名：eod = end of day
	 * 注意，客户端发来的消息自然是强制要求服务器端做一次日结。此时，服务器端
	 * 1：可能还没有走到日结的时间。（比如，日结的时间是10分钟一次）
	 * 2：可能已经走过了日结的时间。
	 * 服务器端要检查自己的时间离日结时间的秒数偏差，如果差太大，那么属于错误，返回APP_SYNC_FAIL。如果偏差小，那么就直接做eod结算
	 * 
	 *
	 */
	private function eodFarm()
	{
		//求出日结个数
		$day_long = FarmConfig::getWorldConfig('DayLong');
		$time_dif = time() - $this->days_gone*$day_long;
		$time_dif = abs($time_dif);
		if($time_dif > FarmConfig::getMaxNetDelay()){
			return 'eod sync error: time diff is '.$time_dif;
		}else{
			$farm_data = &$this->farm_data;
			$hall_count = count($farm_data['halls']);
			for($i = 0;$i<$hall_count;$i++){
				$farm_data['halls'][$i] = $this->eodHall($i,1)->toArray();
			}
			$fooder_count = count($farm_data['fooders']);
			for($i = 0;$i<$hall_count;$i++){
				$farm_data['fooders'][$i] = $this->eodFooder($i,1)->toArray();
			}
			$golder_count = count($farm_data['golders']);
			for($i = 0;$i<$golder_count;$i++){
				$farm_data['goldders'][$i] = $this->eodGolder($i,1)->toArray();
			}
				
			$this->mongoUpdate($this->query,array('days_gone'=>$this->days_gone + 1));
			return 'ok';
		}	
	
	}




	/**
	 * 根据name和id返回建筑的引用。这个函数的动作由，读，获取引用，快照
	 *
	 * @var $name	建筑的名字
	 * @var $oid	建筑的id
	 * 
	 * @return object building的引用
	 */

	private function getObject($name,$oid)
	{
		//------------------------
		//step1:读出建筑
		$old_data = $this->readObject($name,$oid);
		//------------------------
		//step2:对建筑做快照
		switch($name){
		case 'storage':$object = new Storage();break;
		case 'building':$object = new Building();break;
		case 'activity':$object = new Activity();break;
		default:echo 'error: bad name'.$name;	
		}
		$object->load($old_data);

		//-----------------
		//step3:返回建筑引用
		return $object;	
	}

	/**
	 * 把建筑存入数据库，
	 *
	 * @var object  $building	建筑的名字
	 * 
	 * @return string error_reson or "ok"
	 */
	private function setObject($name,$oid,$object)
	{
		//------------------------
		//step4:得到建筑的新的array值
		$new_data = $object->toArray();
		
		//------------------------
		//step5:把建筑的新值存入数据库
		$this->storeObject($name,$oid,$new_data);

		return 'ok';	
	}

	/**/
	private function boostBuilding($name,$oid)
	{
		//------------------------
		//step3:获得建筑当前的数据引用
		$building = $this->getObject($name,$oid);
		
		//------------------------
		//step3:调用建筑的加速函数
		$ret = $building->boost();
		if($ret != 'ok'){
			return $ret;
		}
		//------------------------
		//step4:调用建筑的设置函数，写回数据库
		$ret = $this->setObject($name,$oid,$building);	
		return $ret;
	}

	/**
	 * 客户端的主动发消息调用，主要是调用服务器对建筑做一次snap,然后强制修改它的状态值
	 *
	 *
	 */
	private function boostBuildingOver($name,$oid)
	{
		//------------------------
		//step3:获得建筑当前的数据引用
		$building = $this->getObject($name,$oid);
		
		//------------------------
		//step3:调用建筑的加速函数
		$ret = $building->boostOver();
		if($ret != 'ok'){
			return $ret;
		}
		//------------------------
		//step4:调用建筑的设置函数，写回数据库
		$ret = $this->setObject($name,$oid,$building);	
		return $ret;
	}

	/**/
	private function cancelBuilding($name,$oid)
	{
		//------------------------
		//step3:获得建筑当前的数据引用
		$building = $this->getObject($name,$oid);
		
		//------------------------
		//step3:加速
		$ret = $building->cancel();
		if($ret != 'ok'){
			return $ret;
		}
		//------------------------
		//step4:调用建筑的设置函数，写回数据库
		$ret = $this->setObject($name,$oid,$building);	
		return $ret;

	}

	/**
	 * 客户端的主动发消息调用，主要是调用服务器对建筑做一次snap,然后强制修改它的状态值
	 *
	 *
	 */
	private function recycleBuildingOver($name,$oid)
	{
		//------------------------
		//step3:获得建筑当前的数据引用
		$building = $this->getObject($name,$oid);
		
		//------------------------
		//step3:调用建筑的加速函数
		$ret = $building->recycleOver();
		if($ret != 'ok'){
			return $ret;
		}
		//------------------------
		//step4:调用建筑的设置函数，写回数据库
		$this->setObject($name,$oid,$building);	
		return $ret;
	}
	private function recycleBuilding($name,$oid)
    {
		//step1:对建筑做快照
		$building	= $this->getObject($name,$oid);
		$ret =	$building->recycleBuilding();
		if($ret != 'ok')
		{
			return $ret;
		}
		//step3:把建筑更新入数据库
		$ret = $this->setObject($name,$oid,$building);	
		return $ret;
	}

	private function upgradeBuilding($name,$oid)
	{
		//------------------------
		//step3:获得建筑当前的数据引用
		$building = $this->getObject($name,$oid);
		
		//------------------------
		//step3:加速
		$ret = $building->upgrade();
		if($ret != 'ok'){
			return $ret;
		}
		//------------------------
		//step4:调用建筑的设置函数，写回数据库
		$ret = $this->setObject($name,$oid,$building);	
		return $ret;
	}
	/**
	 * 客户端的主动发消息调用，主要是调用服务器对建筑做一次snap,然后强制修改它的状态值
	 *
	 *
	 */
	private function upgradeBuildingOver($name,$oid)
	{
		//------------------------
		//step3:获得建筑当前的数据引用
		$building = $this->getObject($name,$oid);
		
		//------------------------
		//step3:加速
		$ret = $building->upgradeOver();
		if($ret != 'ok'){
			return $ret;
		}
		//------------------------
		//step4:调用建筑的设置函数，写回数据库
		$ret = $this->setObject($name,$oid,$building);	
		return $ret;

	}

	public function moveBuilding($name,$oid,$new_r,$new_c)
	{
		//------------------------
		//step3:获得建筑当前的数据引用
		$building = $this->getObject($name,$oid);
		
		//------------------------
		//step3:移动
		$ret = $building->move($new_r,$new_c);
		if($ret != 'ok'){
			return $ret;
		}
		//------------------------
		//step4:调用建筑的设置函数，写回数据库
		$ret = $this->setObject($name,$oid,$building);	
		return $ret;

	}


	private function finishBuilding($name,$oid)
	{
		//------------------------
		//step3:获得建筑当前的数据引用
		$building = $this->getObject($name,$oid);
		
		//------------------------
		//step3:移动
		$ret = $building->finish();
		if($ret != 'ok'){
			return $ret;
		}
		//------------------------
		//step4:调用建筑的设置函数，写回数据库
		$ret = $this->setObject($name,$oid,$building);	
		return $ret;

	}

	/**
	 * 客户端发送“完成一个harv交易”的消息，其中带有目前剩余的交易（$trade）作为和服务器端的确认。
	 * 这个消息是在客户端进入游戏以后，每完成一项交易发出。此时客户端和服务器端因为时间关系，可能不一致。
	 * 如果不一致小的话，由客户端重置数据库，如果不一致大的话，返回APP_SYNC_FAIL消息。
	 * 为了对以后的hadoop价格分析做支持，这个消息中加入交易量和交易价格，这样我们就能分析一次交易的量。
	 *
	 * @var count int   交易的苹果数目
	 * @var price float 交易的苹果的价格,金币/每个(客户端显示的时候，肯定是按金币/每百个，发送到消息就换算成金币/个)
	 *
	 */
	private function doaHarv($oid)//后期考虑 收获时到底按照数据库的收获时间还是按快照 用不用快照
	{
		$name = 'foodar';
		//step1:对foodar做快照
		$foodar	= $this->getObject($name,$oid);
		$foodar->doaHarv();

		$fooder_id = $foodar->fooder;
		$fooder = $this->getObject('fooder',$fooder_id);

		//step2:	
		$fitem = 'apple';
		$fooder->add($fitem,$foodar->harv_food);	

		//step3:把建筑更新入数据库
		$ret = $this->setObject('fooder',$fooder_id,$fooder);	
		$ret = $this->setObject('foodar',$oid,$foodar);	

		return 'ok';
	}



}
