<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Admin\Controller;

/**
 * 文章控制器
 *
 * @author Sunhong
 */
class ArticleController extends \Think\Controller{
    
    //保存模型
    protected $_model = null;

    /**
     * 控制器初始化方法
     */
    protected function _initialize() {
        $meta_titles = array(
            'index' => "文章管理",
            'add' => '添加文章',
            'edit' => '修改文章',
        );
        $meta_title = $meta_titles[ACTION_NAME];
        $this->assign('meta_title', $meta_title);
        $this->_model = D('Article');
    }

    /**
     * 文章列表
     */
    public function index() {
        //获取搜索关键字的功能
        $cond = array();
        //模糊查询文章的名字
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
     * 添加文章
     */
    public function add() {
        if (IS_POST) {
            //获取数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            //添加，跳转
            if ($this->_model->addArticle() === false) {
                $this->error(get_error($this->_model->getError()));
            } else {
                $this->success("添加文章成功", U('index'));
            }
        } else {
            //获取分类列表
            $categorys = D('ArticleCategory')->getPageList();
            $this->assign('categorys',$categorys);
            $this->display();
        }
    }

    /**
     * 修改文章
     * @param integer $id 文章唯一标识.
     */
    public function edit($id) {
        if (IS_POST) {
            //获取数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            //保存，跳转
            if ($this->_model->saveArticle() === false) {
                $this->error(get_error($this->_model->getError()));
            } else {
                $this->success("修改文章成功", U('index'));
            }
        } else {
            $row = $this->_model->find($id);
            //获取文章内容
            $content = M('ArticleContent')->where(array('article_id'=>$row['id']))->getField('content');
            $row['content'] = $content;
            $row = array($row);
            $this->_model->getCategory($row);
            //获取分类列表
            $categorys = D('ArticleCategory')->getPageList();
            $this->assign('categorys',$categorys);
            $this->assign('row', $row);
            $this->display('add');
        }
    }

    /**
     * 删除文章,逻辑删除
     */
    public function delete() {
        //修改文章分类状态为 -1 并在名称后加上 _del
        if ($this->_model->deleteArticle() === false) {
            $this->error(get_error($this->_model->getError()));
        } else {
            $this->success("删除文章成功", U('index'));
        }
    }
}
