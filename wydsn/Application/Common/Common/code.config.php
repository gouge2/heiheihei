<?php
/**
 * 返回码配置文件
 * _zh(display)后缀数组代表内容展示
 */
// 公共返回码
$error_code_common = array (
	'SUCCESS'                    => 0,  // 成功
	'INIT'                       => 1,  // 初始化
	'DB_ERROR'                   => 2,  // 数据库错误
	'PARAMETER_ERROR'            => 3,  // 参数不正确，参数缺失
	'SIGN_ERROR'                 => 4,  // 验签错误
	'FILE_UPLOAD_ERROR'          => 5,  // 文件上传失败
	'PARAMETER_FORMAT_ERROR'     => 6,  // 数据库自动验证，参数格式错误
	'PHONE_FORMAT_ERROR'         => 7,  // 不是正确的手机格式
	'EMAIL_FORMAT_ERROR'         => 8,  // 不是正确的邮箱格式
	'PASSWORD_FORMAT_ERROR'      => 9,  // 密码不少于6位
	'TWICE_PASSWORD_UNEQUAL'     => 10, // 两次密码不相同
	'SHARE_CODE_GENERATION_FAIL' => 11, // 分享码生成失败
	'THIRD_REQUEST_FAIL' 		 => 12, // 第三方接口请求不成功
	'LOGISTICS_TRACK_NULL' 		 => 13, // 物流轨迹信息空
);
define ( 'error_code_common', json_encode ( $error_code_common ) );

$error_code_common_zh = array (
	0  => '成功',
	1  => '初始化',
	2  => '数据库错误',
	3  => '参数不正确，参数缺失',
	4  => '验签错误',
	5  => '文件上传失败',
	6  => '数据库自动验证，参数格式错误',
	7  => '不是正确的手机格式',
	8  => '不是正确的邮箱格式',
	9  => '密码不少于6位',
	10 => '两次密码不相同',
	11 => '分享码生成失败',
	12 => '第三方接口请求不成功',
	13 => '物流轨迹信息空',
);
define ( 'error_code_common_zh', json_encode ( $error_code_common_zh ) );

// 用户相关返回码
$error_code_user = array (
	'USERNAME_ALREADY_EXISTS'                    => 101, // 该用户名称已被使用！
	'USERNAME_FORMAT_ERROR'                      => 102, // 用户名格式不正确
	'PHONE_ALREADY_EXISTS'                       => 103, // 该手机号码已被使用！
	'EMAIL_ALREADY_EXISTS'                       => 104, // 该邮箱已被使用！
	'USER_NOT_EXIST'                             => 105, // 用户不存在
	'USER_FROZEN'                                => 106, // 该用户已被冻结
	'USER_LOGIN_ERROR'                           => 107, // 账号或密码错误
	'USER_OLD_PASSWORD_ERROR'                    => 108, // 原密码错误
	'PHONE_NON_REGISTERED'                       => 109, // 该手机号码尚未注册
	'EMAIL_NON_REGISTERED'                       => 110, // 该邮箱尚未注册
	'NICKNAME_ALREADY_EXISTS'                    => 111, // 该昵称已存在，不准重复
	'REFERRER_NOT_EXISTS'                        => 112, // 推荐人不存在
	'PLEASE_DO_NOT_CHECK_IN_AGAIN_TODAY'         => 113, // 今日已签到，请勿重复签到
	'LACK_OF_POINT'                              => 114, // 积分不足
	'RECHARGE_MONEY_FORMAT_ERROR'                => 115, // 充值金额不是正确的货币格式
	'BALANCE_INSUFFICIENT'                       => 116, // 余额不足
	'WITHDRAWAL_AMOUNT_MUST_BE_A_MULTIPLE_OF_10' => 117, // 提现金额必须为10的倍数
	'WITHDRAWAL_ONLY_ONCE_A_DAY'                 => 118, // 每天只准提现一次
	'UNAUTHORIZED'                               => 119, // 对不起，您尚未被授权，请联系管理员
	'AUTH_CODE_NOT_EXIST'                        => 120, // 授权码不存在
	'AUTH_CODE_IS_USED'                          => 121, // 该授权码已被使用
	'ONLY_ORDINARY_MEMBERS_CAN_BE_UPGRADED'      => 122, // 只有普通会员可以升级
	'NOT_CONCERN_SELF'                           => 123, // 不可以自己关注自己
	'USER_TOKEN_DISABLED'                        => 124, // 请重新登录！
	'DO_NOT_HAVE'                                => 125, // 对不起，您没有权限
	'REAL_CENTRE'                                => 126, // 您已申请认证，请等待审核
	'IDE_WRONG'                                  => 127, // 身份证号码不正确
	'LACK_TOKEN'                                 => 128, // 缺少token参数值
	'VERIFY_APPLE_FAIL'                          => 129, // 验证apple用户信息失败
	'NOT_APPLE_IDEN'                             => 130, // userIdentifier参数不能为空值
);
define ( 'error_code_user', json_encode ( $error_code_user ) );

