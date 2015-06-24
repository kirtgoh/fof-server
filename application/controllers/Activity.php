<?php
include_once 'JobConfig.php';

class JobState{
	const NONE = 0;			
	const TODO = 1;		
	const DOING = 2;		
	const DONE = 3;			
}


class ActivityMode{
	const LOOP = 0;
	const SERL = 1;
	const PARA = 2;
	const SLEEP = 3;
}


class Activity
{
	/*
	 * Activity的索引.在数据库里对应索引i.
	 * */
	public $i;
	/*
	 * Activity的种类.字符串类型.
	 * */
	public $t;
	/*
	 * Activity的模式.活动运行的模式:有ActivityMode.LOOP,ActivityMode.SERL,ActivityMode.PARA,ActivityMode.SLEEP
	 * */
	public $mode;
	/*
	 * Activity的JQE列表. JQE列表.
	 * */
	public $jqes;
	/*
	 * Activity的Job列表. Job数组.
	 * */
	public $jobs;
	/*
	 * Activity的job完成列表.字符串数组.
	 * */
	public $jops;

	public function  __set($name,$value)
	{
		$this->$name = $value;
	}
	public function __get($name)
	{
		if(isset($this->$name))
		{
			return $this->$name;
		}else{
			return(NULL);
		}
	}

	/**
	 * 加载array数据到对象的变量里. 这个函数是Farm的setObject结尾会调用的函数,toArray了之后才会写入数据库
	 */

	public function toArray()
	{
		$act = array(
			'i'=>$this->i,
			't'=>$this->t,
			'mode'=>$this->mode,
			'jqes'=>$this->jqes,
			'jobs'=>$this->jobs,
			'jops'=>$this->jops,
		);
		return $act;	
	}


	/**
	 * 加载array数据到对象的变量里. 这个函数是Farm的getObject结尾会调用的函数.这个函数实际是toArray对应的toObject
	 * 注意，mode,maxq,iniq变量是通过config文件查出来的，而不是存在数据库的。
	 */
	public function load($data)
	{
		$this->i = $data['i'];
		$this->t = $data['t'];
		$activityCfg = ActivityConfig::get($this->t);
		Utility::logInfo('Class::ActivityConfig.load('.$this->t.')');
		$this->jqes = $data['jqes'];
		$this->jobs = $data['jobs'];
		$this->jops = $data['jops'];
		$this->mode = $activityCfg['mode'];
		$this->maxq = $activityCfg['maxq'];
		$this->iniq = $activityCfg['iniq'];
	}


	/**
	 * 在一个Activity里增加一个工作队列项(JQE)Job Queue Element.
	 *
	 *		对于Mill:扩展一项
	 *		对于stable:增加一头猪，羊，牛
	 *		对于mulberrytree:增加一个蚕蛹
	 *		对于采蜂树：增加一个风罐子
	 *
	 * @param name,job的名字
	 * @param tid,添加到activity的id
	 * @param qid,添加到activity的哪个queue
	 * 
	 * 这个函数完成的功能在4种模式下的工作：
	 *
	 *	LOOP	:非法(LOOP模式的job都是写死的，不可动态更新)
	 *	SERL	:name一定是"any".
	 *	PARA	:name可以是任意.
	 *
	 * @return array 返回函数执行的结果array,result['flag']为布尔型，执行成功为true. result['text']为string,返回的结果标注。
	 *
	 * */
	public function addJqe($name,$qid)
	{
		Utility::logInfo('Class::Activity.addJqe('.$name.','.$qid.')');
		/*
		 * 实例化的activity对象 
		 * */
		$jqes = &$this->jqes;
		$qSize = count($jqes);
		/*
		 * jqes[qid]一定不存在.此处我们用array_key_exists — 检查给定的键名或索引是否存在于数组中
		 * bool array_key_exists ( mixed $key , array $search )
		 * array_key_exists() 在给定的 key 存在于数组中时返回 TRUE。key 可以是任何能作为数组索引的值。array_key_exists() 也可用于对象。
		 * isset() 对于数组中为 NULL 的值不会返回 TRUE，而 array_key_exists() 会。
		 * $search_array = array('first' => null, 'second' => 4);
		 * isset($search_array['first']);// returns false	
		 * array_key_exists('first', $search_array);// returns true
		 * */

		if(array_key_exists($qid,$jqes))
		{
			return Utility::retFunc(false,'qid '.$qid.' already exist. with jqe name'.$jqes[$qid]);
		}

		//验证目前的队列大小加1一定不超过配置文件指定的队列大小
		if($qSize + 1 > $activityObj->maxq)
		{
			return Utility::retFunc(false,"out limit the max queue size ".$ac['maxq']);
		}

		switch($this->mode)
		{
		case AcitvityMode::LOOP:
			return Utility::retFunc(false,"failure! try to addJqe to a  LOOP Activity");
			break;
		case AcitvityMode::SERL:
			if($name != 'any')
			return Utility::retFunc(false,"failure! try to addJqe a not any job ( ".$name." ) to a  SERL Activity");
			break;
		case AcitvityMode::PARA:
			break;
		default:
			return Utility::retFunc(false,"failure! Bad mode ".$this->mode);
			break;
		}
		//设置$jqes[$qid]为$name
		$jqes[$qid] = $name;
		return Utility::retFunc(true,"OK");;
	}

