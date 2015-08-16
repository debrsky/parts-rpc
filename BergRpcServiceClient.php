<?php
/**
 * BergRpcServiceClient
 * @author GapArchi <peh626@gmail.com>
 * @date 27.05.2014 15:08
 */
namespace gaparchi;
/**
 * Документация по API http://api.berg.ru/
 */
class BergRpcServiceClient {

    private $auth_key;

    public function __construct() {
        $this->url_send = 'http://api.berg.ru';
        $this->auth_key = '0475f010713302e98c4406771cc36c8bb82c3395c27435c162312f0bfed7895d';
    }

    /**
     * Метод генерирует запрос к API Berg
     */
    private function sendRequest($method,$items=false,$analogs = 0) {

        if($items){
            $request_part = [
                'items' => $items,
                'warehouse_types'=> [1],
                'analogs'=>$analogs,
                'key'=>$this->auth_key
            ];
        }else{
            $request_part = ['key'=>$this->auth_key];
        }

        $ch = curl_init($this->url_send.$method.'.json?'.http_build_query($request_part));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }

    /**
     * Метод возвращает кучу запчастей с заменами
     * @param type $brand Бренд строкой
     * @param type $article Артикул производителя
     */
    public function getPartsArticleBrand($article,$brand,$analog=0) {
        $method='/ordering/get_stock';
        $items  = array();
        $items[] = [
            'resource_article' => $brand,
            'brand_name'=> $article,
        ];

        return $this->sendRequest($method,$items,$analog);
    }

    /**
     * Метод возвращает один товар по внутренему идентификатору
     * @param type $num_supplier внутрений id товара поставщика
     */
    public function getFromNumSupplier($num_supplier) {
        $method='/ordering/get_stock';
        $items  = array();
        $items[] = [
            'resource_id' => $num_supplier,
        ];

        return $this->sendRequest($method,$items);
    }

}