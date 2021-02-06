<?php
/**
 * 腾讯云直播SDK管理类
 */
namespace Common\Controller;
use Think\Controller;
use Common\Controller\ImController;


////  引用腾讯云直播SDK命名空间-start
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Vod\V20180717\VodClient;
use TencentCloud\Vod\V20180717\Models\SearchMediaRequest;
use TencentCloud\Vod\V20180717\Models\DescribeMediaInfosRequest;
use TencentCloud\Live\V20180801\LiveClient;
use TencentCloud\Live\V20180801\Models\CreateCommonMixStreamRequest;
use TencentCloud\Live\V20180801\Models\CancelCommonMixStreamRequest;
use TencentCloud\Live\V20180801\Models\DropLiveStreamRequest;
////  引用腾讯云直播SDK命名空间-end


class TencentController extends Controller
{
	protected static $secretId    		= TENCENT_SECRETID;							// 腾讯云secretId
	protected static $secretKey   		= TENCENT_SECRETKEY;						// 腾讯云secretKey

	protected static $cred   			= null;										// cred对象
	protected static $httpProfile 		= null;										// httpProfile对象
	protected static $clientProfile 	= null;										// clientProfile对象

	public static  $offset 				= 0;										// 查询起始位置
	public static  $limit  				= 10;										// 分页数默认10条

	public static $region  				= [											// 地域列表
		'ap-bangkok'        => ['曼谷', '亚太东南'],	
		'ap-beijing'        => ['北京', '华北地区'],	
		'ap-chengdu'        => ['成都', '西南地区'],	
		'ap-chongqing'      => ['重庆', '西南地区'],	
		'ap-guangzhou'      => ['广州', '华南地区'],	
		'ap-guangzhou-open' => ['广州Open', '华南地区'],	
		'ap-hongkong'       => ['中国香港', '港澳台地区'],	
		'ap-mumbai'         => ['孟买', '亚太南部'],	
		'ap-nanjing'        => ['南京', '华东地区'],	
		'ap-seoul'          => ['首尔', '亚太东北'],	
		'ap-shanghai'       => ['上海', '华东地区'],	
		'ap-shanghai-fsi'   => ['上海金融', '华东地区'],	
		'ap-shenzhen-fsi'   => ['深圳金融', '华南地区'],
		'ap-singapore'      => ['新加坡', '亚太东南'],
		'ap-tokyo'          => ['东京', '亚太东北'],
		'ap-frankfurt'      => ['法兰克福', '欧洲地区'],
		'ap-moscow'         => ['莫斯科', '欧洲地区'],
		'ap-ashburn'        => ['弗吉尼亚', '美国东部'],
		'ap-siliconvalley'  => ['硅谷', '美国西部'],
		'ap-toronto'        => ['多伦多', '北美地区']
	];


	/**
	 * 初始化方法
	 */
	public function _initialize()
	{
		Vendor('autoload');		// 引用腾讯云直播SDK-自动加载文件
	}


	/**
	 * 腾讯云调用借口的头部公共代码
	 */
	protected static function commonHead($httpUrl = 'vod.tencentcloudapi.com')
	{
		self::$cred          = new Credential(self::$secretId, self::$secretKey);
		self::$httpProfile   = new HttpProfile();
		self::$httpProfile->setEndpoint($httpUrl);
		self::$clientProfile = new ClientProfile();
		self::$clientProfile->setHttpProfile(self::$httpProfile);	
	}

	/**
	 * 混流公共方法
	 */
	protected static function commonMixedFlow($params, $type = 'create')
	{
		try {
			self::commonHead('live.tencentcloudapi.com');		// 调用公共方法
			$client = new LiveClient(self::$cred, "", self::$clientProfile);
			$req 	= $type ==  'cancel' ? (new CancelCommonMixStreamRequest()) : (new CreateCommonMixStreamRequest());
			
			$req->fromJsonString(json_encode($params));
			$resp 	= $type ==  'cancel' ? ($client->CancelCommonMixStream($req)) : ($client->CreateCommonMixStream($req));
		
			$result = json_decode($resp->toJsonString(), true);
			
			// 返回 结果
			if (is_array($result)) { 
				if (count($result) == 1) {
					return 'ok';
				} else {
					return $result;
				}
			} else {
				return $resp->toJsonString();
			}	
			
		} catch(TencentCloudSDKException $e) {
			return $e;
		}
	}

