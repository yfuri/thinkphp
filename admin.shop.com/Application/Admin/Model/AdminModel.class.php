<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Admin\Model;

/**
 * Description of AdminModel
 *
 * @author Sunhong
 */
class AdminModel extends \Think\Model{
    
    /**
     * 自动验证
     * @var type 
     */
    protected $_validate = array(
        array('username', 'require', '用户名必填', self::EXISTS_VALIDATE, '', self::MODEL_INSERT),
        array('username', '', '用户名已存在', self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT),
        array('username', '2,16', '用户名长度不合法', self::EXISTS_VALIDATE, 'length', self::MODEL_INSERT),
        array('password', 'require', '密码必填', self::EXISTS_VALIDATE, '', self::MODEL_INSERT),
        array('password', '6,16', '密码长度不合法', self::EXISTS_VALIDATE, 'length', self::MODEL_INSERT),
        array('email', 'require', '邮箱必填', self::EXISTS_VALIDATE, '', self::MODEL_INSERT),
        array('email', 'email', '邮箱不合法', self::EXISTS_VALIDATE, '', self::MODEL_INSERT),
        array('username', 'require', '用户名必填', self::MUST_VALIDATE, '', 'login'),
        array('password', 'require', '密码必填', self::MUST_VALIDATE, '', 'login'),
        array('captcha', 'require', '验证码必填', self::MUST_VALIDATE, '', 'login'),
        array('captcha', 'check_captcha', '验证码不正确', self::MUST_VALIDATE, 'callback', 'login'),
    );
    
    /**
     * 自动完成
     * @var type 
     */
    protected $_auto     = array(
        array('salt', '\Org\Util\String::randString', self::MODEL_INSERT, 'function', 6),
        array('salt', '\Org\Util\String::randString', 'reset_pwd', 'function', 6),//当重置密码是自动生成一个盐
        array('add_time', NOW_TIME, self::MODEL_INSERT),
    );
    
    /**
     * 添加管理员,并保存管理员相关权限信息
     * @return boolean]
     */
    public function addAdmin() {
        //保存基本信息
        unset($this->data['id']);
        $this->data['password'] = my_mcrypt($this->data['password'], $this->data['salt']);
        if (($admin_id  = $this->add()) === false) {
            return false;
        }
        //保存关联角色
        if ($this->_save_role($admin_id) === false) {
            $this->error = '保存角色失败';
            return false;
        }
        //保存关联权限
        if ($this->_save_permission($admin_id) === false) {
            $this->error = '保存额外权限失败';
            return false;
        }
        return true;
    }
    
    /**
     * 获取管理员详细信息,包括角色和权限.
     * @param type $id
     * @return boolean
     */
    public function getAdminInfo($id) {
        $row = $this->where('id='.$id)->select();
        if (empty($row)) {
            $this->error = '管理员不存在';
            return false;
        }
        $row = array_shift($row);
        $role_ids  = M('AdminRole')->where(array('admin_id' => $id))->getField('role_id', true);
        $row['role_ids'] = $role_ids;

        $permission_ids   = M('AdminPermission')->where(array('admin_id' => $id))->getField('permission_id', true);
        $row['permission_ids'] = json_encode($permission_ids);
        return $row;
    }
    
    /**
     * 修改管理员
     * @return boolean
     */
    public function updateAdmin() {
        $admin_id = $this->data['id'];
        //保存角色关联
        if ($this->_save_role($admin_id, false) === false) {
            $this->error = '更新角色关联失败';
            return false;
        }
        //保存权限关联
        if ($this->_save_permission($admin_id, false) === false) {
            $this->error = '更新权限关联失败';
            return false;
        }
        return true;
    }
    
    /**
     * 删除管理员,并删除关联的角色和权限.
     * @param type $id 管理员id.
     * @return boolean
     */
    public function deleteAdmin($id) {
        if ($this->delete($id) === false) {
            return false;
        }
        
        if($this->_delete_role($id)===false){
            $this->error = '删除角色关联失败';
            return false;
        }
        if($this->_delete_permission($id)===false){
            $this->error = '删除权限关联失败';
            return false;
        }
        
        return true;
    }
    
    /**
     * 保存权限关联
     * @param type $admin_id
     * @param type $is_new
     * @return boolean
     */
    private function _save_permission($admin_id, $is_new = true) {
        if (!$is_new) {
            if($this->_delete_permission($admin_id)===false){
                return false;
            }
        }
        //获取勾选的权限,如果没有勾选任何权限,就不再执行添加操作.
        $perms = I('post.perms');
        if (empty($perms)) {
            return true;
        }
        $data = array();
        foreach ($perms as $perm) {
            $data[] = array(
                'admin_id'      => $admin_id,
                'permission_id' => $perm,
            );
        }
        return M('AdminPermission')->addAll($data);
    }
    
    /**
     * 保存角色关联
     * @param type $admin_id
     * @param type $is_new
     * @return boolean
     */
    private function _save_role($admin_id, $is_new = true) {
        if (!$is_new) {
            if($this->_delete_role($admin_id)===false){
                return false;
            }
        }
        //获取勾选的角色,如果没有勾选任何角色,就不再执行添加操作.
        $roles = I('post.roles');
        if (empty($roles)) {
            return true;
        }
        $data = array();
        foreach ($roles as $role) {
            $data[] = array(
                'admin_id' => $admin_id,
                'role_id'  => $role,
            );
        }
        return M('AdminRole')->addAll($data);
    }
    
