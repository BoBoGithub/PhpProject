<?php
/**
 * user服务类
 *  
 **/
class UserService {
	private static $instance = NULL;

	//获取单例
	public static function getInstance() {
		if (! isset ( self::$instance )) {
			self::$instance = new UserService ();
		}
		return self::$instance;
	}

	protected function __construct() {
	}

	//获取用户信息
	public function getUserInfoByUid($uid) {
		if(false === Utils::check_int($uid)){
			throw new Exception(CommonConst::COMMON_PARAM_ERROR . ' to input content['.$uid.'] not valid ');
		}

		//redis获取信息
		if($result = $this->getRedisUserInfo($uid)){
			return $result;
		}
		//数据库获取用户信息
		$user_dao = UserDao::getInstance();
		$user_info = $user_dao->getUserInfoByUid($uid);

		//用户信息写入redis
		if($user_info && $user_info['uid'] > 0){
			$this->setRedisUserInfo($user_info['uid'], $user_info);
		}

		return $user_info;
	}

	//获取用户扩展信息
	public function getUserMoreInfoByUid($uid) {

		if(false === Utils::check_int($uid)){
			throw new Exception(CommonConst::COMMON_PARAM_ERROR . ' to input content['.$uid.'] not valid ');
		}

		//redis获取用户扩展信息
		if($result = $this->getRedisUserMoreInfo($uid)){
			return $result;
		}
		//数据库获取基本信息
		$user_dao = UserDao::getInstance ();
		$user_info = $user_dao->getUserMoreInfoByUid($uid);

		//用户信息写入redis
		if($user_info && $user_info['uid'] > 0){
			$this->setRedisUserMoreInfo($user_info['uid'], $user_info);
		}


		return $user_info;
	}
	//获取用户列表
	public function getList($where, $page, $pageSize,$order='uid desc') {

		$pageSize = empty($pageSize)?'10':$pageSize;
		$page = empty($page)?'0':$page;

		if(false === Utils::check_int($page)){
			throw new Exception(CommonConst::COMMON_PARAM_ERROR . ' to input content['.$page.'] not valid ');
		}	

		if(false === Utils::check_int($pageSize)){
			throw new Exception(CommonConst::COMMON_PARAM_ERROR . ' to input content['.$pageSize.'] not valid ');
		}
		if(false === Utils::check_string($where)){
			throw new Exception(CommonConst::COMMON_PARAM_ERROR . ' to input content['.$where.'] not valid ');
		}
		$user_dao = UserDao::getInstance ();
		$user_list = $user_dao->getList($where,$page, $pageSize,$order='uid desc');
		return $user_list;
	}	
	/**
     * 按照 用户名|| 手机 || 邮箱 获取用户信息
     * @return Array | false
     *
     * @param int $uid 用户uid       
     *     
     */

	public function getOne($where) {	

		if(false === Utils::check_string($where)){
			throw new Exception(CommonConst::COMMON_PARAM_ERROR . ' to input content['.$where.'] not valid ');
		}
		$user_dao  = UserDao::getInstance ();
		$ret = $user_dao->getOne($where);
		return $ret;
	}	
	/**
     * 获取用户详细信息列表
     *
     * @param array $uidArr 用户uid数组      
     *
     * @param addTIme 2014-03-06
     */
	public function getMoreList($moreUidArr){
		if(empty($moreUidArr) || !is_array($moreUidArr)){
			throw new Exception(CommonConst::COMMON_PARAM_ERROR . ' to input content['.$moreUidArr.'] not valid ');
		}
		$userMoreList = array();
		$user_dao = UserDao::getInstance ();
		foreach ($moreUidArr as $key => $value) {
			$usermore_list[] = $user_dao->getUserMoreInfoByUid($value);
		}
		if(empty($usermore_list) || count($usermore_list)<=0){
			return false;
		}
		return $usermore_list;
	}
	/**
     * 设置session信息
     * @return true | false
     *
     * @param int $uid 用户uid       
     * @param array $userInfo 用户基本信息 
     */
	public function setSession($uid, array $sessionInfo,$expireTime){
		if(empty($uid) || empty($sessionInfo) || !is_array($sessionInfo)){
			return false;
		}
		$sessionInfoStr = json_encode($sessionInfo);
		$redis = RedisWrapper::getInstance($uid);
		$redis->set(CommonConst::MODULE_PASSPORT_ID . '_session_' . $uid, $sessionInfoStr,$expireTime);
		return true;

	}	
	/**
     * 获取session信息
     * @return Array | false
     *
     * @param int $uid 用户uid       
     *     
     */
	public function getSession($uid){
		if(empty($uid)){
			return false;
		}
		$redis = RedisWrapper::getInstance($uid);
		$result = $redis->get(CommonConst::MODULE_PASSPORT_ID . '_session_' . $uid);

		if(empty($result)){
			return false;
		} 
		$result = json_decode($result,true);
		return $result;
	}