$error_code_user_zh = array (
	101 => '该用户名称已被使用！',
	102 => '用户名格式不正确',
	103 => '该手机号码已被使用！',
	104 => '该邮箱已被使用！',
	105 => '用户不存在',
	106 => '该用户已被冻结',
	107 => '账号或密码错误',
	108 => '原密码错误',
	109 => '该手机号码尚未注册',
	110 => '该邮箱尚未注册',
	111 => '该昵称已存在，不准重复',
	112 => '推荐人不存在',
	113 => '今日已签到，请勿重复签到',
	114 => '积分不足',
	115 => '充值金额不是正确的货币格式',
	116 => '余额不足',
	117 => '提现金额必须为10的倍数',
	118 => '每天只准提现一次',
	119 => '对不起，您尚未被授权，请联系管理员',
	120 => '授权码不存在',
	121 => '该授权码已被使用',
	122 => '只有普通会员可以升级',
	123 => '不可以自己关注自己',
	124 => '请重新登录！',
	125 => '对不起，您没有权限',
	126 => '您已申请认证，请等待审核',
	127 => '身份证号码不正确',
	128 => '',
	129 => '验证apple用户信息失败',
	130 => 'userIdentifier参数不能为空值',
);
define ( 'error_code_user_zh', json_encode ( $error_code_user_zh ) );

// 商品返回码
$error_code_goods = array (
    'GOODS_NOT_EXIST'                                     => 201, // 该商品不存在
    'GOODS_ALREADY_COLLECTED'                             => 202, // 已收藏该商品，请勿重复收藏
    'GOODS_NOT_COLLECT'                                   => 203, // 您尚未收藏该商品
    'ADDRESS_NOT_EXIST'                                   => 204, // 收货地址不存在
    'ORDER_NOT_EXIST'                                     => 205, // 订单不存在
    'ONLY_UNPAID_ORDER_CAN_BE_CANCELLED'                  => 206, // 只有未付款订单才可以取消
    'MUST_HAVE_BANK_CARD_OR_ALIPAY_ACCOUNT'               => 207, // 银行卡号、支付宝账号必须填写其中一个
    'LACK_OF_POINT'                                       => 208, // 积分不足
    'ONLY_DELIVERY_ORDER_CAN_BE_CONFIRMED'                => 209, // 只有已发货订单才可以确认收货
    'GOODS_IS_COMMENTED'                                  => 210, // 该商品已评价
    'GOODS_COMMENT_IMG_ABOVE_THE_LIMIT'                   => 211, // 商品评论图片不超过6张
    'ORDER_NOT_BELONG_USER'                               => 212, // 该订单不属于您
    'ONLY_UNCONFIRMED_RECEIPT_ORDER_CAN_APPLY_FOR_REFUND' => 213, // 只有未确认收货订单可以申请退款
    'ONLY_CONFIRMED_RECEIPT_ORDER_CAN_BE_COMMENTED'       => 214, // 只有已确认收货订单可以评论
    'ONLY_UNCONFIRMED_RECEIPT_ORDER_CAN_DELAY_RECEIVING'  => 215, // 只有未确认收货订单可以延迟收货
    'ONLY_DELAY_RECEIPT_ONCE'                             => 216, // 只能延迟收货一次
    'PRICE_IS_NOT_CURRENCY_FORMAT'                        => 217, // 商品价格不是正确的货币格式
    'GOODS_CAT_NOT_EXIST'                                 => 218, // 商品分类不存在
    'INVENTORY_SHORTAGE'                                  => 219, // 库存不足
    'SEND_DATE_FORMAT_ERROR'                              => 220, // 配送时间不是正确的日期格式
    'SEND_DATE_NOT_LATER_THAN_TODAY'                      => 221, // 配送时间不能晚于当前时间
    'GOODS_ON_SALE'                                       => 222, // 您已上架过该商品，请勿重复操作！
    'GOODS_SKU_NOT_EXIST'                                 => 223, // 商品规格配置不存在
    'GIFT_PACKAGE_GOODS_AND_SELF_OPERATED_GOODS_NEED_TO_BE_SETTLED_SEPARATELY'                                 => 224, // 礼包商品和自营商品需要分开结算
    'ONLY_UNPAID_ORDER_CAN_BE_CANCELLED_OR_END'                  => 225, // 只有未付款订单才可以取消
);
define ( 'error_code_goods', json_encode ( $error_code_goods ) );

