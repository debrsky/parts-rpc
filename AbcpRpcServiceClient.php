<?php
/**
 * AbcpRpcServiceClient
 * @author GapArchi <peh626@gmail.com>
 * @date 27.05.2014 15:08
 */
namespace gaparchi;
/**
 * Документация по Abcp API http://docs.abcp.ru/wiki/API:Docs
 */
class AbcpRpcServiceClient {

    private $userlogin;
    private $userpsw;
    private $urlapi;

    public function __construct($userlogin,$userpsw) {
        $this->urlapi = 'http://id3789.public.api.abcp.ru';
        $this->userlogin = $userlogin;
        $this->userpsw = $userpsw;
    }


    /**
     * Метод генерирует запрос к API Berg
     */
    private function sendRequest($method,$param=[]) {

        $request_part = [
            'userlogin' => $this->userlogin,
            'userpsw'=> $this->userpsw,
        ];

        $ch = curl_init($this->urlapi.$method.'?'.http_build_query(array_merge($request_part,$param)));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }

    public function getArticlesBrands() {
        $method='/articles/brands';
        return $this->sendRequest($method);
    }

    public function getSearchTips($str) {
        $method='/search/tips';
        $param= ['number' => $str];
        return $this->sendRequest($method,$param);
    }
    public function getArticlesInfo($brand,$article) {
        $method='/articles/info/';
        $param= [
            'brand'  => $brand,
            'number' => $article,
            'format' =>'bnci',//tpmi
            'locale' =>'ru_RU'
                ];
        return $this->sendRequest($method,$param);
    }
}