	/**
     * 用户信息写入redis
     * @return true | false
     *
     * @param int $uid 用户uid       
     * @param array $userInfo 用户基本信息 
     */
	public function setRedisUserInfo($uid, array $userInfo){
		if(empty($uid) || empty($userInfo) || !is_array($userInfo)){
			return false;
		}
		$userInfoStr = json_encode($userInfo);
		$redis = RedisWrapper::getInstance($uid);
		$redis->set(CommonConst::MODULE_PASSPORT_ID . '_userinfo_' . $uid, $userInfoStr);
		return true;
	}	
	/**
     * 用户扩展信息写入redis
     * @return true | false
     *
     * @param int $uid 用户uid       
     * @param array $userInfo 用户扩展信息 
     */
	public function setRedisUserMoreInfo($uid, array $userInfo){
		if(empty($uid) || empty($userInfo) || !is_array($userInfo)){
			return false;
		}
		$userInfoStr = json_encode($userInfo);
		$redis = RedisWrapper::getInstance($uid);
		$redis->set(CommonConst::MODULE_PASSPORT_ID . '_usermoreinfo_' . $uid, $userInfoStr);
		return true;
	}
	/**
     * 获取redis用户基本信息
     * @return Array | false
     *
     * @param int $uid 用户uid       
     *     
     */
	public function getRedisUserInfo($uid){

		if(empty($uid)){
			return false;
		}

		$redis = RedisWrapper::getInstance($uid);
		if($redis) {
			$result = $redis->get(CommonConst::MODULE_PASSPORT_ID . '_userinfo_' . $uid);
		}
		if(empty($result)){
			return false;
		} 

		$result = json_decode($result,true);
		return $result;
	}
	/**
     * 获取redis用户扩展信息
     * @return Array | false
     *
     * @param int $uid 用户uid       
     *     
     */
	public function getRedisUserMoreInfo($uid){

		if(empty($uid)){
			return false;
		}

		$redis = RedisWrapper::getInstance($uid);
		if($redis) {
			$result = $redis->get(CommonConst::MODULE_PASSPORT_ID . '_usermoreinfo_' . $uid);
		}
		if(empty($result)){
			return false;
		} 

		$result = json_decode($result,true);
		return $result;
	}

	/**
      * 删除redis用户基本信息
      * @param int $uid 用户uid
      */
	public function deleteRedisUserInfo($uid){

		$redis = RedisWrapper::getInstance($uid);
		$ret = $redis->del(CommonConst::MODULE_PASSPORT_ID . '_userinfo_' . $uid);
		if($ret) {
			return true;
		} else {
			return false;
		}
	}
	/**
      * 更新redis用户基本信息
      * @param int $uid 用户uid 
      * @param array $param 需要更新的数据 
      * 
     */
	public function updateRedisUserInfo($uid,$param){
		if(empty($uid)){
			return false;
		}
		if(!is_array($param)){
			return false;
		}
		$result = $this->getRedisUserInfo($uid);
		if(!$result){
			return false;
		}
		foreach ($param as $key => $value) {
			if(array_key_exists($key,$result)) {
				$result[$key] = $value;
			}
		}
		$updateResult = $this->setRedisUserInfo($uid,$result);
		if(!$updateResult){
			return false;
		}
		return true ;
	}
	/**
      * 更新redis用户扩展信息
      * @param int $uid 用户uid 
      * @param array $param 需要更新的数据 
      * 
     */
	public function updateRedisUserMoreInfo($uid,$param){
		if(empty($uid)){
			return false;
		}
		if(!is_array($param)){
			return false;
		}
		$result = $this->getRedisUserMoreInfo($uid);
		if(!$result){
			return false;
		}
		foreach ($param as $key => $value) {
			if(array_key_exists($key,$result)) {
				$result[$key] = $value;
			}
		}
		$updateResult = $this->setRedisUserMoreInfo($uid,$result);
		if(!$updateResult){
			return false;
		}
		return true ;
	}

