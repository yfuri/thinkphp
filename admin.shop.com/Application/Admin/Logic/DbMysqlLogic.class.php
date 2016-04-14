<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Admin\Logic;

/**
 * Description of DbMysqlLogic
 *
 * @author Sunhong
 */
class DbMysqlLogic implements DbMysqlInterface{
    public function connect() {
        
    }

    public function disconnect() {
        
    }

    public function free($result) {
        
    }

    public function getAll($sql, array $args = array()) {
        
    }

    public function getAssoc($sql, array $args = array()) {
        
    }

    public function getCol($sql, array $args = array()) {
        
    }
    
    /**
     * 获取查询结果的第一个字段值
     * @param String $sql
     * @param array $args
     * @return type
     */
    public function getOne($sql, array $args = array()) {
        //获取参数列表
        $args = func_get_args();
        //SQL结构语句
        $sql = array_shift($args);
        //替换参数
        $parms = $args;
        //分割字符串
        $sqls = preg_split('/\?[NTF]/', $sql);
        //弹出最后的空键值
        array_pop($sqls);
        $sql = '';
        //遍历组合SQL语句
        foreach ($sqls as $key => $value) {
            $sql .= $value . $parms[$key];
        }
        //执行
        $rows = M()->query($sql);
        if($rows){
            $res = array_shift($rows);
            return array_shift($res);
        }
    }
    
    /**
     * 获取一行查询结果
     * @param type $sql
     * @param array $args
     * @return type
     */
    public function getRow($sql, array $args = array()) {
        //获取参数列表
        $args = func_get_args();
        //SQL结构语句
        $sql = array_shift($args);
        //替换参数
        $parms = $args;
        //分割字符串
        $sqls = preg_split('/\?[NTF]/', $sql);
        //弹出最后的空键值
        array_pop($sqls);
        $sql = '';
        //遍历组合SQL语句
        foreach ($sqls as $key => $value) {
            $sql .= $value . $parms[$key];
        }
        //执行
        $rows = M()->query($sql);
        if($rows){
            return array_shift($rows);
        }
    }
    
    /**
     * 插入
     * @param type $sql
     * @param array $args
     * @return boolean
     */
    public function insert($sql, array $args = array()) {
        //获取参数列表
        $args = func_get_args();
        //SQL结构语句
        $sql = array_shift($args);
        //获取字段关键信息
        $table_name = $args[0];
        $params     = $args[1];
        unset($params['id']);
        $sql        = str_replace('?T', '`' . $table_name . '`', $sql);
        $fields     = array();
        foreach ($params as $key => $value) {
            $fields[] = '`' . $key . '`="' . $value . '"';
        }
        $field_str = implode(',', $fields);
        $sql       = str_replace('?%', $field_str, $sql);
        if(M()->execute($sql)!==false){
            return M()->getLastInsID();
        }else{
            return false;
        }
    }
    
    /**
     * 执行简单的
     * @param type $sql
     * @param array $args
     * @return type
     */
    public function query($sql, array $args = array()) {
        //获取参数列表
        $args = func_get_args();
        //SQL结构语句
        $sql = array_shift($args);
        //替换参数
        $parms = $args;
        //分割字符串
        $sqls = preg_split('/\?[NTF]/', $sql);
        //弹出最后的空键值
        array_pop($sqls);
        $sql = '';
        //遍历组合SQL语句
        foreach ($sqls as $key => $value) {
            $sql .= $value . $parms[$key];
        }
        //执行
        return M()->execute($sql);
    }

    public function update($sql, array $args = array()) {
        
    }

//put your code here
}
