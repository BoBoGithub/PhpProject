<?php
/**
 * 数据中心 - 抓取数据Service类
 * 
 * @param   addTime 2018-03-19
 * @param   author  ChengBo
 */
class FetchDataService{
	private static $instance = NULL;
	
	//单例模式
	public static function getInstance(){
		if (!isset(self::$instance)){
			self::$instance = new FetchDataService();
		}
		return self::$instance;
	}
	
	protected function __construct(){}

	/**
	 * 抓取安居客指定小区数据
	 *
	 * @param int $type 指定的小区
	 *
	 * @param addTime 2018-03-19
	 * @param author  ChengBo
	 */
	public function fetchAjkHouseData($type = 0){
		//循环分页抓取数据
		for($i=1;$i<10;$i++){
			$ret = $this->fetchAjkHouseDataByPage($type, $i);
			if(!$ret){
				break;
			}

			//停留1-3s防止被屏蔽抓取
			sleep(mt_rand(1,3));
		}

		return true;
	}

	private function fetchAjkHouseDataByPage($type, $page){
		//检查小区类型
		if(!Utils::check_int($type)){
			throw new Exception(CommonConst::COMMON_PARAM_ERROR.' fetchAjkHouseData type error');
		}

		//抓取数据
		switch($type){
			case 1:	$url = 'https://sjz.anjuke.com/sale/o5-rd'.$page.'/?kw=奥北公元';break;
			case 2: $url = 'https://sjz.anjuke.com/sale/o5-rd'.$page.'/?kw=瀚唐小区';break;

			default : $url = 'https://sjz.anjuke.com/sale/o5-rd'.$page.'/?kw=奥北公元';
		}

		//抓取数据
		return $this->fetchAjkHouseListData($url);
	}

	/**
	 * 安居客 - 抓取最新排序的列表数据
	 *
	 * @param  str $url	列表页url
	 *
	 * return array()
	 *
	 * @param addTime 2018-03-19
	 * @param author  ChengBo 
	 */
	public function fetchAjkHouseListData($url = ''){
		//检查url
		if(!Utils::check_string($url)){
			throw new Exception(CommonConst::COMMON_PARAM_ERROR.' fetchAjkHouseData url error');
		}

		//抓取数据
		$pageContentData = $this->fetchAjkUrlData($url);

		//提取列表数据
		$listData = preg_match_all('/<li[^>]*class=\"list-item\"[^>]*>(.*)<\/li>/isU', $pageContentData, $retList);
		if(empty($retList[1])){
			CLog::debug("fetchAjkHouseListData list data empty");
			return false;
		}
		
		//提取每页数据
		$listUrlData = array();
		foreach($retList[1] as $k=>$listItem){
			//提取详情页地址
			preg_match('/<a[^>]*data-from=""[^>]*href="(.*)"[^>]*>/isU', $listItem, $urlData);
			if(empty($urlData[1])){
				continue;
			}
			
			$listUrlData[] = urlencode($urlData[1]);
			
			//调取详情页数据
			$detailRet = $this->fetchAjkDetailPage($urlData[1]);
			if(!$detailRet){
				return false;
			}

			//停留1-3s防止被屏蔽
			sleep(mt_rand(1, 3));
		}
		
		return true;
	}