	/**
     * 获取用户头像
     *
     * @param int $uid 用户uid       
     *     
     */
	public function getUseraAvator($uid){
		return 'http://my.fh21.com.cn/uploadfile/headimage/70/2/691789/90x90.jpg';
	}


	/**
     * 设置手机验证码
     * @return true | false
     *
     * @param int $uid 用户uid       
     * @param array $captcha 验证码信息
     */
	public function setMobileCaptcha($uid, array $captcha, $expireTime){
		if(empty($uid) || empty($captcha) || !is_array($captcha)){
			return false;
		}
		$captchaArr = json_encode($captcha);
		$redis = RedisWrapper::getInstance($uid);
		$redis->set(CommonConst::MODULE_PASSPORT_ID . '_mobilecaptcha_' . $uid, $captchaArr,$expireTime);
		return true;
	}
	/**
     * 获取验证码信息
     * @return Array | false
     *
     * @param int $uid 用户uid       
     *     
     */
	public function getMobileCaptcha($uid){
		if(empty($uid)){
			return false;
		}
		$redis = RedisWrapper::getInstance($uid);
		$result = $redis->get(CommonConst::MODULE_PASSPORT_ID . '_mobilecaptcha_' . $uid);

		if(empty($result)){
			return false;
		} 
		$result = json_decode($result,true);
		return $result;
	}
	/**
     * 删除手机验证码信息
     * @return Array | false
     *
     * @param int $uid 用户uid       
     */
	public function delMobileCaptcha($uid){
		$redis = RedisWrapper::getInstance($uid);
		$ret = $redis->del(CommonConst::MODULE_PASSPORT_ID . '_mobilecaptcha_' . $uid);
		if($ret) {
			return true;
		} else {
			return false;
		}
	}
	/**
     * 设置手机验证码发送次数
     * @return true | false
     *
     * @param int $uid 用户uid       
     * @param array $captcha 验证码信息
     */
	public function setMobileCaptchaNums($uid, array $captcha, $expireTime){
		if(empty($uid) || empty($captcha) || !is_array($captcha)){
			return false;
		}
		$captchaArr = json_encode($captcha);
		$redis = RedisWrapper::getInstance($uid);
		$redis->set(CommonConst::MODULE_PASSPORT_ID . '_mobilecaptchanums_' . $uid, $captchaArr,$expireTime);
		return true;
	}
	/**
     * 获取发送验证码信息次数
     * @return Array | false
     *
     * @param int $uid 用户uid       
     *     
     */
	public function getMobileCaptchaNums($uid){
		if(empty($uid)){
			return false;
		}
		$redis = RedisWrapper::getInstance($uid);
		$result = $redis->get(CommonConst::MODULE_PASSPORT_ID . '_mobilecaptchanums_' . $uid);

		if(empty($result) || !isset($result)){
			return false;
		} 
		$result = json_decode($result,true);
		return $result;
	}
	/**
     * 获取省份
     * @return Array | false
     *
     * @param int $uid 用户uid       
     *     
     */
	public function getArea($pid=0){
		$user_dao = UserDao::getInstance ();
		$province = $user_dao->getAreaList($pid);
		return $province;
	}




}
