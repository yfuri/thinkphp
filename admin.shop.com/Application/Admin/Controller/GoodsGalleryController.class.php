<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Admin\Controller;

/**
 * Description of GoodsGalleryController
 *
 * @author Sunhong
 */
class GoodsGalleryController {
    
    /**
     * 删除一张图片
     * @param type $id
     */
    public function delete($id) {
        $goods_gallery = M('GoodsGallery');
        if($goods_gallery->delete($id) === FALSE){
            $this->error(get_error($goods_gallery->getError()));
        }  else {
            $this->success('删除成功');
        }
        
    }
}
