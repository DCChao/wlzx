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
class InspectionModel extends Model{

	/* 自动验证规则 */
	protected $_validate = array(
		// array('name', '/^[a-zA-Z]\w{0,30}$/', '文档标识不合法', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
	);

	/* 自动完成规则 */
	protected $_auto = array(
		array('uid', 'session', self::MODEL_INSERT, 'function', 'user_auth.uid'),
		array('create_time', NOW_TIME, self::MODEL_INSERT),
	);

    public $page = '';





	/**
	 * 获取详情页数据
	 * $lid 配线间id
	 * $create_time 巡检时间
	 */
	public function detail($lid,$create_time){

		$map['lid'] = $lid;
		//无巡检时间参数显示最后一个
		if (!empty($create_time)) {
			$map['create_time'] = $create_time;
		}
		return $this->field($field)->where($map)->order('id desc')->limit(1)->select();

	}

	/**
	 * 返回前一篇文档信息
	 * @param  array $info 当前文档信息
	 * @return array
	 */
	public function prev($info){
		$map = array(
			'id'          => array('lt', $info['id']),
			'pid'		  => 0,
			'category_id' => $info['category_id'],
			'status'      => 1,
		);

		/* 返回前一条数据 */
		return $this->field(true)->where($map)->order('id DESC')->find();
	}

	/**
	 * 获取下一篇文档基本信息
	 * @param  array    $info 当前文档信息
	 * @return array
	 */
	public function next($info){
		$map = array(
			'id'          => array('gt', $info['id']),
			'pid'		  => 0,
			'category_id' => $info['category_id'],
			'status'      => 1,
		);

		/* 返回下一条数据 */
		return $this->field(true)->where($map)->order('id')->find();
	}

	public function update(){
		/* 检查文档类型是否符合要求 */
		$Model = new \Inspection\Model\InspectionModel();
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
     * 获取根节点id
     * @return integer 数据id
     * @author huajie <banhuajie@163.com>
     */
    protected function getRoot(){
    	$pid = I('post.pid');
    	if($pid == 0){
    		return 0;
    	}
    	$p_root = $this->getFieldById($pid, 'root');
    	return $p_root == 0 ? $pid : $p_root;
    }

	/**
	 * 验证分类是否允许发布内容
	 * @param  integer $id 分类ID
	 * @return boolean     true-允许发布内容，false-不允许发布内容
	 */
	protected function checkCategory($id){
		if(is_array($id)){
			$type = get_category($id['category_id'], 'type');
			return in_array($id['type'], $type);
		} else {
			$publish = get_category($id, 'allow_publish');
			return $publish ? true : false;
		}
	}

	/**
	 * 获取巡检内容列表
	 * 无参数显示全部列表
	 * 有参数显示当前$lid配线间全部列表
	 */
	public function getList($lid,$order = 'id desc'){
		//判断是否有参数
		if (!empty($lid)) {
			$map['lid'] = $lid;
		}
		return $this->field($field)->where($map)->order($order)->select();
	}


}
