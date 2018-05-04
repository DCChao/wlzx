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
class LocationModel extends Model{

    /* 自动验证规则 */
	protected $_validate = array(
		//array('name', '1,80', '标题长度不能超过80个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
		array('name', '', '名称已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
		array('name', 'require', '名称不能为空', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
		array('pid', '/^[\d]+$/', '优先级只能填正整数', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
	);

    /* 用户模型自动完成 */
    protected $_auto = array(
        
    );

    public function update(){
		/* 检查文档类型是否符合要求 */
		// if(!$res['status']){
		// 	$this->error = $res['info'];
		// 	return false;
		// }

		/* 获取数据对象 */
		$data = $this->field('description', true)->create();
		$data = $this->create();
		if(empty($data)){
			return false;
		}

		/* 添加或新增基础内容 */
		if(empty($data['id'])){ //新增数据
			$id = $this->add(); //添加基础内容

			if(!$id){
				$this->error = '添加基础内容出错！';
				return false;
			}
			$data['id'] = $id;
		} else { //更新数据
			$status = $this->save(); //更新基础内容
			if(false === $status){
				$this->error = '更新基础内容出错！';
				return false;
			}
		}

		//内容添加或更新完成
		return $data;

	}
   
	

}
