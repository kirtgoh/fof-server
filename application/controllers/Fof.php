<?php
/**
 *工程的顶层逻辑实现 
 *@param $unityPost的key注明客户端要对farm执行的操作，fof只负责判断客户端的操作，之后实例化farm，进入相应的功能模块
 *@author 郑菲菲
 *copyright 2014/9/18
 * */
include_once 'Farm.php';
include_once 'FarmConfig.php';
class Fof extends CI_Controller {
    public function __construct()
    {
		parent::__construct();
        $this->load->library('Mongo_db');//实现脚本能调用mongodb的函数库
	}

	function __autoload($classname)
	{
		require_once $classname.'.php';
	}

	/**
	* index是默认的程序入口
	* 判断客户端的请求是哪种消息类型，取出农场的id（fid），实例化farm
	* @param $fid 当客户端传来的消息又fid时将它付给$fid，否则将-1赋值给$fid
	* @param stripslsshes($message)是对客户端传来的json内容进行数组化 true不能没有
	*/ 
    public function index()
	{
		/*
		 * CodeIgniter 有3个 helper方法可以让用户取得POST, COOKIE 或 SERVER 的内容。用这些方法比直接使用php方法($_POST['something'])的好处是不用先检查此项目是不是存在。 直接使用php方法，必须先做如下检验： 
		 * if ( ! isset($_POST['something']))
		 * {
		 *		$something = FALSE;
		 * }else{
		 *		$something = $_POST['something'];
		 * }
		 * 用CodeIgniter内建的方法，你可以这样：$something = $this->input->post('something');
		 * 第一个参数是所要取得的post中的数据:$this->input->post('some_data');如果数据不存在，方法将返回 FALSE (布尔值)。
		 * 第二个参数是可选的，如果想让取得的数据经过跨站脚本过滤（XSS Filtering），把第二个参数设为TRUE。$this->input->post('some_data', TRUE);
		 * 不设置任何参数，该方法将以一个数组的形式返回全部POST过来的数据。
		 * 把第一个参数设置为NULL，第二个参数设置为 TRUE (boolean)，该方法将经过跨站脚本过滤，返回一个包含全部POST数据的数组。
		 * 如果POST没有传递任何数据，该方法将返回 FALSE (boolean)
		 * $this->input->post(NULL, TRUE); // 经过跨站脚本过滤 返回全部 POST 数据 
		 * $this->input->post(); // 不经过跨站脚本过滤 返回全部 POST 数据 
		 * */
		$unityPost = $this->input->post();
		
		/*
		 * reset() 将 array 的内部指针倒回到第一个单元并返回第一个数组单元的值。
		 * key() 函数返回数组中内部指针指向的当前单元的键名。 但它不会移动指针。如果内部指针超过了元素列表尾部，或者数组是空的，key() 会返回 NULL。
		 * 相关：
		 * current() - 返回数组中的当前单元
		 * next() - 将数组中的内部指针向前移动一位
		 * */
		reset($unityPost);
        $key = key($unityPost);
		$msg = $unityPost[$key];
		/*
		 * 客户端传来的数组每一项都是一个字符串，转义/"，所以首先处理接收的数据
		 * addslashes函数主要是在字符串中添加反斜杠对特殊字符进行转义，stripslashes则是去掉转义后字符串中的反斜杠\，
		 * 比如当你提交一段json数据到PHP端的时候可能会遇到json字符串中有\导致json_decode函数无法将json数据转换成数组的情况，这时你就需要stripslashes函数。
		 * 该函数用于清理从数据库或 HTML 表单中取回的数据。
		 *
		 * */
		stripslashes($msg);
		$info =json_decode($msg,TRUE);

		/*
		 * 在下面的解析消息info域的时候，会保守的使用intval(*)进行类型转换。
		 * 因为传来的Form消息有可能来自于html表单（测试时）,是以string类型传来的。
		 * intval — 获取变量的整数值
		 * 成功时返回 var 的 integer 值，失败时返回 0。 空的 array 返回 0，非空的 array 返回 1。
		 * 最大的值取决于操作系统。 32 位系统最大带符号的 integer 范围是 -2147483648 到 2147483647。
		 * 举例，在这样的系统上， intval('1000000000000') 会返回 2147483647。64 位系统上，最大带符号的 integer 值是 9223372036854775807。
		 *
		 * */
        switch($key)
		{
			/**
			 * 根据user_name获得这个玩家所在的农场,如果没有农场那么申请一个返回
			 * 先根据user_name查看luda数据库有没有这个用户，如果有这个用户，
			 * 再查看player表看有没有用这个玩家，如果有才认为这个农场存在
			 * 申请农场的逻辑：
			 * apply_farm用来给第一次登陆这个游戏的账户分配一个初始化的农场 初始化的农场一共有几种风格，
			 * 保存在farmlayout.php中，include 'farmlayout'后即   可引入其中的信息，分配农场过后，
			 * 需要将玩家的user_id与分配到的farm_id插入到关联表player中，player表用来判断用户是否登陆过游戏（根据user_id能否查到记录）
			 */

			case "GET_FARM":
			{
				$user_name = $info['user_name'];
				$this->mongo_db->switch_db("luda");
				$user_data = $this->mongo_db->where(array("user_name"=>$user_name))->get("user");
				$this->mongo_db->switch_db("fof");
				if(!$user_data)
				{
					Utility::echoMsg('APP_SYNC_FAIL','user_name'.$user_name.' doent exist in luda db');
					exit(0);
				} else {
				   $user_id = $user_data[0]['_id'];
				   $player_data = $this->mongo_db->where(array("user_id"=>$user_id))->get("player");
				
				   if(!$player_data)//说明该账户第一次登陆该游戏,调用apply_farm函数，申请一个初始化的农场,返回给客户端。
				   {
						$newfarm = FarmLayout::$farmlayout[0];
						$farm_id_exist=true;
						/*		
						while($farm_id_exist)
						{
							//$fid = rand(1,100000);
							$fid = 1;
							$farm_id_exist = $this->mongo_db->where(array("fid"=>$fid))->get('farm');
						}
						*/
						$fid = 1;
						$newfarm['fid'] = $fid;
						$day_long = FarmConfig::getWorldConfig('DayLong');
						//此处一定要把它整数化，否则客户端会解析Json出错
						$newfarm['days_gone'] = intval(time()/$day_long);
						//mongoDB在插入的时候会把key="_id"的key-value pair插入到newfarm中。所以此处要去掉这个key
						$this->mongo_db->insert('farm',$newfarm);
						$this->mongo_db->insert('player',array("user_id"=>$user_id,"fid"=>$fid));
						unset($newfarm['_id']);
						Utility::echoMsg('GET_FARM_ACK',$new_farm);
				   } 
				   else {
						//该用户已经登陆过该游戏，数据库中有他的农场信息 直接返回给客户端
						$fid = $player_data[0]['fid'];
						$farm = new Farm($fid);
						$farm_data = $farm->snapActivity();
						Utility::echoMsg('GET_FARM_ACK',$farm_data);
				   }
				}
				break;
			}
			/*
			 * FIXME:
			 * GET_CONFIG应该发送客户端本地的版本号，如果版本号和服务器的不一致，那么就要返回服务器的CONFIG文件，
			 * 
			 * */
			case "GET_CONFIG":
			{
				$config_data = FarmConfig::toArray();
				Utility::echoMsg('GET_CONFIG_ACK', $config_data);
			    break;	
			}
			/*
			 * 对Activity[tid].Q[qid]增加一个Job Queue Element. 这个JQE的名字是name.之所以是“增加”而不是“设置”是因为。这个动作明显是增加了Q.
			 * name可能是"any",表达这是一个万能JQE(可以容纳任何job).对于猪圈，增加了一头猪。对于磨坊，增加了一个工作槽。
			 *
			 * */
			case "ADD_JQE":
			{
				$fid	= $info['fid'];
				$farm	= new farm($fid);
				$name	= $info['name'];
				$tid	= intval($info['tid']);
				$qid    = intval($info['qid']); 
				$ret	= $farm->addJqe($name,$tid,$qid);
				if($ret['flag'] == 'ok'){
					Utility::echoMsg('ADD_JQE_ACK','ok');
				}else{
					Utility::echoMsg('ADD_JQE_FAIL',$ret['text']);
					exit(0);
				}

				break;
			}

			case "ADD_JOB":
			{
				$fid = $info['fid'];
				$name	= $info['name'];
				$tid	= intval($info['tid']);
				$qid    = intval($info['qid']); 
				$count	= intval($info['count']);

				$farm = new farm($fid);
				$ret = $farm->addJob($name,$tid,$qid,$count);
				if($ret['flag'] == 'ok'){
					Utility::echoMsg('ADD_JOB_ACK','ok');
				}else{
					Utility::echoMsg('ADD_JOB_FAIL',$ret['text']);
					exit(0);
				}
				break;
			}
			case "INC_JOB":
			{
				$fid = $info['fid'];
				$name	= $info['name'];
				$tid	= intval($info['tid']);
				$qid    = intval($info['qid']); 
				$count	= intval($info['count']);
				$farm = new farm($fid);
				$ret = $farm->incJob($tid,$qid,$count);
				if($ret['flag'] == 'ok'){
					Utility::echoMsg('INC_JOB_ACK','ok');
				}else{
					Utility::echoMsg('INC_JOB_FAIL',$ret['text']);
					exit(0);
				}
				break;
			}
			case "DEC_JOB":
			{
				$fid = $info['fid'];
				$farm = new farm($fid);
				$farm->decJob($info);
				break;
			}
			case "DEL_JOB":
			{
				$fid = $info['fid'];
				$farm = new farm($fid);
				$farm->delJob($info);
				break;
			}
			case "SPD_JOB":
			{
				$fid = $info['fid'];
				$name	= $info['name'];
				$tid	= intval($info['tid']);
				$qid    = intval($info['qid']); 
				$secs	= intval($info['secs']);
				$prog	= intval($info['prog']);
				$farm = new farm($fid);
				$farm->spdJob($tid,$qid,$secs,$prog);
				break;
			}
			case "LIT_JOB":
			{
				$fid = $info['fid'];
				$farm = new farm($fid);
				$farm->litJob($info);
				break;
			}
			case "DOA_JOB":
			{
				$fid = $info['fid'];
				$farm = new farm($fid);
				$ret = $farm->doaJob($info);
				Utility::echoMsg('DOA_JOB_ACK',$ret);
				break;
			}

			case "CREATE_FARM":
			{
				//$farm->create_farm($unityPost);
				break;
			}
			case "ADD_BUILDING":
			{
				$fid = $info['fid'];
				$farm = new farm($fid);
				$farm->add_building($info);
	            break;
		    }
			case "DEL_BUILDING":
			{
				$fid = $info['fid'];
				$farm = new farm($fid);
		        $farm->del_building($unityPost);
			    break;
			}
			case "MOV_BUILDING":
			{
				$fid = $info['fid'];
				$farm = new farm($fid);
				$name = $info['name'];
				$bid  = $info['b_id'];
				$newr = $info['r'];
				$newc = $info['c'];
				$ret = $farm->moveBuilding($name,$bid,$newr,$newc);
				if($ret == 'ok'){
					Utility::echoMsg('MOV_BUILDING_ACK','ok');
				}else{
					Utility::echoMsg('APP_SYNC_FAIL',$ret);
					exit(0);
				}
				break;
			}
			case "UPG_BUILDING":
			{
				$fid = $info['fid'];
				$farm = new farm($fid);

				$name = $info['name'];
				$bid  = $info['b_id'];
				$ret = $farm->upgradeBuilding($name,$bid);
				if($ret == 'ok'){
					Utility::echoMsg('UPG_BUILDING_ACK','ok');
				}else{
					Utility::echoMsg('APP_SYNC_FAIL',$ret);
					exit(0);
				}
				break;
			}
			case "UPG_BOVER":
			{
				$fid = $info['fid'];
				$farm = new farm($fid);

				$name = $info['name'];
				$bid  = $info['b_id'];
				$ret = $farm->upgradeBuildingOver($name,$bid);
				if($ret == 'ok'){
					Utility::echoMsg('UPG_BOVER_ACK','ok');
				}else{
					Utility::echoMsg('APP_SYNC_FAIL',$ret);
					exit(0);
				}
				break;
			}
			case "RCL_BUILDING":
			{
				$fid = $info['fid'];
				$farm = new farm($fid);

				$name = $info['name'];
				$bid  = $info['b_id'];
				$ret = $farm->recycleBuilding($name,$bid);
				if($ret == 'ok'){
					Utility::echoMsg('RCL_BUILDING_ACK','ok');
				}else{
					Utility::echoMsg('APP_SYNC_FAIL',$ret);
					exit(0);
				}
				break;
			}
			case "RCL_BOVER":
			{
				$fid = $info['fid'];
				$farm = new farm($fid);

				$name = $info['name'];
				$bid  = $info['b_id'];
				$ret = $farm->recycleBuildingOver($name,$bid);
				if($ret == 'ok'){
					Utility::echoMsg('RCL_BUILDINGOVER_ACK','ok');
				}else{
					Utility::echoMsg('APP_SYNC_FAIL',$ret);
					exit(0);
				}
				break;
			}



			case "DWG_BUILDING":
			{
				$fid = $info['fid'];
				$farm = new farm($fid);

				$farm->dwg_building($unityPost);
				break;
			}
			case "CLEAN_BUILDING":
			{
				//$farm->clean_building($unityPost);
				break;
			}
			case "BST_BUILDING":
				{
				$fid = $info['fid'];
				$farm = new farm($fid);

				$name = $info['name'];
				$bid  = $info['b_id'];
				$ret = $farm->boostBuilding($name,$bid);
				if($ret == 'ok'){
					Utility::echoMsg('BST_BUILDING_ACK','ok');
				}else{
					Utility::echoMsg('APP_SYNC_FAIL',$ret);
					exit(0);
				}
				break;
			}
			case "BST_BOVER":
				{
					$fid = $info['fid'];
				$farm = new farm($fid);

				$name = $info['name'];
				$bid  = $info['b_id'];
				$ret = $farm->boostBuildingOver($name,$bid);
				if($ret == 'ok'){
					Utility::echoMsg('BST_BOVER_ACK','ok');
				}else{
					Utility::echoMsg('APP_SYNC_FAIL',$ret);
					exit(0);
				}
				break;
			}

			case "CAL_BUILDING":
				{
					$fid = $info['fid'];
				$farm = new farm($fid);

				$name = $info['name'];
				$bid  = $info['b_id'];
				$ret = $farm->cancelBuilding($name,$bid);
				if($ret == 'ok'){
					Utility::echoMsg('CAL_BUILDING_ACK','ok');
				}else{
					Utility::echoMsg('APP_SYNC_FAIL',$ret);
					exit(0);
				}
				break;
			}

			case "FNS_BUILDING":
				{
				$fid = $info['fid'];
				$farm = new farm($fid);

				$name = $info['name'];
				$bid  = $info['b_id'];
				$ret = $farm->finishBuilding($name,$bid);
				if($ret == 'ok'){
					Utility::echoMsg('FNS_BUILDING_ACK','ok');
				}else{
					Utility::echoMsg('APP_SYNC_FAIL',$ret);
					exit(0);
				}
				break;
			}

			default:
			{
				echo "The opration is wrong!";
			}	
        }

	}

    private function apply_farm($user_id)
    {
        $newfarm = FarmLayout::$farmlayout[0];
        $farm_id_exist=true;

/*		while($farm_id_exist)
		{
			//$fid = rand(1,100000);
			$fid = 1;
			$farm_id_exist = $this->mongo_db->where(array("fid"=>$fid))->get('farm');
		}
 */
			$fid = 1;
		$newfarm['fid'] = $fid;
		$day_long = FarmConfig::getWorldConfig('DayLong');
		//此处一定要把它整数化，否则客户端会解析Json出错
		$newfarm['days_gone'] = intval(time()/$day_long);
		$this->mongo_db->insert('farm',$newfarm);
		$this->mongo_db->insert('player',array("user_id"=>$user_id,"fid"=>$fid));
		//mongoDB在插入的时候会把key="_id"的key-value pair插入到newfarm中。所以此处要去掉这个key
		unset($newfarm['_id']);
		return $newfarm;

    }


	private function getFarm($user_name)
	{


	
	}
}