    /**
	 * 搜索媒体信息
	 */
	public static function searchMedia($data = [])
	{
		try {
			// 参数处理
			$regionStr 	   	= isset($data['Region']) ? $data['Region'] : '';
			unset($data['Region']); 

			if (!in_array($regionStr, array_keys(self::$region))) {
				$regionStr 	= '';	
			}

			$page 			= isset($data['Page']) ? $data['Page'] : 1;
			unset($data['Page']); 
			$data['Offset'] = isset($data['Limit']) ? ($page - 1) * $data['Limit'] : self::$offset;
			$data['Limit'] 	= isset($data['Limit']) ? $data['Limit'] : self::$limit;
			

			// 调用腾讯云接口
			self::commonHead();					// 调用公共方法
			$client        = new VodClient(self::$cred, $regionStr, self::$clientProfile);
			$req           = new SearchMediaRequest();
			$params        = json_encode($data);
			$req->fromJsonString($params);
			$resp          = $client->SearchMedia($req);

			return $resp->toJsonString();

		} catch(TencentCloudSDKException $e) {
			echo $e;
		}
	}

	/**
	 *  查询短视频信息并插入数据库
	 */
	public static function queryShortAddOne($vid)
	{
		try {
			// 调用腾讯云接口
			self::commonHead();					// 调用公共方法
			$client        	= new VodClient(self::$cred, '', self::$clientProfile);
			$req 			= new DescribeMediaInfosRequest();
			$params 		= json_encode(['FileIds' => [$vid]]);
			$req->fromJsonString($params);
			$resp 			= $client->DescribeMediaInfos($req);
			$res 			= json_decode($resp->toJsonString(), true);

			// 返回数据
			if ($res && isset($res['MediaInfoSet'])) {
				$info  = $res['MediaInfoSet'][0]['BasicInfo'];
				$data  = $res['MediaInfoSet'][0]['MetaData'];
				$Short = new \Common\Model\ShortModel();    	// 短视频模型
				$Short->callbacksAdd($info, $data);				// 添加记录
			}
		} catch (TencentCloudSDKException $e) {
			echo $e;
		}
	}

	/**
	 * 获取 sdkappid 账号
	 */
	public static function getSdkAppid($one_id = 0, $two_id = 0)
	{
		$Im 	= new ImController();
		$sdk 	= $Im::getImSdkAppid();

		if ($one_id && $two_id) {
			$sdk_id = $sdk;
			$sdk    = [
				'sdk_id' 	=> $sdk_id,
				'one_str' 	=> $sdk_id .'_'. $one_id,
				'two_str' 	=> $sdk_id .'_'. $two_id,
			]; 
			$sdk['com_str'] = $sdk['one_str'] .'_'. $sdk['two_str'];
		}

		return $sdk;
	}

	/**
	 *  主播PK
	 */
	public static function hostPk($one_id, $two_id, $data = [])
	{
		$sdk					= self::getSdkAppid($one_id, $two_id);

		// 参数设置
		$par 					= [];
		$par[0]['ImageWidth'] 	= isset($data[0]['ImageWidth']) ? $data[0]['ImageWidth'] : 320;
		$par[0]['ImageHeight'] 	= isset($data[0]['ImageWidth']) ? $data[0]['ImageWidth'] : 568;

		$params 				= [
			'MixStreamSessionId' 		=> $sdk['com_str'],
			'MixStreamTemplateId' 		=> 390,									// PK模板ID
			'InputStreamList' 			=> [
				[
					'InputStreamName' 	=> $sdk['com_str'],
					'LayoutParams' 		=> [
						'ImageLayer' 	=> 1,
						"InputType" 	=> 3,
						'ImageWidth' 	=> $par[0]['ImageWidth'],	 			// 画布宽
						'ImageHeight' 	=> $par[0]['ImageHeight'], 				// 画布高
						'Color' 		=> "0x000000"
					]
				],
				[
					'InputStreamName' 	=> $sdk['one_str'],						// 一主播流
					'LayoutParams' 		=> [
						'ImageLayer' 	=> 2
					]
				],
				[
					'InputStreamName' 	=> $sdk['two_str'],						// 二主播流
					'LayoutParams' 		=> [
						'ImageLayer' 	=> 3
					]
				]
			],
			'OutputParams' => [
				'OutputStreamName' 		=> $sdk['one_str']
			]
		];

		return self::commonMixedFlow($params);									// 调用公共混流方法
	}

