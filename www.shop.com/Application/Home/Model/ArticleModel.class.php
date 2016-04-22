<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Home\Model;

/**
 * Description of ArticleModel
 *
 * @author Sunhong
 */
class ArticleModel extends \Think\Model{
    
    /**
     * 获取帮助类文章
     * @return type
     */
    public function getHelpArticleList() {
        $help_categories = M('ArticleCategory')->where(['is_help'=>1,'status'=>1])->limit(5)->getField('id,name');
        foreach ($help_categories as $key=>$value){
            $value = [
                'name'=>$value,
                'list'=>$this->field('id,name')->where(['status'=>1,'article_category_id'=>$key])->limit(6)->select(),
            ];
            $help_categories[$key] = $value;
        }
        return $help_categories;
    }
}