$error_code_goods_zh = array (
    201 => '该商品不存在',
    202 => '已收藏该商品，请勿重复收藏',
    203 => '您尚未收藏该商品',
    204 => '收货地址不存在',
    205 => '订单不存在',
    206 => '只有未付款订单才可以取消',
    207 => '银行卡号、支付宝账号必须填写其中一个',
    208 => '积分不足',
    209 => '只有已发货订单才可以确认收货',
    210 => '该商品已评价',
    211 => '商品评论图片不超过6张',
    212 => '该订单不属于您',
    213 => '只有未确认收货订单可以申请退款',
    214 => '只有已确认收货订单可以评论',
    215 => '只有未确认收货订单可以延迟收货',
    216 => '只能延迟收货一次',
    217 => '商品价格不是正确的货币格式',
    218 => '商品分类不存在',
    219 => '库存不足',
    220 => '配送时间不是正确的日期格式',
    221 => '配送时间不能晚于当前时间',
    222 => '您已上架过该商品，请勿重复操作！',
    223 => '商品规格配置不存在',
    224 => '礼包商品和自营商品需要分开结算',
    225 => '只有完成的订单以及取消的订单才能删除',
);
define ( 'error_code_goods_zh', json_encode ( $error_code_goods_zh ) );

// 发票返回码
$error_code_invoice = array (
    'INVOICE_CAN_NOT_BE_NULL' => 301, // 请填写企业纳税人识别号
    'INVOICE_NOT_EXIST'       => 302, // 发票不存在
    'INVOICE_NOT_BELONG_YOU'  => 303, // 该发票不属于您
);
define ( 'error_code_invoice', json_encode ( $error_code_invoice ) );

$error_code_invoice_zh = array (
    301 => '请填写企业纳税人识别号',
    302 => '发票不存在',
    303 => '该发票不属于您'
);
define ( 'error_code_invoice_zh', json_encode ( $error_code_invoice_zh ) );

// 手机短信返回码
$error_code_sms = array (
	'API_ERROR'         => 1001, // 短信接口返回的错误
	'SEND_LIMIT'        => 1002, // 1分钟内只允许发送一条短信
	'MOBILE_NOT_EXIST'  => 1003, // 手机号码不存在
	'BEYOND_VALID_TIME' => 1004, // 验证码已过有效时间
	'CODE_ERROR'        => 1005, // 验证码错误
);
define ( 'error_code_sms', json_encode ( $error_code_sms ) );

$error_code_sms_zh = array (
	1001 => '短信接口返回的错误',
	1002 => '1分钟内只允许发送一条短信',
	1003 => '手机号码不存在',
	1004 => '验证码已过有效时间',
	1005 => '验证码错误',
);
define ( 'error_code_sms_zh', json_encode ( $error_code_sms_zh ) );

// 邮件返回码
$error_code_email = array (
	'API_ERROR'         => 1101, // 接口返回的错误
	'SEND_LIMIT'        => 1102, // 1分钟内只允许发送一封邮件
	'EMAIL_NOT_EXIST'   => 1103, // 邮箱不存在
	'BEYOND_VALID_TIME' => 1104, // 验证码已过有效时间
	'CODE_ERROR'        => 1105, // 验证码错误
);
define ( 'error_code_email', json_encode ( $error_code_email ) );

$error_code_email_zh = array (
	1101 => '接口返回的错误',
	1102 => '1分钟内只允许发送一封邮件',
	1103 => '邮箱不存在',
	1104 => '验证码已过有效时间',
	1105 => '验证码错误',
);
define ( 'error_code_email_zh', json_encode ( $error_code_email_zh ) );

// 短视频返回码
$error_code_short = array (
	'YET_PRAISE'    => 600, // 已点赞，不可再操作
	'NOT_PRAISE'    => 601, // 未点赞，不可此操作
	'YET_CONCERN'   => 602, // 已关注，不可再操作
	'NOT_CONCERN'   => 603, // 未关注，不可此操作
	'NOT_EXIST'     => 604, // 视频文件不存在
	'REUSE_COMMENT' => 605, // 内容重复，不可再操作
	'NOT_COMMENT' 	=> 606, // 评论内容不存在
);
define ( 'error_code_short', json_encode ( $error_code_short ) );

$error_code_short_zh = array (
	600 => '已点赞，不可再操作',
	601 => '未点赞，不可此操作',
	602 => '已关注，不可再操作',
	603 => '未关注，不可此操作',
	604 => '视频文件不存在',
	605 => '内容重复，不可再操作',
	606 => '评论内容不存在',
);
define ( 'error_code_short_zh', json_encode ( $error_code_short_zh ) );

// 直播返回码
$error_code_live = array (
	'NOT_EXIST'     	=> 700, // 房间记录不存在
	'NOT_MATCHE'    	=> 701, // 房间记录不匹配
	'NOT_GIFT'      	=> 702, // 礼物不存在
	'NOT_Fill'      	=> 703, // 充值选项不存在
	'NOT_Fill_RECORD'   => 704, // 充值记录不存在
	'MIXED_FLOW_FAIL'   => 705, // 混流操作失败
	'USER_NOT_HOST'   	=> 706, // 该用户不是主播
);
define ( 'error_code_live', json_encode ( $error_code_live ) );

$error_code_live_zh = array (
	700 => '房间记录不存在',
	701 => '房间记录不匹配',
	702 => '礼物不存在',
	703 => '充值选项不存在',
	704 => '充值记录不存在',
	705 => '混流操作失败',
	706 => '该用户不是主播',
);
define ( 'error_code_live_zh', json_encode ( $error_code_live_zh ) );

