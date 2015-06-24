<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function do_post_data($url, $data, $optional_headers = null)
{
	$params = array('http' => array(
		'method' => 'POST',
		'header' =>"Content-type: application/x-www-form-urlencoded\r\n"."Content-length: ".strlen($data),
		'content' => $data,
	));
	$ctx = stream_context_create($params);
	readfile($url, false, $ctx);
	return $response;

}
class Test extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->helper('url');
		$post = $this->input->post();
		$key = $post['key'];
		$msg = array();
		switch($key)
		{
		case 'ADD_JOB':
		{
			$tid = $post['tid']  ;
			$name= $post['name'] ;
			$count = $post['count'];
			$vjid = $post['vjid'];
			$vc = $post['vc'];
			$msgarray = array('fid'=>1,'tid'=>$tid,'name'=>$name,'count'=>$count,'jid'=>$vjid,'vcount'=>$vc,);
			$msgJson = json_encode($msgarray);
			$msg=array(
				'ADD_JOB'=>$msgJson,
			);
			break;

		}
		case 'INC_JOB':
		{
			$tid = $post['tid'];
			$jid = $post['jid'];
			$count = $post['count'];
			$vcount = $post['vcount'];
			$msgarray = array('fid'=>1,'tid'=>$tid,'count'=>$count,'jid'=>$jid,'vcount'=>$vcount);
			$msgJson = json_encode($msgarray);
			$msg=array(
				'INC_JOB'=>$msgJson,
			);
			break;
		}
		case 'DEC_JOB':
		{
			$tid = $post['tid'];
			$jid = $post['jid'];
			$count = $post['count'];
			$vcount = $post['vcount'];
			$msgarray = array('fid'=>1,'tid'=>$tid,'count'=>$count,'jid'=>$jid,'vcount'=>$vcount);
			$msgJson = json_encode($msgarray);
			$msg=array(
				'DEC_JOB'=>$msgJson,
			);
			break;
		
		}
		case 'DEL_JOB':
		{
			$tid = $post['tid'];
			$jid = $post['jid'];
			$msgarray = array('fid'=>1,'tid'=>$tid,'jid'=>$jid);
			$msgJson = json_encode($msgarray);
			$msg=array(
				'DEL_JOB'=>$msgJson,
			);
			break;
		
		}
		case 'SPD_JOB':
		{
			$tid = $post['tid'];
			$jid = $post['jid'];
			$secs = $post['secs'];
			$prog = $post['prog'];
			$msgarray = array('fid'=>1,'tid'=>$tid,'jid'=>$jid,'secs'=>$secs,'prog'=>$prog);
			$msgJson = json_encode($msgarray);
			$msg=array(
				'SPD_JOB'=>$msgJson,
			);
			break;
		
		}
		case 'LIT_JOB':
		{
			$tid = $post['tid'];
			$jid = $post['jid'];
			$msgarray = array('fid'=>1,'tid'=>$tid,'jid'=>$jid);
			$msgJson = json_encode($msgarray);
			$msg=array(
				'LIT_JOB'=>$msgJson,
			);
			break;
		
		}
	case 'DOA_JOB':
		{
			$tid = $post['tid'];
			$jid = $post['jid'];
			$count = $post['count'];
			$vcount = $post['vcount'];
			$msgarray = array('fid'=>1,'tid'=>$tid,'count'=>$count,'jid'=>$jid,'vcount'=>$vcount);
			$msgJson = json_encode($msgarray);
			$msg=array(
				'DOA_JOB'=>$msgJson,
			);
			break;
		
		}

	case 'GET_FARM':
		{
			$user_name = $post['user_name'];
			$msgarray = array('user_name'=>'198407121BYKTI6');
			$msgJson = json_encode($msgarray);
			$msg = array('GET_FARM'=>$msgJson);
			break;
		}
		}
		$postdata =  http_build_query($msg);
		echo $postdata;
		$url = site_url("Fof");
		$response = do_post_data($url,$postdata);
		echo $response;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
