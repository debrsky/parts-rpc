<?php
/**
 * TmpartsRpcServiceClient
 * @author GapArchi <peh626@gmail.com>
 * @date 20.11.2015
 */
namespace gaparchi;

/**
 * RPC для Yandex Direct API
 * Документация по api - https://tech.yandex.ru/direct/doc/dg-v4/examples/php-sample-json-docpage/
 */
class YandexDirectRpcServiceClient {
    private $api_url;
    private $auth_key;

    public function __construct() {
        $this->api_url = 'https://api-sandbox.direct.yandex.ru/live/v4/json/';
        //$this->login = \Yii::$app->params['yandexDirectLogin'];
        $this->auth_key = \Yii::$app->params['yandexDirectToken'];
    }

    private function sendRequest($method,$params=[]) {

        # формирование запроса
        $request = array(
            'token'=> $this->auth_key,
            'method'=> $method,
            'param'=> self::utf8($params),
            'locale'=> 'ru',
        );
        # преобразование в JSON-формат
        $request = json_encode($request);

        # параметры запроса
        $opts = array(
            'http'=>array(
                'method'=>"POST",
                'content'=>$request,
                'header' => "Content-Type: text/plain; charset=UTF-8\r\n".
                            "Content-Length: ".strlen($request)."\r\n",
            )
        );
        $context = stream_context_create($opts);
        $result = \file_get_contents($this->api_url, 0, $context);
        return $result;
    }
    /**
     * Метод для преобразование значений массива в UTF-8
     * @autor yandex
     */
    private function utf8($struct) {
        foreach ($struct as $key => $value) {
            if (is_array($value)) {
                $struct[$key] = self::utf8($value);
            }
            elseif (is_string($value)) {
                $struct[$key] = utf8_encode($value);
            }
        }
        return $struct;
    }
    /**
     * https://tech.yandex.ru/direct/doc/dg-v4/reference/GetCampaignsList-docpage/
     */
    public function GetCampaignsList() {
        $params=[];
        return $this->sendRequest(__FUNCTION__,$params);
    }
    /**
     * https://tech.yandex.ru/direct/doc/dg-v4/reference/GetBanners-docpage/
     */
    public function GetBanners(array $CampaignIDS, array $BannerIDS) {
        $params = [];
        if (count($CampaignIDS)){
            $params['CampaignIDS'] = $CampaignIDS;
        }
        if (count($BannerIDS)){
            $params['BannerIDS'] = $BannerIDS;
        }
        return $this->sendRequest(__FUNCTION__,$params);
    }
    public function GetRegions() {
        return $this->sendRequest(__FUNCTION__);
    }
    /**
     * https://tech.yandex.ru/direct/doc/dg-v4/reference/CreateOrUpdateBanners-docpage/
     */
    public function CreateOrUpdateBanners(array $BannerInfos) {
        return $this->sendRequest(__FUNCTION__,$BannerInfos);
    }
    /**
     * https://tech.yandex.ru/direct/doc/dg-v4/reference/CreateOrUpdateCampaign-docpage/
     */
    public function CreateOrUpdateCampaign(array $CampaignInfos) {
        return $this->sendRequest(__FUNCTION__,$CampaignInfos);
    }
    /**
     * https://tech.yandex.ru/direct/doc/dg-v4/reference/DeleteBanners-docpage/
     */
    public function DeleteBanners($CampaignID,array $BannerIDS) {
        $params=[
            'CampaignID'=>$CampaignID,
            'BannerIDS'=>$BannerIDS
            ];
        return $this->sendRequest(__FUNCTION__,$params);
    }
    /**
     * https://tech.yandex.ru/direct/doc/dg-v4/reference/ResumeCampaign-docpage/
     */
    public function ResumeCampaign($campaign_id) {
            $params=[
            'CampaignID'=>$campaign_id,
            ];
        return $this->sendRequest(__FUNCTION__,$params);
    }
    /**
     * https://tech.yandex.ru/direct/doc/dg-v4/reference/StopCampaign-docpage/
     */
    public function StopCampaign($campaign_id) {
            $params=[
            'CampaignID'=>$campaign_id,
            ];
        return $this->sendRequest(__FUNCTION__,$params);
    }
    /**
     * https://tech.yandex.ru/direct/doc/dg-v4/reference/ResumeBanners-docpage/
     */
    public function ResumeBanners($campaign_id,array $BannerIDS) {
            $params=[
                'CampaignID'=>$campaign_id,
                'BannerIDS'=>$BannerIDS
            ];
        return $this->sendRequest(__FUNCTION__,$params);
    }
    /**
     * https://tech.yandex.ru/direct/doc/dg-v4/reference/StopBanners-docpage/
     */
    public function StopBanners($campaign_id,array $BannerIDS) {
            $params=[
                'CampaignID'=>$campaign_id,
                'BannerIDS'=>$BannerIDS
            ];
        return $this->sendRequest(__FUNCTION__,$params);
    }
    /**
     * https://tech.yandex.ru/direct/doc/dg-v4/reference/ModerateBanners-docpage/
     */
    public function ModerateBanners($campaign_id,array $BannerIDS) {
            $params=[
                'CampaignID'=>$campaign_id,
                'BannerIDS'=>$BannerIDS
            ];
        return $this->sendRequest(__FUNCTION__,$params);
    }
    /**
     * https://tech.yandex.ru/direct/doc/dg-v4/reference/SetAutoPrice-docpage/
     */
    public function SetAutoPrice($campaign_id,$price) {
            $params=[
                'CampaignID'=>$campaign_id,
                'Mode'=>'SinglePrice',
                'SinglePrice'=> $price
            ];
        return $this->sendRequest(__FUNCTION__,$params);
    }
}