	/**
	 * 在一个Activity里增加count个名为name的job.
	 * 传来的Form消息有可能来自于html表单,所以在这个地方把传来的消息中的整形数据转化为intval
	 *
	 * name: job的名字
	 * tid:  添加到activity的id
	 * qid:  添加到activity的哪个queue
	 * count:添加多少个这样的job
	 * 
	 * 这个函数完成的功能在4种模式下的工作：
	 * LOOP:非法(LOOP模式的job都是写死的，不可动态更新)
	 * SERL:把新job添加在队尾，如果是第一个，那么运行（设置它的state为DOING.等等..）。验证队尾的索引是qid
	 * PARA:把新job添加在j[qid]这个地方,不一定和其它job索引相连续。
	 *
	 * @return array 成员变量Array
	 */

	public function addJob($name,$qid,$count)
	{
		Utility::logInfo('Class::Activity.addJob('.$name.','.$qid.','.$count.')');
		$jobs = &$this->jobs;
		$jqes = &$this->jqes;
		$jobc = count($jobs);
		$jqec = count($jqes);
		$mode = $this->mode;

		if(!array_key_exists($qid,$jqes))
		{
			return Utility::retFunc(false,'qid '.$qid.' doesnt exist in jqe list');
		}
		//如果jobs[$qid]已经有了job,那么
		if(array_key_exists($qid,$jobs))
		{
			return Utility::retFunc(false,'qid '.$qid.' already exist in job list as '.$jobs[$qid]['name']);
		}

		//判断添加的job的参数 如果是ponce或者sonce且jcount为1，设置initime,state立即执行，如果是其他的非法
		switch($mode)
		{
			case ActivityMode::LOOP:
			{	
				return Utility::retFunc(false,"failure! try to addJob to a  LOOP Activity");
				break;
			}
			case ActivityMode::SERL:
			{
				if($jobc != $qid)
				{
					return Utility::retFunc(false,"failure! try to addJob to a SERL Activity with wrong qid ".$qid." while jobc is ".$jobc);
				}
				$jobCfg = JobConfig::getJob($name);

				$initime	= -1;
				$state		= JobState::TODO;

				if($jobc == 0)
				{
					$initime = time();
					$state	 = JobState::DOING;
				}else{
					$initime = -1;
					$state	 = JobState::TODO;
				}
				$job = array(
					'name'=>$name,
					'initime'=>$initime,
					'prog'=>0,
					'state'=>$state,
					'ct'=>-1,'cd'=>-1,'cc'=>0,
					'count'=>$count,
					'cost'=> $jobCfg['cost'],
					'gain'=> $jobCfg['gain'],
					'dura'=>$jobCfg['dura']);

				$jobs[$qid] = $job;
				break;
			}
			case ActivityMode::PARA:
			{
				$jobCfg = JobConfig::getJob($name);

				$job = array(
					'name'=>$name,
					'initime'=>$time(),
					'prog'=>0,
					'state'=>JobState::DOING,
					'ct'=>-1,'cd'=>-1,'cc'=>0,
					'count'=>$count,
					'cost'=> $jobCfg['cost'],
					'gain'=> $jobCfg['gain'],
					'dura'=>$jobCfg['dura']);

				$jobs[$qid] = $job;

				break;
			}
			default:
			{
				return Utility::retFunc(false,"failure! Bad mode ".$this->mode);
				break;
			}
		}	
	}
	/**
	 * 在一个Activity删除掉一个job
	 *
	 * jid: 删除的Job的id
	 *
	 * LOOP:非法(LOOP模式的job都是写死的，不可动态更新)
	 * SERL:非法
	 * PARA:把Job删除掉
	 *
	 * @return array 成员变量Array
	 */
	public function delJob($jid)
	{
		Utility::logInfo('Class::Activity.delJob('.$qid.')');
		$jobs = &$this->jobs;
		if(!array_key_exists($jid,$jobs))//如果jobs中没有对应的key[$jid]
		{
			return Utility::retFunc(false,'jid '.$jid.' doesnt exist in job list');
		}
		switch($this->mode)
		{
			case ActivityMode::PARA:
				unset($jobs[$jid]);
				break;
			default:
				return Utility::retFunc(false,"failure! Bad mode ".$this->mode);
				break;
		}		
		return Utility::retFunc(true,'jobs ['.$jid.'] deleted');
	}

