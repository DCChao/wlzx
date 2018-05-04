<?php
// +----------------------------------------------------------------------
// | 系统设置页
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
// |作者：郝峥
// +----------------------------------------------------------------------

namespace Home\Controller;
use OT\DataDictionary;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class SettingsController extends HomeController {

	//系统首页
    public function index(){

        $category = D('Category')->getTree();
        $this->assign('category',$category);//栏目

        //校区数据
        $arealist = arealist();
        $this->assign('arealist',$arealist);

        //巡检内容列表
        $InspectionAttribute = new \Inspection\Model\InspectionAttributeModel();
        $InspectionAttributeList = $InspectionAttribute->getList();
        $this->assign('InspectionAttributeList',$InspectionAttributeList);

        $this->display();
    }
    
    /**
     * 添加配线间
     */
    public function update(){

        $id = I('post.id','');
        $pid = I('post.area','0');
        $name = I('post.name','');
        
        $data['id'] = $id;
        $data['pid'] = $pid;
        $data['uid'] = is_login();
        $data['name'] = $name;
        $data['description'] = '';

        //dump($data);UID

        //构造post数据用于自动验证
        $_POST = $data;

       $res = D('Location')->update($data);

        if($res){
            $this->success('添加位置成功！', U('Home/Settings/index'));
        }else{
            if(isset($res)){
                $this->error(D('Location')->getError());
            }else{
                $this->error('批量导入失败，请检查内容格式！');
            }
        }
        $Location = D('Location')->order('id')->select();
        $this->assign('Location',$Location);
        $this->display('index');
        
    }

    /**
     * 巡检内容设置
     * 
     */
    function inspectionAttribute(){
        $data = I('post.');
        $data['id'] = I('get.id');

        $_POST = $data;

        $InspectionAttribute = new \Inspection\Model\InspectionAttributeModel();
        $info = $InspectionAttribute->update();
        if(!$info){
			$this->error($InspectionAttribute->getError());
        }

        $this->success('操作完成', U('Home/Settings/index'));
    }

     /**
     * 巡检内容修改，应该可以和上边合并，懒得改了
     * 
     */
    function inspectionAttributeUpdata(){
        $data = I('get.');

        $_POST = $data;

        $InspectionAttribute = new \Inspection\Model\InspectionAttributeModel();
        $info = $InspectionAttribute->update();
        if(!$info){
			$this->error($InspectionAttribute->getError());
        }

        $this->success('操作完成', U('Home/Settings/index'));
    }

     /**
     * 巡检内容删除
     * 
     */
    function inspectionAttributeDelete(){
        $id = I('get.id');

        $InspectionAttribute = new \Inspection\Model\InspectionAttributeModel();
        $res = $InspectionAttribute->delData($id);
        if($res){
            $this->success('删除成功！', U('Home/Settings/index'));
        }else{
            $this->error('删除失败！');
        }


        //$this->success('操作完成', U('Home/Settings/index'));
    }

}