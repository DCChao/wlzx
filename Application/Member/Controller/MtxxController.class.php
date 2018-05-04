<?php
/**
 * 美图秀秀图片上传
 */

namespace Member\Controller;

use User\Api\UserApi;

class MtxxController extends MemberController{

    public function uploadPicture(){

        $uid = $uid = is_login();
        /* 返回标准数据 */
        $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
        /* 调用文件上传组件上传文件 */
        $Picture = D('Picture');
        $pic_driver = C('PICTURE_UPLOAD_DRIVER');
        $info = $Picture->upload(
            $_FILES,
            C('HPIC_UPLOAD'),
            C('PICTURE_UPLOAD_DRIVER'),
            C("UPLOAD_{$pic_driver}_CONFIG")
        ); //TODO:上传到远程服务器

        //更新头像数据
        if ($info) {
            $Member = D('Member');
            $mem = $Member->getMember($uid);
            if (!empty($mem['avatar_id'])) {
                D('Picture')->del($mem['avatar_id']);
            }

            $data['uid'] = $uid;
            $data['avatar_id'] = $info['Filedata']['id'];
            if ($Member->updataMember($data)) {
                return true;
            }else{
                return '更新头像失败';
            }
        }

    }

 
   
}