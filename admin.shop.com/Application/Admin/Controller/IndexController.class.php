<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display();
    }
    public function top(){
        $this->display();
    }
    
    /**
     * 展示菜单列表
     */
    public function menu(){
        $menu_model = D('Menu');
        $menus   = $menu_model->getMenuList();
        $this->assign('menus', $menus);
        $this->display();
    }
    
    public function main(){
        $this->display();
    }
    
}