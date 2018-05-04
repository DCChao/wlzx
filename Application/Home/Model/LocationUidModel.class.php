<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Model;
use Think\Model;
use User\Api\UserApi;
use Admin\Model\AuthGroupModel;

/**
 * 文档基础模型
 */
class LocationUidModel extends Model{

    /* 自动验证规则 */
	protected $_validate = array(
		//array('lid', '/^[\d]+$/', '优先级只能填正整数', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
	);

    /* 用户模型自动完成 */
    protected $_auto = array(
        
	);
	

	/**
	 * 新增或添加一条文章详情
	 * @param  number $uid 用户ID
	 * @param  number $lid 区域ID
	 * @return boolean    true-操作成功，false-操作失败
	 * @author 郝峥
	 */

    public function update($uid,$lid){
		/* 获取文章数据 */ //TODO: 根据不同用户获取允许更改或添加的字段
		$data['uid'] = $uid;
		$data['location'] = $lid;

		$map['uid'] = $uid;
		$vfc = $this->where($map)->delete();
		$status = $this->add($data);
		if(false === $status){
			$this->error('更新内容失败！');
			return false;
		}

		return true;
	}



}
