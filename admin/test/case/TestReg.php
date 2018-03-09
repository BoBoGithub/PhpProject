<?php
/**
 * @brief  注册测试
 *  
 **/
require_once ('PassportTest.class.php');

class Reg_Test extends PassportTest {

	private $handle;

	public function setUp() {
		$this->handle = new Passport();
	}

	public function tearDown() {

	}
	function rule_typeprovider()
	{
		//timestamp,type,clienttype,channel,devuid,$sign_isok,$exp_errno
		return array(

			//正常
			array(
				array(
					'username'=>$_ENV['username'],'password'=>$_ENV['password'],'clientip'=>$_ENV['clientip'],'truename'=>'test'
				),CommonConst::SUCCESS
			),
			//异常,password参数有误
			array(
				array(
					'username'=>$_ENV['username'],'clientip'=>$_ENV['clientip'],'truename'=>'test'
				),CommonConst::COMMON_PARAM_ERROR
			),
		); 
	}

	/** 
	 * @dataProvider rule_typeprovider
	 */
	public function testReg($param=array(),$errno) {
		$ret = $this->handle->reg($param);
		$this->assertEquals($errno, intval($ret['errno']));
		if ($ret['errno'] === CommonConst::SUCCESS) {
			$this->assertEquals(is_numeric($ret['uid']),true);
		}
	}
}

?>

