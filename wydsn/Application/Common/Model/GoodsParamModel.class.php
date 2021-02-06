<?php


namespace Common\Model;


class GoodsParamModel
{
    // 商品id
    public $id;
    // 淘宝商品id
    public $goodsId;
    // 商品淘宝链接
    public $itemLink;
    // 淘宝标题
    public $title;
    // 推广文案
    public $desc;
    // 淘宝分类
    public $cid;
    // 商品主图链接
    public $mainPic;
    // 营销主图链接
    public $marketingMainPic;
    // 商品视频
    public $video;
    // 商品原价
    public $originalPrice;
    // 券后价
    public $actualPrice;
    // 折扣力度
    public $discounts;
    // 佣金类型，0-通用，1-定向，2-高佣，3-营销计划
    public $commissionType;
    // 佣金比例
    public $commissionRate;
    // 优惠券ID
    public $couponId;
    // 优惠券链接
    public $couponLink;
    // 券总量
    public $couponTotalNum;
    // 领券量
    public $couponReceiveNum;
    // 优惠券开始时间
    public $couponStartTime;
    // 优惠券结束时间
    public $couponEndTime;
    // 优惠券金额
    public $couponPrice;
    // 优惠券使用条件
    public $couponConditions;
    // 30天销量
    public $monthSales;
    // 2小时销量
    public $twoHoursSales;
    // 当日销量
    public $dailySales;
    // 是否是品牌商品
    public $brand;
    // 品牌id
    public $brandId;
    // 品牌名称
    public $brandName;
    // 商品上架时间
    public $createTime;
    // 活动类型，1-无活动，2-淘抢购，3-聚划算
    public $activityType;
    // 活动开始时间
    public $activityStartTime;
    // 活动结束时间
    public $activityEndTime;
    // 店铺类型，1-天猫，0-淘宝
    public $shopType;
    // 是否海淘，1-海淘商品，0-非海淘商品
    public $haitao;
    // 淘宝卖家id
    public $sellerId;
    // 店铺名称
    public $shopName;
    // 卖家昵称
    public $nickName;
    // 淘宝店铺等级
    public $shopLevel;
    // 描述分
    public $descScore;
    // 描述相符
    public $dsrScore;
    // 描述同行比
    public $dsrPercent;
    // 服务态度
    public $shipScore;
    // 服务同行比
    public $shipPercent;
    // 物流服务
    public $serviceScore;
    // 物流同行比
    public $servicePercent;
    // 热推值
    public $hotPush;
    // 放单人名称
    public $teamName;
    // 定金，若无定金，则显示0
    public $quanMLink;
    // 立减，若无立减金额，则显示0
    public $hzQuanOver;
    // 0.不包运费险 1.包运费险
    public $yunfeixian;
    // 预估淘礼金
    public $estimateAmount;
    // 店铺logo
    public $shopLogo;
    // 特色文案 如：买一送一、第二件0元等
    public $specialText;
    // 偏远地区包邮，1-包邮，0-否
    public $freeshipRemoteDistrict;
    // 是否是金牌卖家，1-是，0-非金牌卖家
    public $goldSellers;
    // 商品详情图（需要做适配）
    public $detailPics;
    // 淘宝轮播图
    public $imgs;
    // 相关商品图
    public $reimgs;
    // 热词榜排名（适用于5.热词飙升榜6.热词排行榜）
    public $top;
    // 热搜词（适用于5.热词飙升榜6.热词排行榜）
    public $keyWord;
    //排名提升值（适用于5.热词飙升榜）
    public $upVal;
    //排名热度值
    public $hotVal;


    // 扩展字段，放各平台特殊字段
    public $extInfo;

}