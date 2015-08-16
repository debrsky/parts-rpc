<?php
/**
 * MxgroupRpcServiceClient
 * @author GapArchi <peh626@gmail.com>
 * @date 27.05.2014 15:08
 */
namespace gaparchi;
use Yii;

class MxgroupRpcServiceClient {
    private $url_send;
    private $session;

    public function __construct() {
        $url = 'http://zakaz.mxgroup.ru/mxapi/?m=login&login=kontakt@tansin-vl.ru&password=vjqrfvjqrf&out=json';
        $result = $this->sendRequest($url);
        if ($result->status == 'ok'){
            $this->session = $result->session;
            echo $this->session ."\n";
        }else{
            Yii::error('API сессия для MXgroup не получена','csv');
        }
    }

    /**
     * Возвращает токен клиента для получения цен, используется в методах : <a href="#GetItem">GetItem</a>, <a href="#GetItemsByGroup">GetItemsByGroup</a> , <a href="#GetAllItems">GetAllItems</a>, <a href="#GetDeposits">GetDeposits</a>, <a href="#GetPrices">GetPrices</a>
     */
    private function sendRequest($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        $result = curl_exec($ch);
        for ($i=1;$i<=20;$i++){
            if($i == 20){
                Yii::warning('Too many request - MXgroup, 20 time','csv');
                return false;
            }
            $result = curl_exec($ch);
            if ($result === 'Too many request'){
                echo 'Too many request, sleep '.(600).' sec'."\n";
                sleep(600);
                continue;
            }else{
                break;
            }
        }

        curl_close($ch);
        return json_decode($result);
    }

    /**
     * Получить остатки, цены и сроки доставки только по одному Артикулу для конкретного Бренда
     * включая аналоги (замены). Для получения проценки по конкретному Артикулу для всех
     * возможных брендов без аналогов (замен) необходимо использовать StockByArticleList
     * @param type $brand
     * @param type $article
     */
    public function SearchZapros($article) {
        $url = 'http://zakaz.mxgroup.ru/mxapi/?m=search&session='.$this->session.'&zapros='.urlencode($article).'&out=json';
        //echo $url."\n";
        return $this->sendRequest($url);
    }
}