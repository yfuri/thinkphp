<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Admin\Model;

/**
 * Description of GoodsModel
 *
 * @author Sunhong
 */
class GoodsModel extends \Think\Model{
    
    /**
     * 商品的状态
     * @var type 
     */
    public $goods_status = array(
        1 => '精品',
        2 => '新品',
        4 => '热销'
    );
    
    /**
     * 商品的上架状态
     * @var type 
     */
    public $is_on_sales = array(
        1 => '上架',
        0 => '下架'
    );

    /**
     * 自动验证
     * @var type 
     */
    protected $_validate = array(
        array('name', 'require', '商品名称不能为空', self::EXISTS_VALIDATE, '', self::MODEL_INSERT),
        array('goods_category_id', 'require', '商品分类不能为空', self::EXISTS_VALIDATE, '', self::MODEL_INSERT),
        array('stock', '/^\d+$/', '商品库存输入有误', self::EXISTS_VALIDATE, '', self::MODEL_INSERT),
        array('shop_price', '/^\d+$/', '售价输入有误', self::EXISTS_VALIDATE, '', self::MODEL_INSERT),
    );
    
    /**
     * 自动完成商品状态的处理与录入时间
     * @var type 
     */
    protected $_auto = array(
        array('goods_status','array_sum', self::EXISTS_VALIDATE, 'function'),
        array('inputtime', NOW_TIME, self::MODEL_INSERT),
    );


    /**
     * 添加商品
     * @return boolean
     */
    public function addGoods() {
        unset($this->data['id']);
        if(empty($this->data['sn'])){
            $this->_make_sn();
        }
        // 保存基本信息
        if(($goods_id = $this->add()) === false){
            return false;
        }
        // 保存描述
        if($this->_save_content($goods_id) === false){
            $this->error = '保存商品详细描述失败';
            return false;
        }
        // 保存相册
        if($this->_save_gallery($goods_id) === false){
            $this->error = '保存商品相册失败';
            return false;
        }
        return true;
    }
    
    /**
     * 根据日期计算货号
     */
    private function _make_sn(){
        $date = date('Ymd');
        $day_count_model = M('GoodsDayCount');
        if(!($count = $day_count_model->where(array('day'=>$date))->getField('count'))){
            $count = 1;
                $data  = array(
                    'day'   => $day,
                    'count' => $count,
                );
                $day_count_model->add($data);
        }else{
            $count++;
            $day_count_model->where(array('day' => $day))->setInc('count', 1);
        }
        $sn = 'SN' . $date . str_pad($count, 5, '0', STR_PAD_LEFT);
        $this->data['sn'] = $sn;
    }
    
    /**
     * 保存商品描述
     * @param type $goods_id
     * @return type
     */
    private function _save_content($goods_id,$is_new=true){
        $content = I('post.content', '', false);
        $data = array(
            'goods_id' => $goods_id,
            'content'  => $content,
        );
        if($is_new){     
            return M('GoodsIntro')->add($data);
        }else{
            return M('GoodsIntro')->save($data);
        }
    }
    
    /**
     * 保存商品相册
     * @param type $goods_id
     * @return type
     */
    private function _save_gallery($goods_id){
        $paths = I('post.path');
        if(!$paths){
            return true;
        }
        $data = array();
        foreach($paths as $path){
            $data[]   = array(
                'goods_id' => $goods_id,
                'path'  => $path,
            );
        }
        return M('GoodsGallery')->addAll($data);
    }
    
    /**
     * 查询
     * @param array $cond 模糊查询条件
     * @return array
     */
    public function getPageList(array $cond=array()) {
        //查询条件
        $condition = $cond + array(
            'status' => array('gt',0),
        );
        //页面显示数据条数
        $page_size = C('PAGE_SIZE');
        //获取总条数
        $total = $this->where($condition)->count();
        //分页
        $page_obj = new \Think\Page($total,$page_size);
        $page_obj->setConfig('theme', C('PAGE_THEME'));
        $page_html = $page_obj->show();
        //查询
        $rows = $this->where($condition)->order('sort')->page(I('get.p'),$page_size)->select();
        foreach ($rows as $key => $value) {
            $rows[$key]['is_best'] = $value['goods_status'] & 1 ? 1 : 0;
            $rows[$key]['is_new']  = $value['goods_status'] & 2 ? 1 : 0;
            $rows[$key]['is_hot']  = $value['goods_status'] & 4 ? 1 : 0;
        }
        return array(
            'rows'=>$rows,
            'page_html'=>$page_html,
        );   
    }
    
    public function getGoods($goods_id) {
        // 查询基本信息
        $row = M('Goods')->where(array('id'=>$goods_id))->select();
        if(!$row){
            $this->error = '查找的商品不存在';
            return FALSE;                    
        }
        $row = array_shift($row);
        $tmp_status          = $row['goods_status'];
        $row['goods_status'] = array();
        if ($tmp_status & 1) {
            $row['goods_status'][] = 1;
        }
        if ($tmp_status & 2) {
            $row['goods_status'][] = 2;
        }
        if ($tmp_status & 4) {
            $row['goods_status'][] = 4;
        }
        $row['goods_status'] = json_encode($row['goods_status']);
        // 查询相册
        $row['paths'] = M('GoodsGallery')->where(array('goods_id'=>$goods_id))->getField('id,id,path',true);
        // 查询描述
        $row['content'] = M('GoodsIntro')->where(array('goods_id'=>$goods_id))->getField('content');
        return $row;
    }
    
    /**
     * 保存修改商品
     */
    public function saveGoods() {
        $goods_id = $this->data;
        $this->data['goods_status'] = array_sum($this->data['goods_status']);
        // 保存基本信息
        if ($this->save() === false) {
            return false;
        }
        // 保存详细描述
        if($this->_save_content($goods_id, false)===false){
            $this->error = '保存商品详细描述失败';
            return false;
        }
        // 保存相册信息
        if($this->_save_gallery($goods_id)===false){
            $this->error = '保存相册失败';
            return false;
        }
        
        return true;
    }
    
    /**
     * 逻辑删除商品
     * @param type $goods_id
     * @return type
     */
    public function deleteGoods($goods_id) {
        $data = array(
            'id' => $goods_id,
            'status' => 0
        );
        return $this->save($data);
    }
}
