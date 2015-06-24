<?php
class Storage
{
	private $i;
	private $t;
	private $v;
	private $s;
	public $e;

	function toArray()
	{
		
		$data = array(
			"i"=>$this->i,
			"t"=>$this->t,
			"v"=>$this->v,
			"s"=>$this->s,
			"e"=>$this->e
		);
		return $data;

    }
	
	public function init($info)
	{
		$this->i = $info['i'];
		$this->t = $info['t'];
		$this->v = 0;
		$this->s = 0;
		$this->e = 0;
        $data=$this->toArray();
        return $data;
	}


	public function load($data)
    {
        $this->i = $data['i'];
        $this->t = $data['t'];
        $this->v = $data['v'];
		$this->s = $data['s'];
		$this->e = $data['e'];
    }


	public function  add($type,$count)
	{
		$old_count = $this->e[$type];
		$old_stock = $this->s;	
		$top_stock = StorageConfig::getStorageTop($this->t,$this->v);
		$new_stock = $old_stock + $count ;
		$ret_stock = $top_stock - $old_stock;
		$dif_count = $count;
		if($new_stock > $top_stock){
			$new_stock = $top_stock;
			$dif_count = $ret_stock ;
		}
		$dif_stock = $new_stock - $old_stock;
		$this->e[$type] += $dif_count;
		$this->s = $new_stock;
		return $dif_stock;
	
	}

	/**
	 * 果仓减少水果，如果到达0，返回实际减少的水果数目
	 * FIXME:如果果仓到达0，需要减少快乐。
	 *
	 * @param int $food 减少的水果 
	 *
	 * @return int $dif_food 返回实际减少的水果
	 */
	public function dec($type,$count){
		if($count > $this->e[$type])
		{
			die("the count of $type is not enough for your need!");
		}
		$dif_count = $count;
		$this->e[$type] -= $count;
		$this->s -= $dif_count;
	}


}