	/**
	 * 完成一个活动。
	 */
	public function doaJob($jid)
	{
		Utility::logInfo('Class::Activity.doaJob('.$qid.')');
		//step1:判断tid的合法性。这个tid一定存在在acts里面&&这个活动的状态一定是doing.
		$jobs = &$this->jobs;
		$jobc = count($jobs);
		$jops = &$this->jops;
		$job  = &$jobs[$jid];
		$mode =  $this->mode;
		

		switch($mode)
		{
			case ActivityMode::LOOP:
			{
				$job['initime'] = time();
				$job['prog'] = 0;
				return Utility::retFunc(true,'jobs ['.$jid.'] looped');
				break;
			}
			case ActivityMode::SERL:{
				if($jid != 0)
				{
					return Utility::retFunc(false,'Cannot DOA jobs[0] in a SERL Activity');
				}else{
					array_push($jops,$job['name']);//array_push() 函数向第一个参数的数组尾部添加一个或多个元素（入栈），然后返回新数组的长度。
					array_shift($jobs);
					if( $jobc == 0 )
					{
						$jobs[0]['initime']=time();
						$jobs[0]['state']=JobState::DOING;
					}
				}
				break;
			}
			case ActivityMode::PARA:{
				$job['state']=JobState::DONE;
				break;
			}
			default:{
				return Utility::retFunc(false,"failure! Bad mode ".$this->mode);
			}
		}	
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
	 * 
	 * 这个函数要完成的功能如下：
	 * 在activity[tid].j[qid]的这个数组项一定存在，并且它的名字是name.
	 * 各种模式下的功能如下：
	 * LOOP	:非法
	 * SERL:把progress往前提secs秒，修改progress，验证是否和消息发来的数据一致。
	 * PARA:如SERL.
	 * @return array 成员变量Array
	 */

	public function spdJob($jid,$secs)
	{
		Utility::logInfo('Class::Activity.spdJob('.$qid.')');
		$mode = $this->mode;
		$jobs = &$this->jobs;
		$jops = &$this->jops;
		$job = &$jobs[$jid];
		$name = $job['name'];
		$jobCfg = JobConfig::getJob($name);

		if(!array_key_exists($jid,$jobs))//如果jobs中没有对应的key[$jid]
		{
			return Utility::retFunc(false,'jid '.$jid.' doesnt exist in job list');
		}


		switch($mode)
		{
			case ActivityMode::SERL:
			case ActivityMode::PARA:
			{
				$job['prog'] += $secs;
				$job['ct'] = time();
				$job['cd']=$jobCfg['cd'];
				$job['cc']++;
				return Utility::retFunc(true,"ok");
				break;
			}
			default:
			{
				return Utility::retFunc(false,"failure! Bad mode ".$this->mode);
			}
		}
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
	 * 
	 * 这个函数要完成的功能如下：
	 * 在activity[tid].j[qid]的这个数组项一定存在，并且它的名字是name.
	 * 各种模式下的功能如下：
	 * LOOP	:非法
	 * SERL:把progress往前提secs秒，修改progress，验证是否和消息发来的数据一致。
	 * PARA:如SERL.
	 * @return array 成员变量Array
	 */

	public function litJob($jid)
	{
		$jobs = &$this->jobs;
		if(!array_key_exists($jid,$jobs))//如果jobs中没有对应的key[$jid]
		{
			return Utility::retFunc(false,'jid '.$jid.' doesnt exist in job list');
		}

		$job  = &$jobs[$jid];
		$name = $job['name'];
		$jobCfg = JobConfig::getJob($name);

		$job['cc']++;
		$job['ct'] = time();
		$job['cd']=$jobCfg['cd'];

		return Utility::retFunc(true,'ok');
	}



	public function snap()
	{
		Utility::logInfo('Class::Activity.snap()');
		/*如果Activity中没有job,那么退出*/
		$jobs = $this->jobs;
		$jops = &$this->jops;
		$mode = $this->mode;
		$name = $job['name'];
		echo '--'.$name.'--';
		$jobCfg = JobConfig::getJob($name);

		if($jobc<=0) return;
		/*如果播放模式是Serial,那么我们就只对*/

		$now = time();

		switch($mode)
		{
			case ActivityMode::LOOP:
			{
				for($i=0;$i<$jobc;$i++)
				{
					$job		= &$jobs[$i];
					$cost		= $job['cost'];	
					$gain		= $job['gain'];	
					$initime	= $job['initime'];
					$dura		= $job['dura'];
					$lastTime	= $now-$initime;
					$turn = intval($lastTime/$dura);
					$job['initime'] += $dura*$turn;
					$job['prog'] = $lastTime%$dura;
				}
				break;
			}
			case ActivityMode::SERL:
			{
				$lastTime = 1;
				while($lastTime > 0)
				{
					$job	= &$jobs[0];
					$initime = $job['initime'];
					$lastTime = $now-$initime;
					$state	= $job['state'];
					if($state != JobState::DOING)
					{
						return Utility::retFunc(false,'Can not snap a none-DOING job in a SERL Activity');
					}
					$cost = $job['cost'];	
					$gain = $job['gain'];	
					$dura = $job['dura'];

					if($lastTime < $dura)
					{
						$job['prog'] = $lastTime%$dura;
						break;
					}else{
						array_push($jops,$job['name']);//array_push() 函数向第一个参数的数组尾部添加一个或多个元素（入栈），然后返回新数组的长度。
						array_shift($jobs);
						$jobc = count($jobs);
						if( $jobc > 0)
						{
							$jobs[0]['initime']	=	$initime +$dura;
							$jobs[0]['prog']	=	0;
							$jobs[0]['state']	=	JobState::DOING;
						}
						$lastTime -= $dura;
					}				
				}
				break;
			}
			case ActivityMode::PARA:
			{
				foreach($jobs as $key=>$value)
				{
					$job = &$jobs[$key];
					if($job['state']!=JobState::DOING)
					{
						return Utility::retFunc(false,'Can not snap a none-DOING job in a PARA Activity');
					}
					$cost		= $value['cost'];	
					$gain		= $value['gain'];	
					$dura		= $value['dura'];
					$initime	= $value['initime'];
					$lastTime	= $now-$initime;
					if($lastTime < $dura)
					{
						$job['prog'] = $lastTime%$dura;
					}else{
						$job['state'] = JobState::DONE;	
					}				
				}
				break;
			}
			default:
			{
				return Utility::retFunc(false,"failure! Bad mode ".$this->mode);
			}
		}
	}

	
	public function start()
	{
		if($this->state != 'none')
		{
			die("state error:$this->state");
		}
		if($this->initime != -1)
		{
			die("initime error:$this->iniTime");
		}
		if($this->count < 1)
		{
			die("count error:$this->count");
		}
		$this->state = 'doing';
		$this->initime = time();

	}


	public function finish()
	{
		
		if($this->state != 'doing')
		{
			die(" the state is not doing!");
		}
		if($this->initime == -1)
		{
			die("initime error:-1");
		}
		if($this->count != 0)
		{
			die("count error:$this->count");
		}
		$this->state = 'none';
		$this->initime = -1;	
		$this->order = -1;
	}

}
