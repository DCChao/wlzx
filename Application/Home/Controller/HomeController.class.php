<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class HomeController extends Controller {

	/* 空操作，用于输出404页面 */
	public function _empty(){
		$this->redirect('Index/index');
	}

	//自执行
    protected function _initialize(){
        // 获取当前用户ID
        define('UID',is_login());
        // if( !UID ){// 还没登录 跳转到登录页面
        //     $this->redirect('Index/index');
        // }
        /* 读取站点配置 */
        $config = api('Config/lists');
        C($config); //添加配置

        if(!C('WEB_SITE_CLOSE')){
            $this->error('站点已经关闭，请稍后访问~');
        }

        /* 用户登录检测 */
        $uid = $_REQUEST['uid'] ? $_REQUEST['uid'] : is_login();
        if (!$uid) {
            $this->redirect('Member/Login/index');
            //$this->error('需要登录', U('Member/Login/index'));
        }
        $this->assign('uid', $uid);
        $this->mid = is_login();

        
    }

	/* 用户登录检测 */
	protected function login(){
		/* 用户登录检测 */
		is_login() || $this->error('您还没有登录，请先登录！', U('User/login'));
	}




	// public function _initialize(){
 //        $uid = $_REQUEST['uid'] ? $_REQUEST['uid'] : is_login();
 //        if (!$uid) {
 //            $this->error('需要登录', U('Login/index'));
 //        }
 //        $this->assign('uid', $uid);
 //        $this->mid = is_login();
 //    }

 //    protected function defaultTabHash($tabHash){
 //        $tabHash = $_REQUEST['tabHash'] ? $_REQUEST['tabHash'] : $tabHash;
 //        $this->assign('tabHash', $tabHash);
 //    }

 //    protected function getCall($uid)
 //    {
 //        if ($uid == is_login()) {
 //            return '我';
 //        } else {
 //            $apiProfile = callApi('User/getProfile', array($uid));
 //            return $apiProfile['sex'] == 'm' ? '他' : '她';
 //        }
 //    }

 //    protected function ensureApiSuccess($result)
 //    {
 //        if (!$result['success']) {
 //            $this->error($result['message'], $result['url']);
 //        }
 //    }

 //    protected function requireLogin()
 //    {
 //        if (!is_login()) {
 //            $this->error('必须登录才能操作');
 //        }
 //    }

}