	/**
	 *  自定义连麦
	 */
	public static function customWheat($one_id, $two_id, $data = [])
	{	
		$sdk							= self::getSdkAppid($one_id, $two_id);

		// 参数设置
		$par 							= [];
		$par[0]['ImageWidth'] 			= isset($data[0]['ImageWidth']) ? $data[0]['ImageWidth'] : 1080;
		$par[0]['ImageHeight'] 			= isset($data[0]['ImageHeight']) ? $data[0]['ImageHeight'] : 1920;
		$par[1]['ImageWidth'] 			= isset($data[1]['ImageWidth']) ? $data[1]['ImageWidth'] : 1080;
		$par[1]['ImageHeight'] 			= isset($data[1]['ImageHeight']) ? $data[1]['ImageHeight'] : 1920;
		$par[2]['ImageWidth'] 			= isset($data[2]['ImageWidth']) ? $data[2]['ImageWidth'] : 230;
		$par[2]['ImageHeight'] 			= isset($data[2]['ImageHeight']) ? $data[2]['ImageHeight'] : 409;
		$par[2]['LocationX'] 			= isset($data[2]['LocationX']) ? $data[2]['LocationX'] : 0.68;
		$par[2]['LocationY'] 			= isset($data[2]['LocationY']) ? $data[2]['LocationY'] : 0.65;
		$par[2]['CropWidth'] 			= isset($data[2]['CropWidth']) ? $data[2]['CropWidth'] : 0.8;
		$par[2]['CropHeight'] 			= isset($data[2]['CropHeight']) ? $data[2]['CropHeight'] : 0.8;
		$par[2]['CropStartLocationX'] 	= isset($data[2]['CropStartLocationX']) ? $data[2]['CropStartLocationX'] : 0.1;
		$par[2]['CropStartLocationY'] 	= isset($data[2]['CropStartLocationY']) ? $data[2]['CropStartLocationY'] : 0.1;

		$params 				= [
			'MixStreamSessionId' 		 	=> $sdk['com_str'],
			'InputStreamList'		     	=> [
				[
					'InputStreamName'    	=> $sdk['com_str'],
					'LayoutParams'       	=> [
						'ImageLayer'     	=> 1,
						'InputType'      	=> 3,
						'ImageWidth'     	=> $par[0]['ImageWidth'],			// 画布宽
						'ImageHeight'       => $par[0]['ImageHeight']			// 画布高
					]
				],
				[
					'InputStreamName'     	=> $sdk['one_str'],					// 一主播流
					'LayoutParams'        	=> [
						'ImageLayer'        => 2,
						'ImageWidth'        => $par[1]['ImageWidth'],
						'ImageHeight'       => $par[1]['ImageHeight']
					]
				],
				[
					'InputStreamName'     	=> $sdk['two_str'],					// 二主播流	
					'LayoutParams'        	=> [
						'ImageLayer'        => 3,
						'ImageWidth'        => $par[2]['ImageWidth'],			// 小窗口宽	
						'ImageHeight'       => $par[2]['ImageHeight'],			// 小窗口高
						'LocationX'         => $par[2]['LocationX'],			// 小窗口坐标的X轴
						'LocationY'         => $par[2]['LocationY']				// 小窗口坐标的Y轴
					],
					'CropParams'          	=> [
						'CropWidth'         => $par[2]['CropWidth'],			// 裁剪宽
						'CropHeight'        => $par[2]['CropHeight'],			// 裁剪高
						'CropStartLocationX'=> $par[2]['CropStartLocationX'],	// 裁剪开始X轴
						'CropStartLocationY'=> $par[2]['CropStartLocationY']	// 裁剪开始Y轴
					]
				]
			],
			'OutputParams'          		=> [
				'OutputStreamName'     		=> $sdk['one_str']					// 一主播流
			]
		];

		return self::commonMixedFlow($params);									// 调用公共混流方法
	}

	/**
	 *  取消混流
	 */
	public static function cancelMixture($one_id, $two_id)
	{
		$sdk	= self::getSdkAppid($one_id, $two_id);
		$params = ['MixStreamSessionId' => $sdk['com_str']];

		return self::commonMixedFlow($params, 'cancel');
	}

    /**
     * 三方断流操作
     */
	public static function tripartite($params)
    {
        try {

            $cred = new Credential(self::$secretId, self::$secretKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("live.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new LiveClient($cred, "", $clientProfile);

            $req = new DropLiveStreamRequest();

            $req->fromJsonString(json_encode($params));

            $resp =  $client->DropLiveStream($req);

            return $resp->toJsonString();
        }
        catch(TencentCloudSDKException $e) {
            echo $e;
        }
    }
}
?>