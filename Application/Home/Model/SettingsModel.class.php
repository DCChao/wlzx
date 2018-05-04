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

/**
 * 文档基础模型
 */
class SettingsModel extends Model{

    /* 自动验证规则 */
	protected $_validate = array(
		array('name', '/^[a-zA-Z]\w{0,30}$/', '文档标识不合法', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
		array('name', '', '标识已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
		array('title', 'require', '标题不能为空', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
		array('category_id', 'require', '分类不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_INSERT),
		array('category_id', 'require', '分类不能为空', self::EXISTS_VALIDATE , 'regex', self::MODEL_UPDATE),
		array('category_id,type', 'checkCategory', '内容类型不正确', self::MUST_VALIDATE , 'callback', self::MODEL_INSERT),
		array('category_id', 'checkCategory', '该分类不允许发布内容', self::EXISTS_VALIDATE , 'callback', self::MODEL_BOTH),
		array('model_id,category_id', 'checkModel', '该分类没有绑定当前模型', self::MUST_VALIDATE , 'callback', self::MODEL_INSERT),
	);

    /* 用户模型自动完成 */
    protected $_auto = array(
        array('login', 0, self::MODEL_INSERT),
        array('reg_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
        array('reg_time', NOW_TIME, self::MODEL_INSERT),
        array('last_login_ip', 0, self::MODEL_INSERT),
        array('last_login_time', 0, self::MODEL_INSERT),
        array('update_time', NOW_TIME),
        array('status', 1, self::MODEL_INSERT),
    );

    public function update(){
		/* 检查文档类型是否符合要求 */
		$Model = new \Admin\Model\SettingsModel();
		$res = $Model->checkDocumentType( I('type'), I('pid') );
		if(!$res['status']){
			$this->error = $res['info'];
			return false;
		}

		/* 获取数据对象 */
		$data = $this->field('pos,display', true)->create();
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

		/* 添加或新增扩展内容 */
		$logic = $this->logic($data['model_id']);
		if(!$logic->update($data['id'])){
			if(isset($id)){ //新增失败，删除基础数据
				$this->delete($data['id']);
			}
			$this->error = $logic->getError();
			return false;
		}

		//内容添加或更新完成
		return $data;

	}
   


}
