<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-11
 * Time: PM1:13
 */

namespace Member\Controller;

use User\Api\UserApi;

class ProfileController extends MemberController{

    public function _initialize(){
        parent::_initialize();

    }
    public function index($uid = null){
        $uid = is_login();
        //调用API获取基本信息
        $User = new UserApi;
        list($info['id'],$info['username'],$info['email'],$info['mobile']) = $User->info($uid);
        $map['uid'] = $uid;

        $field = 'nickname,sex,birthday,qq,score,reg_time,status';
        $member = M('Member')->where($map)->field($field)->find();
        $info = $info + $member;
        //显示页面
        $this->assign('user', $info);
        $this->display();
    }

/**
 * 退出
 */
    public function logout(){
        //调用退出登录的API
        $this->requireLogin();
        //调用用户中心
        $model = D('Home/Member');
        $model->logout();
        session_destroy();
        //显示页面
        $this->success('退出成功', U('Home/Index/index'));
    }

    /**
     * 修改密码提交
     * @author huajie <banhuajie@163.com>
     */
    public function changePassword(){
        if ( !is_login() ) {
            $this->error( '您还没有登陆',U('User/login') );
        }
        if ( IS_POST ) {
            //获取参数
            $uid        =   is_login();
            $password   =   I('post.old');
            $repassword = I('post.repassword');
            $data['password'] = I('post.password');
            empty($password) && $this->error('请输入原密码');
            empty($data['password']) && $this->error('请输入新密码');
            empty($repassword) && $this->error('请输入确认密码');

            if($data['password'] !== $repassword){
                $this->error('您输入的新密码与确认密码不一致');
            }

            $Api = new UserApi();
            $res = $Api->updateInfo($uid, $password, $data);
            if($res['status']){
                $this->success('修改密码成功！');
            }else{
                $this->error($res['info']);
            }
        }else{
            $this->display();
        }
    }

    public function updataProfile(){
        $data = array();
        if (IS_POST) {
            $data['uid'] = is_login();
            $data['nickname'] = I('post.nickname');
            $data['sex'] = I('post.sex');
            $data['birthday'] = I('post.birthday');
            $Member = D('Member');
            
            if ($Member->updataMember($data)) {
                $this->success('资料修改成功');
            }
        }
    }

    
}