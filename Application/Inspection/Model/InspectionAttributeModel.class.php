<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Inspection\Model;
use Think\Model;
use Think\Page;

/**
 * 文档基础模型
 */
class InspectionAttributeModel extends Model{

	/* 自动验证规则 */
	protected $_validate = array(
		array('name', '/^[a-zA-Z]\w{0,30}$/', '文档标识不合法', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
		array('name', '', '标识已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
		array('title', 'require', '标题不能为空', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
	);

	/* 自动完成规则 */
	protected $_auto = array(
		array('update_time', NOW_TIME, self::MODEL_INSERT),
	);

	public function update(){
		/* 检查文档类型是否符合要求 */
		//$Model = new \Inspection\Model\InspectionModel();
		// $res = $Model->checkDocumentType( I('type'), I('pid') );
		// if(!$res['status']){
		// 	$this->error = $res['info'];
		// 	return false;
		// }

		/* 获取数据对象 */
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

	/**
	 * 获取巡检内容列表
	 */
	public function getList(){
		return $this->field($field)->order('id')->select();
	}

	/**
	 * 
	 * 删除巡检内容
	 */
	public function delData($id){
		$map['id'] = $id;
		if(!empty($id)){
        	$res = $this->where($map)->delete();
		}
		return $res;
	}
}
