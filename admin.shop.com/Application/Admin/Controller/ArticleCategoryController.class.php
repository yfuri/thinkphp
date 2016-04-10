<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Admin\Controller;

/**
 * 文章分类控制器
 *
 * @author Sunhong
 */
class ArticleCategoryController extends \Think\Controller{
    
    //保存模型
    protected $_model = null;

    /**
     * 控制器初始化方法
     */
    protected function _initialize() {
        $meta_titles = array(
            'index' => "文章分类管理",
            'add' => '添加文章分类',
            'edit' => '修改文章分类',
        );
        $meta_title = $meta_titles[ACTION_NAME];
        $this->assign('meta_title', $meta_title);
        $this->_model = D('ArticleCategory');
    }

    /**
     * 文章分类列表
     */
    public function index() {
        //获取搜索关键字的功能
        $cond = array();
        //模糊查询文章分类的名字
        $keyword = I('get.keyword');
        if ($keyword) {
            $cond['name'] = array('like', '%' . $keyword . '%');
        }
        //查询数据
        $result = $this->_model->getPageList($cond);
        $this->assign($result);
        $this->display();
    }

    /**
     * 添加文章分类
     */
    public function add() {
        if (IS_POST) {
            //获取数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            //添加，跳转
            if ($this->_model->add() === false) {
                $this->error(get_error($this->_model->getError()));
            } else {
                $this->success("添加文章分类成功", U('index'));
            }
        } else {
            $this->display();
        }
    }

    /**
     * 修改文章分类
     * @param integer $id 文章分类唯一标识.
     */
    public function edit($id) {
        if (IS_POST) {
            //获取数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            //保存，跳转
            if ($this->_model->save() === false) {
                $this->error(get_error($this->_model->getError()));
            } else {
                $this->success("修改文章分类成功", U('index'));
            }
        } else {
            $row = $this->_model->find($id);
            $this->assign('row', $row);
            $this->display('add');
        }
    }

    /**
     * 删除文章分类,逻辑删除
     */
    public function delete() {
        //修改文章分类状态为 -1 并在名称后加上 _del
        if ($this->_model->deleteArticleCategory() === false) {
            $this->error(get_error($this->_model->getError()));
        } else {
            $this->success("删除文章分类成功", U('index'));
        }
    }
}
