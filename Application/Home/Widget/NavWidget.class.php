<?php

namespace Home\Widget;
use Think\Action;

/**
 * 分类widget
 * 用于动态调用导航信息
 */

class NavWidget extends Action{
	
	/* 显示指定分类的同级分类或子分类列表 */
	public function sub_nav($pid){
		$subnav = subnav($pid);
		$this->assign('subnav', $subnav);
		$this->display('Widget/subnav');
	}
	
}
