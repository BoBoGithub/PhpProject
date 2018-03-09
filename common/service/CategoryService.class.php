<?php
/**
 * 分类服务类
 *  
 **/
class CategoryService {
	private static $instance = NULL;

	//获取单例
	public static function getInstance() {
		if (! isset ( self::$instance )) {
			self::$instance = new CategoryService ();
		}
		return self::$instance;
	}

	protected function __construct() {
	}

	/**
	 * 获取分类详细信息
	 *
	 * @param cid 分类ID
	 * @return array  详细信息数组
	 *         
	 */
	public function getDetail($cid){
		if(false === Utils::check_int($cid) && !(is_array($cid) && count($cid)>0)){
			throw new Exception(CommonConst::COMMON_PARAM_ERROR . ' input cid[' . $cid . '] not valid');
		}
		$categoryDao = CategoryDao::getInstance();	
		$arr_ret = $categoryDao->getDetail($cid);
		return $arr_ret;
	}

	/**
	 * 获取分类信息以及分类下级的信息
	 * 
	 * @param $cid 分类ID
	 * @return arr_ret 分类详细信息+子级详细信息 
	 */
	public function getSonList($cid){
		if(false === Utils::check_int($cid)){
			throw new Exception(CommonConst::COMMON_PARAM_ERROR . ' input cid[' . $cid . '] not valid');
		}

		$field = array('id','name','themeid','picid');
		$categoryDao = CategoryDao::getInstance();
		
		$arr_list_tmp = $categoryDao->getSonList($cid,$field);
		if($arr_list_tmp){
			$arr_list = array();
			foreach ($arr_list_tmp as $key => $value) {
				$arr_list[$value['id']] = $value;
			}
		}
		return $arr_list;
	}

	/**
	* 获取分类下的专家信息
	*/
	public function getExpert($cid){
		$userService = UserService::getInstance();
		$where ='categoryname='.$cid.' AND elect=1 AND usertype='.CommonConst::USER_TYPE_PAETTIME;
		return $userService -> getList($where,1,3);
	}
        /**
	 * 获取一级分类列表
         * @param 要获取的字段 空数组为 *
	 * @return array
	 */
	public function getFirstCategoryList($field = array() , $where = ''){

                $categoryDao = CategoryDao::getInstance();	
		return $categoryDao->getFirstCategoryList($field , $where);
	}

}
