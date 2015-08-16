<?php
/**
 * RosskoRpcServiceClient
 * @author GapArchi <peh626@gmail.com>
 * @date 27.05.2014 15:08
 */
namespace gaparchi;

class RosskoRpcServiceClient {
    private $key1;
    private $key2;
    private $client;

    public function __construct() {
        $api_key = \common\models\Suppliers::find()
                        ->select('api_key')
                        ->where(['id_supplier'=>'4'])
                        ->limit(1)
                        ->scalar();
        $keys = explode(':', $api_key);
        $this->key1 = $keys[0];
        $this->key2 = $keys[1];
        $this->client = new \SoapClient('http://vl.rossko.ru/service/v1/GetSearch?wsdl',["exceptions" => true]);
    }

    public function GetSearch($search_string) {
        try {
            $result = $this->client->GetSearch([
                'KEY1' => $this->key1,
                'KEY2' => $this->key2,
                'TEXT' => $search_string
                 ]);
        } catch (\SoapFault $exception) {
            \Yii::warning($exception->getMessage(),'rossko_api');
            return false;
        }catch (\Exception $e) {
            \Yii::warning($e->getMessage(),'rossko_api');
            return false;
        }

        if ($result->SearchResults->SearchResult->Success){
            return $result->SearchResults->SearchResult->PartsList->Part;
        }else{
            return false;
        }
    }
}