    /**
     * 删除权限关联
     * @param type $admin_id
     * @return boolean
     */
    private function _delete_permission($admin_id) {
        if (M('AdminPermission')->where(array('admin_id' => $admin_id))->delete() === false) {
            return false;
        }
    }
    
    /**
     * 删除角色关联
     * @param type $admin_id
     * @return boolean
     */
    private function _delete_role($admin_id) {
        if (M('AdminRole')->where(array('admin_id' => $admin_id))->delete() === false) {
            return false;
        }
    }
    
    /**
     * 获取列表
     * @param type $field
     * @return type
     */
    public function getList($field = '*') {
        return $this->field($field)->where(array('status' => 1))->select();
    }
    
    /**
     * 重置密码.
     * @param integer $id 管理员id.
     * @return string|false 成功返回新密码,失败返回false.
     */
    public function resetPwd($id) {
        //获取数据
        $password = I('post.password');
        if(empty($password)){
            //使用系统自带的随机字符串生成方法生成
            $len = mt_rand(8, 10);
            $password = \Org\Util\String::randString($len, '', '-=_+_,./?!@#$%^&*()~');
        }
        $this->data['password'] = my_mcrypt($password, $this->data['salt']);
        return $this->save()?$password:false;
    }
    
    /**
     * 验证验证码
     * @param type $code
     * @return bool
     */
    protected function check_captcha($code){
        $verify = new \Think\Verify();
        return $verify->check($code);
    }
    
    public function login() {
        session('USERINFO', null);
        $request->data = $this->data;
        // 根据输入用户名查询
        $userinfo = $this->getByUsername($request->data['username']);
        if(empty($userinfo)){
            $this->error = '用户不存在';
            return FALSE;
        }
        // 比较密码,加盐加密
        $password = my_mcrypt($request->data['password'], $userinfo['salt']);
        if($password != $userinfo['password']){
            $this->error = '密码错误';
            return FALSE;
        }
        // 保存用户信息到session,包括基本信息,权限信息
        session('USERINFO', $userinfo);
        $this->_getPermissions($userinfo['id']);
        if(I('post.remember')){
            // 保存用于自动登录的token信息
            $this->_save_token($userinfo['id']);
        }
        return TRUE;
    }
    
    /**
     * 获取用户权限列表,从角色和额外权限的关联表中获取.
     * @param integer $admin_id
     */
    private function _getPermissions($admin_id){
        session('PATHS',null);
        session('PERM_IDS',null);
        //获取通过角色得到的权限
        $role_permssions = $this->distinct(true)->table('__ADMIN_ROLE__ as ar')->join('__ROLE_PERMISSION__ as rp ON ar.`role_id`=rp.`role_id`')->join('__PERMISSION__ as p ON rp.`permission_id`=p.`id`')->where(['admin_id'=>$admin_id ,'path'=>['neq','']])->getField('permission_id,path',true);
        //获取额外权限
        $admin_permissions = $this->distinct(true)->table('__ADMIN_PERMISSION__ as ap')->join('__PERMISSION__ as p ON ap.`permission_id` = p.`id`')->where(['admin_id'=>$admin_id ,'path'=>['neq','']])->getField('permission_id,path',true);
        //由于前面获取的都是关联数组,+合并会自动合并键名相同的元素,也就等同于做了去重
        $role_permssions=$role_permssions?:[];
        $admin_permissions=$admin_permissions?:[];
        $permissions = $role_permssions+$admin_permissions;
        //获取权限id列表
        $permission_ids = array_keys($permissions);
        //获取权限路径列表
        $paths = array_values($permissions);
        session('PATHS',$paths);
        session('PERM_IDS',$permission_ids);
    }

    /**
     * 保存用户登录的令牌信息
     * @param type $admin_id
     * @return type
     */
    private function _save_token($admin_id){
        cookie('AUTO_LOGIN_TOKEN',null);
        M("AdminToken")->where(['admin_id'=>$admin_id])->delete();
        $data = array(
            'admin_id' => $admin_id,
            'token' => sha1(mcrypt_create_iv(32))
        );
        cookie('AUTO_LOGIN_TOKEN',$data,604800);
        return M("AdminToken")->add($data);
    }
    
    /**
     * 自动登录
     */
    public function autoLogin() {
        $data = cookie('AUTO_LOGIN_TOKEN');
        if(empty($data)){
            return false;
        }
        $token_model = M("AdminToken");
        // 如果cookie中保存的token信息错误
        if(!$token_model->where($data)->count()){
            return false;
        }
        // 重新保存token
        if($this->_save_token($data['admin_id']) === false){
            return false;
        }else{
            // 保存用户信息到session,包括基本信息,权限信息
            $userinfo = $this->find($data['admin_id']);
            session('USERINFO', $userinfo);
            $this->_getPermissions($data['admin_id']);
            return TRUE;
        }
    }
}
