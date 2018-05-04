<?php
// +----------------------------------------------------------------------
// | 机房巡检
// +----------------------------------------------------------------------
// | Copyright (c) 2018 郝峥 All rights reserved.
// +----------------------------------------------------------------------
// | Author: 郝峥 <dcchao@126.com> 
// +----------------------------------------------------------------------

namespace Inspection\Controller;
use OT\DataDictionary;

/**
 * 巡检编辑控制器
 * 主要获取首页聚合数据
 */
class ReportController extends InspectionController {

	//巡检首页
    public function index(){

        $lid = getlocationID(UID);
        $areaID = I('get.pid',$lid);

        $category = D('Category')->getTree();
        //校区数据
        $arealist = arealist();
        $this->assign('arealist',$arealist);

        $this->assign('areaID',$areaID);
                 
        $this->display();
    }

    /**
     * 机房巡检编辑
     */
    function edit(){
        

        $rid = I('get.rid');//房间id
        $this->assign('rid',$rid);

        //上次巡检内容
        $map['lid'] = $rid;
        $list = D('Inspection')->where($map)->order('id desc')->limit(1)->select();
        $this->assign('list',$list[0]);

        //配线间名称
        $lname = getLocationName($rid);
        $this->assign('lname',$lname);
        //面包屑导航
        $LocationNameAll = getLocationNameAll($rid);
        $this->assign('LocationNameAll',$LocationNameAll);

        //巡检内容
        $content = D('InspectionAttribute')->getList();
        foreach ($content as $k => $value) {
           $content[$k]['extra'] = str2arrNum($value['extra']);
        }
        $this->assign('content',$content);

        //校区数据
        $arealist = arealist();
        $this->assign('arealist',$arealist);
        $this->display();
        
    }

    /**
     * 机房巡检数据更新
     */
    function update(){

        $rid = I('get.rid');//房间id
        $this->assign('rid',$rid);
        
        $data = I('post.');
        $data['lid'] = intval($rid);

        $map['lid'] = $rid;
        $list = D('Inspection')->where($map)->order('id desc')->limit(1)->select();
        if (date('Y-m-d') == date('Y-m-d',$list[0]['create_time'])) {
            $data['id'] = $list[0]['id'];
        }

        $_POST = $data;

        $res = D('Inspection')->update($data);
        if(!$res){
            $this->error('提交失败');
        }else{
            $this->success($res['id']?'更新成功':'新增成功');
        }
        
    }
}