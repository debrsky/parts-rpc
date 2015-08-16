<?php
/**
 * TmpartsRpcServiceClient
 * @author GapArchi <peh626@gmail.com>
 * @date 27.05.2014 15:08
 */
namespace gaparchi;

class TmpartsRpcServiceClient {
    private $url_send;
    private $auth_key;

    public function __construct($auth_key=false) {
        $this->url_send = 'http://api.tmparts.ru/api/';
        if($auth_key){
            $this->auth_key = $auth_key;
        }else{
            $this->auth_key = \common\models\Suppliers::find()
                        ->select('api_key')
                        ->where(['id_supplier'=>'5'])
                        ->limit(1)
                        ->scalar();
        }
    }

    /**
     * Возвращает токен клиента для получения цен, используется в методах : <a href="#GetItem">GetItem</a>, <a href="#GetItemsByGroup">GetItemsByGroup</a> , <a href="#GetAllItems">GetAllItems</a>, <a href="#GetDeposits">GetDeposits</a>, <a href="#GetPrices">GetPrices</a>
     */
    private function sendRequest($method,$JSONparameter) {

        $fields = ['JSONparameter'=>json_encode($JSONparameter)];

        $ch = curl_init($this->url_send.$method.'?'.http_build_query($fields));
        $headers = array('Authorization: Bearer ' . $this->auth_key);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
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
    public function StockByArticle($brand,$article) {
        $JSONparameter = [
            'Brand' => $brand,
            'Article'      => $article,
            'is_main_warehouse'=> 1
        ];
        return $this->sendRequest(__FUNCTION__,$JSONparameter);
    }

    /**
     * Получить остатки, цены и сроки доставки по списку товаров без аналогов (замен).
     * Для получения проценки аналогов (замен) для конкретного Артикула и
     * Бренда необходимо использовать StockByArticleМетод опрашивает сервис на наличие товара + бренд
     * @param type $brand
     * @param type $article
     */
    public function StockByArticleList($brand_article_arr) {

        $articles_list = array();
        foreach ($brand_article_arr as $value) {
            $articles_list[] = ['Brand'=>$value['brand'],'Article'=>$value['article']];
        }

        $JSONparameter = [
            'is_main_warehouse'=> 1,
            'Brand_Article_List' => $articles_list,
        ];
        return $this->sendRequest(__FUNCTION__,$JSONparameter);
    }
}