	/**
	 * 抓取安居库单页数据
	 *
	 * @param str $url
	 *
	 * @param addTime 2018-03-19
	 * @param author  ChengBo
	 */
	public function fetchAjkDetailPage($url = ''){
		//检查页面地址
		if(!Utils::check_string($url)){
			return false;
		}

		//抓取数据
		$detailData = $this->fetchAjkUrlData($url);

		//提取房屋编号
		preg_match('/<span class="house-encode">房屋编码： (.*)，发布时间：(.*)<\/span>/isU', $detailData, $sendDetail);
		$houseData['house_num'] = isset($sendDetail[1]) ? $sendDetail[1] : '';
		$houseData['send_time'] = isset($sendDetail[2]) ? strtotime(trim(str_replace(array('年', '月', '日'), '-', $sendDetail[2]), '-')) : 0;
		
		//检查是否是今天的数据
		if($houseData['send_time']>strtotime(date('Y-m-d').' 00:00:00')){
			return true;
		}

		//检查是否是昨天以前的数据
		if($houseData['send_time'] < strtotime(date('Y-m-d', time()-86400*2).' 23:59:59')){
			return false;
		}
		
		//提取所属小区
		preg_match('/<dl>[^<]*<dt>[^<]*所属小区：[^<]*<\/dt>[^<]*<dd>[^<]*<a[^>]*>(.*)<\/a>[^<]*<\/dd>[^<]*<\/dl>/isU', $detailData, $siteName);
		$houseData['site_name'] = empty($siteName[1]) ? '' : $siteName[1];

		//总价格
		preg_match('/<span class="light info-tag"><em>(.*)<\/em>万<\/span>/isU', $detailData, $totalPrice);
		$houseData['total_price'] = empty($totalPrice[1]) ? '' : $totalPrice[1].'万';
	
		//提取总面积
		preg_match('/<dl><dt>建筑面积：<\/dt><dd>(.*)平方米<\/dd><\/dl>/isU', $detailData, $totalSize);
		$houseData['total_size'] = empty($totalSize[1]) ? '' : $totalSize[1].'平';
		
		//提取房屋户型
		preg_match('/<dl><dt>房屋户型：<\/dt><dd>(.*)<\/dd>[^<]*<\/dl>/isU', $detailData, $houseType);
		$houseData['house_type'] = isset($houseType[1]) ? str_replace(array('	', PHP_EOL), '', $houseType[1]) : '';
		
		//提取建造年代
		preg_match('/<dl><dt>建造年代：<\/dt><dd>(.*)年<\/dd><\/dl>/isU', $detailData, $createYear);
		$houseData['build_year'] = empty($createYear[1]) ? '' : $createYear[1].'年';

		//提取装修状态
		preg_match('/<dl><dt>装修程度：<\/dt><dd>(.*)<\/dd><\/dl>/isU', $detailData, $decorateState);
		$houseData['decorate_state'] = empty($decorateState[1]) ? '' : $decorateState[1];
		
		//中介业务员
		preg_match('/<p class="brokercard-name">(.*)<\/p>/isU', $detailData, $sendUserName);
		$houseData['user_name']	= empty($sendUserName[1]) ? '' : $sendUserName[1];
		
		//提取业务员的电话
		preg_match('/<p class="broker-mobile"><i class="iconfont">&#xE047;<\/i>(.*)<\/p>/isU', $detailData, $sendUserMobile);
		$houseData['user_mobile'] = empty($sendUserMobile[1]) ? '' : $sendUserMobile[1];
		
		//提取业务员单位
		preg_match('/<p>门店：<a[^>]*>(.*)<\/a><\/p>/isU', $detailData, $sendUserGate);
		$houseData['user_gate'] = isset($sendUserGate[1]) ? $sendUserGate[1] : '';
		
		//提取当前产品地址
		$houseData['url']	 = $url;

		//查询入库数据
		$fetchHouseData		= AjkHouseDao::getInstance()->getHouseDataByNum($houseData['house_num']);

		//检查是否已经入库
		if(!empty($fetchHouseData)){
			return true;
		}

		//新数据入库
		$lastId = AjkHouseDao::getInstance()->addAjkHouseData($houseData);
		CLog::debug('fetchAjkDetailPage insert house data id: '. $lastId);

		return $lastId;
	}

	/**
	 * 抓取安居库URL数据
	 *
	 * @param str $url 请求的url地址
	 *
	 * @param addTime 2018-03-19
	 * @param author  ChengBo
	 */
	public function fetchAjkUrlData($url = ''){
		//记录请求地址
		CLog::debug("fetchAjkUrlData url: ".$url);

		//检查url
		if(!Utils::check_string($url)){
			return '';
		}

		//设置请求Header
		$headers[] = 'cookie:lps=http%3A%2F%2Fsjz.zu.anjuke.com%2Ffangyuan%2Fpx3%2F%7C; ctid=28; sessid=9D211FF8-B6E1-1EEC-78FE-339901F73C94; 58tj_uuid=f1d42ddf-1023-46e9-ad7a-66c0185f8b86; new_session=1; init_refer=; new_uv=1; aQQ_ajkguid=1056C015-2424-B17B-F482-182FF0DCA55E; twe=2; __xsptplusUT_8=1; __xsptplus8=8.1.1519631082.1519631082.1%234%7C%7C%7C%7C%7C%23%23dZlU8pqxkkalvCgjm0RFU4plyU_NTQYU%23; als=0';

		//初始化设置CURL
		$ch	= curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36');
		$output = curl_exec($ch);
		curl_close($ch);

		return $output;
	}
}
