<?php
/**
 * @brief  登录测试
 *  
 **/
require_once ('AdminTest.class.php');

class LoginTest extends AdminTest {

	private $handle;

	public function setUp() {
		$this->handle = new Admin();
	}

	public function tearDown() {

	}
	function rule_typeprovider()
	{
		return array(
			//正常
			array(
				array(
					'username'=>'test110',
					'password'=>'1234568',
				),
				CommonConst::SUCCESS
			),
			//异常,password参数有误
			array(
				array(
					'isLogin' => '0'
				),
				CommonConst::COMMON_PARAM_ERROR
			),
		); 
	}

	/** 
	 * @dataProvider rule_typeprovider
	 */
	public function testLogin($param=array(),$errno) {
		$ret = $this->handle->login($param);
		$this->assertEquals($errno, intval($ret['errno']));
		
		if($ret['errno'] == CommonConst::SUCCESS){
			$this->assertEquals($ret['isLogin'] , 1);
		}
	}
}
?>

