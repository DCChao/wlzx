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
 * 巡检前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends InspectionController {

	//巡检详细列表页 首页
    public function index(){

        $category = D('Category')->getTree();

        $this->assign('category',$category);//栏目
        $this->assign('lists',$lists);//列表

        $list = D('Inspection')->getList();//巡检详细列表
        $this->assign('list',$list);
                 
        $this->display();
    }

    /**
     * 配线间详细信息
     */
    public function room(){
        $lid = I('get.lid');

        $create_time = I('get.createtime');
        $detail = D('Inspection')->detail($lid,$create_time);//当前$lid巡检详细列表
        $this->assign('detail',$detail[0]);
        //js中引用tp模板标签报错看着难受
        $temperature = $detail[0]['temperature'];
        $humidity = $detail[0]['humidity'];
        $this->assign('temperature',$temperature);
        $this->assign('humidity',$humidity);

        $list = D('Inspection')->getList($lid);//当前$lid巡检详细列表
        $this->assign('list',$list);

        $GraphData = getGraphData($list);
        $this->assign('GraphData',$GraphData);

        $this->display();
    }

    /**
     * 返回$lid的温度JSON信息
     */
    public function roomGraphDataJSON(){
        $lid = I('get.lid');

        $list = D('Inspection')->getList($lid,'id asc');//当前$lid巡检详细列表
        $GraphData = getGraphData($list);
        $this->ajaxReturn($GraphData);
    }
    

}