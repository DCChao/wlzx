<?php
/**
 *蜜耘工作室
 *登陆  退出
 */

namespace Member\Controller;
use Think\Controller;
use User\Api\UserApi;

class IndexController extends MemberController{

    /* 空操作，用于输出404页面 */
    public function _empty(){
        $this->redirect('Home/Index/index');
    }


    // protected function _initialize(){
    //     /* 读取站点配置 */
    //     $config = api('Config/lists');
    //     C($config); //添加配置

    //     if(!C('WEB_SITE_CLOSE')){
    //         $this->error('站点已经关闭，请稍后访问~');
    //     }
    // }

    public function index(){
        $this->display('Profile/index');
        
    }

    /* 注册页面 */
    public function register($username = '', $password = '', $repassword = '', $email = '', $verify = ''){
        if(!C('USER_ALLOW_REGISTER')){
            $this->error('注册已关闭');
        }
        if(IS_POST){ //注册用户
            /* 检测验证码 */
            if(!check_verify($verify)){
                $this->error('验证码输入错误！');
            }

            /* 检测密码 */
            if($password != $repassword){
                $this->error('密码和重复密码不一致！');
            }           

            /* 调用注册接口注册用户 */
            $User = new UserApi;
            $uid = $User->register($username, $password, $email);
            if(0 < $uid){ //注册成功
                //TODO: 发送验证邮件
                send_mail($email,'111','222');
                $this->success('注册成功！',U('login'));
            } else { //注册失败，显示错误信息
                $this->error($this->showRegError($uid));
            }

        } else { //显示注册表单
            $this->display();
        }
    }

    /* 登录页面 */
    public function login($username = '', $password = '', $verify = ''){
        if(IS_POST){ //登录验证
            /* 检测验证码 */
            if(!check_verify($verify)){
                $this->error('验证码输入错误！');
            }

            /* 调用UC登录接口登录 */
            $user = new UserApi;
            $uid = $user->login($username, $password);
            if(0 < $uid){ //UC登录成功
                /* 登录用户 */
                $Member = D('Member');
                if($Member->login($uid)){ //登录用户
                    //TODO:跳转到登录前页面
                    $this->success('登录成功！',U('Home/Index/index'));
                } else {
                    $this->error($Member->getError());
                }

            } else { //登录失败
                switch($uid) {
                    case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
                    case -2: $error = '密码错误！'; break;
                    default: $error = '未知错误！'; break; // 0-接口参数错误（调试阶段使用）
                }
                $this->error($error);
            }

        } else { //显示登录表单
            $this->display();
        }
    }

    /* 退出登录 */
    public function logout(){
        if(is_login()){
            D('Member')->logout();
            $this->success('退出成功！', U('Index/login'));
        } else {
            $this->redirect('Index/login');
        }
    }

    /* 验证码，用于登录和注册 */
    public function verify(){
        $verify = new \Think\Verify();
        $verify->entry(1);
    }

    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     */
    private function showRegError($code = 0){
        switch ($code) {
            case -1:  $error = '用户名长度必须在16个字符以内！'; break;
            case -2:  $error = '用户名被禁止注册！'; break;
            case -3:  $error = '用户名被占用！'; break;
            case -4:  $error = '密码长度必须在6-30个字符之间！'; break;
            case -5:  $error = '邮箱格式不正确！'; break;
            case -6:  $error = '邮箱长度必须在1-32个字符之间！'; break;
            case -7:  $error = '邮箱被禁止注册！'; break;
            case -8:  $error = '邮箱被占用！'; break;
            case -9:  $error = '手机格式不正确！'; break;
            case -10: $error = '手机被禁止注册！'; break;
            case -11: $error = '手机号被占用！'; break;
            default:  $error = '未知错误';
        }
        return $error;
    }
}