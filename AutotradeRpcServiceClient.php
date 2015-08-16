<?php
/**
 * AutotradeRpcServiceClient
 * @author GapArchi <peh626@gmail.com>
 * @date 24.05.2014 4:55
 */
namespace gaparchi;

class AutotradeRpcServiceClient {
    private $url_send;
    private $auth_key;

    public function __construct($auth_key=false) {
        $this->url_send = 'https://api2.autotrade.su/?json';
        if($auth_key){
            $this->auth_key = $auth_key;
        }else{
            $this->auth_key = \common\models\Suppliers::find()
                        ->select('api_key')
                        ->where(['id_supplier'=>'3'])
                        ->limit(1)
                        ->scalar();
        }
    }

    /**
     * Возвращает токен клиента для получения цен, используется в методах : <a href="#GetItem">GetItem</a>, <a href="#GetItemsByGroup">GetItemsByGroup</a> , <a href="#GetAllItems">GetAllItems</a>, <a href="#GetDeposits">GetDeposits</a>, <a href="#GetPrices">GetPrices</a>
     */
    private function sendRequest($method,$param = []) {
        $data = array(
            "auth_key"    => $this->auth_key,
            "method"      => $method,
        );

        if(count($param)){
            $data = array_merge($data, ["params"=>$param]);
        }

        $str_data = 'data='.json_encode($data);

        $ch = curl_init($this->url_send);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$str_data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);  // Seems like good practice

        return json_decode($result);
    }

    public function GetStoragesList() {
        return $this->sendRequest(__FUNCTION__);
    }
    public function GetCatalogsList() {
        return $this->sendRequest(__FUNCTION__);
    }
    public function GetSectionsList($catalog_id) {
        $param = ['catalog_id'=>(int)$catalog_id];
        sleep(1);
        return $this->sendRequest(__FUNCTION__,$param);
    }
    public function GetSubSectionsList($catalog_id,$section_id) {
        $param = [
                'catalog_id'=>(int)$catalog_id,
                'section_id'=>(int)$section_id
                ];
        sleep(1);
        return $this->sendRequest(__FUNCTION__,$param);
    }
    public function GetItemsByCatalog($catalog_id, $section_id, $section_id, $page=1, $limit=999999) {
        $param = [
                'catalog_id'    =>(int)$catalog_id,
                'section_id'    =>(int)$section_id,
                'subsection_ids'=>(array)$section_id,
                'brand'         => '',
                'page'          =>$page,
                'limit'         =>$limit
                ];
        sleep(1);
        return $this->sendRequest(__FUNCTION__,$param);
    }
    //todo: сделать запрос, сразу нескольких запчастей.
    public function GetStocksAndPrices($item){
        $param = [
                'items'=>[
                    $item=>1000
                    ]
                ];
        sleep(0.3);
        return $this->sendRequest(__FUNCTION__,$param);
    }
}