<?php
    /**
     * UniqomRpcServiceClient
     * @author JsonRpcClientGenerator
     * @author GapArchi <peh626@gmail.com>
     * @date 24.05.2014 4:55
     */
    namespace gaparchi;
    use \gaparchi\jsonrpc\BaseJsonRpcClient;

    class UniqomRpcServiceClient extends BaseJsonRpcClient {

        private $TokenClientUniqom;

        public function __construct() {
            $this->TokenClientUniqom = \common\models\Suppliers::find()
                    ->select('api_key')
                    ->where(['id_supplier'=>2])
                    ->limit(1)
                    ->scalar();
            $this->CurlOptions[CURLOPT_URL] = 'http://uniqom.ru/api/server.php';
        }
        /**
         * Возвращает токен клиента для получения цен, используется в методах : <a href="#GetItem">GetItem</a>, <a href="#GetItemsByGroup">GetItemsByGroup</a> , <a href="#GetAllItems">GetAllItems</a>, <a href="#GetDeposits">GetDeposits</a>, <a href="#GetPrices">GetPrices</a>
         
 <br/><b>Пример вызова:</b> {"jsonrpc":"2.0","id":1,"method":"GetToken","params":{"email":"e-mail","pwd":"1234567"}}
         
 <br> <b>Пример ответа сервера:</b> : {"jsonrpc":"2.0","result":{"token":"hwZkPA9AW20cDjGuYHIx2H+90AjWsgn6fnRg"},"id":1}
         * @param string $email почта аккаунта
         * @param string $pwd пароль аккаунта
         * @param bool $isNotification [optional] set to true if call is notification
         * @return BaseJsonRpcCall (result: array)
         */
        public function GetToken( $email, $pwd, $isNotification = false ) {
            return $this->call( __FUNCTION__, array( 'email' => $email, 'pwd' => $pwd ), $this->getRequestId( $isNotification ) );
        }


        /**
         * Товары для группы  GetItemsByGroup($idgroup,$token='',$lim=0,$offset=0), <br/><b>Пример вызова:</b> {"jsonrpc":"2.0","id":6,"method":"GetItemsByGroup","params":{"idgroup":206,"token":"токен","lim":0,"offset":0}}
         
 <br><b>Пример ответа сервера:</b> можно увидеть в методе <i>GetAllItems</i>
         * @param int $idgroup id группы
         * @param string $token [optional] токен
         * @param int $lim [optional] кол-во записей
         * @param int $offset [optional] начальная запись
         * @param bool $isNotification [optional] set to true if call is notification
         * @return BaseJsonRpcCall (result: array)
         */
        public function GetItemsByGroup( $idgroup, $token = '', $lim = 0, $offset = 0, $isNotification = false ) {
            return $this->call( __FUNCTION__, array( 'idgroup' => $idgroup, 'token' => $token, 'lim' => $lim, 'offset' => $offset ), $this->getRequestId( $isNotification ) );
        }


        /**
         * Параметры для товаров (кроссы и oem),$type может быть равен oem или marking.<br /> 
         
 <br/><b>Пример вызова:</b> {"jsonrpc":"2.0","id":2,"method":"GetParameters","params":{"token":"Токен","type":"oem","lim":10,"offset":0}}
         
 <br> <b>Пример ответа сервера:</b> {"jsonrpc":"2.0","result":[{"item_id":"106413","value":"28113-1R100"},{"item_id":"106414","value":"11214-20030"}],"id":2}
         * @param string $type тип может быть равен oem или marking
         * @param int $lim [optional] кол-во записей
         * @param int $offset [optional] начальная запись
         * @param bool $isNotification [optional] set to true if call is notification
         * @return BaseJsonRpcCall (result: array)
         */
        public function GetParameters( $type, $lim = 0, $offset = 0, $isNotification = false ) {
            return $this->call( __FUNCTION__, array( 'token' => $this->TokenClientUniqom, 'type' => $type, 'lim' => $lim, 'offset' => $offset ), $this->getRequestId( $isNotification ) );
        }


        /**
         * Цены для товаров<br><b>Пример вызова:</b> {"jsonrpc":"2.0","id":null,"method":"GetPrices","params":{"token":"токен"}}
         <br/><b>Пример ответа сервера:</b>: {"jsonrpc":"2.0","result":[{"id":"20","price":"240.16"},{"id":"21","price":"240.16"}],"id":2}
         * @param bool $isNotification [optional] set to true if call is notification
         * @return BaseJsonRpcCall (result: array)
         */
        public function GetPrices( $lim = 0, $offset = 0, $isNotification = false ) {
            return $this->call( __FUNCTION__, array( 'token' => $this->TokenClientUniqom, 'lim' => $lim, 'offset' => $offset ), $this->getRequestId( $isNotification ) );
        }


        /**
         * Остатки для товаров, параметр $type отвечает за отображение остатков (1- по филиалу общее,2- по складам филиала)
         
 <br><b>Пример ответа сервера:</b> если параметр = 1: {"jsonrpc":"2.0","result":[{"id":"20","deposit":60},{"id":"21","deposit":40}],"id":2}
         
 <br><b>Пример ответа сервера:</b> если параметр = 2: {"jsonrpc":"2.0","result":[{"id":"20","storage_id":"56","deposit":null},{"id":"20","storage_id":"74","deposit":null},{"id":"20","storage_id":"76","deposit":null},{"id":"21","storage_id":"56","deposit":null},{"id":"21","storage_id":"74","deposit":null},{"id":"21","storage_id":"76","deposit":null}],"id":2}
         
         * @param int $type [optional] отвечает за отображение остатков (1- по филиалу общее,2- по складам филиала)
         * @param bool $isNotification [optional] set to true if call is notification
         * @return BaseJsonRpcCall (result: array)
         */
        public function GetDeposits( $type = 1, $isNotification = false ) {
            return $this->call( __FUNCTION__, array( 'token' => $this->TokenClientUniqom, 'type' => $type ), $this->getRequestId( $isNotification ) );
        }


        /**
         * Все товары uniqom.ru, <br/><b>Пример вызова:</b> {"jsonrpc":"2.0","id":4,"method":"GetAllItems","params":{"token":"токен","lim":500,"offset":2600}}
         
 <br><b>Пример ответа сервера:</b>: {"jsonrpc":"2.0","result":[{"header":"\u0410\u0440\u043e\u043c\u0430\u0442\u0438\u0437\u0430\u0442\u043e\u0440 \u0436\u0438\u0434\u043a\u0438\u0439 CRYSTAL ROCK - ITALIAN LEMON","id":"20","text":"","group_id":null,"article":"E-51","variation":"\u0418\u0422\u0410\u041b\u042c\u042f\u041d\u0421\u041a\u0418\u0419 \u041b\u0415\u041c\u041e\u041d","picture_files":[{"img100":"http:\/\/uniqom.ru\/uploads\/items\/thumb\/100_100_0_20.jpg","img210":"http:\/\/uniqom.ru\/uploads\/items\/thumb\/210_210_0_20.jpg","img130":"http:\/\/uniqom.ru\/uploads\/items\/thumb\/130_80_0_20.jpg","big":"http:\/\/uniqom.ru\/uploads\/items\/20.jpg"}]}],"id":2}
         * @param int $lim [optional] кол-во записей
         * @param int $offset [optional] начальная запись
         * @param bool $isNotification [optional] set to true if call is notification
         * @return BaseJsonRpcCall (result: array)
         */
        public function GetAllItems( $lim = 0, $offset = 0, $isNotification = false ) {
            return $this->call( __FUNCTION__, array( 'token' => $this->TokenClientUniqom, 'lim' => $lim, 'offset' => $offset ), $this->getRequestId( $isNotification ) );
        }


        /**
         * Один товар <br /><b>Пример вызова:</b> {"jsonrpc":"2.0","id":2,"method":"GetItem","params":{"id": ID_товара,"token": "токен"}}
          <br><b>Пример ответа сервера:</b>:{"jsonrpc":"2.0","result":[{"header":"\u0410\u0440\u043e\u043c\u0430\u0442\u0438\u0437\u0430\u0442\u043e\u0440 \u0436\u0438\u0434\u043a\u0438\u0439 CRYSTAL ROCK - ITALIAN LEMON","id":"20","text":"","article":"E-51","variation":"\u0418\u0422\u0410\u041b\u042c\u042f\u041d\u0421\u041a\u0418\u0419 \u041b\u0415\u041c\u041e\u041d","picture_files":[{"img100":"http:\/\/uniqom.ru\/uploads\/items\/thumb\/100_100_0_20.jpg","img210":"http:\/\/uniqom.ru\/uploads\/items\/thumb\/210_210_0_20.jpg","img130":"http:\/\/uniqom.ru\/uploads\/items\/thumb\/130_80_0_20.jpg","big":"http:\/\/uniqom.ru\/uploads\/items\/20.jpg"}]}],"id":2}
         * @param int $id [optional] id товара
         * @param bool $isNotification [optional] set to true if call is notification
         * @return BaseJsonRpcCall (result: array)
         */
        public function GetItem( $id = false, $isNotification = false ) {
            return $this->call( __FUNCTION__, array( 'id' => $id, 'token' => $this->TokenClientUniqom ), $this->getRequestId( $isNotification ) );
        }


        /**
         * Группы каталога Uniqom, <br/><b>Пример вызова:</b> {"jsonrpc":"2.0","id":2,"method":"GetAllGroups","params":[]}
         
 <br /><b>Пример ответа сервера:</b>: {"jsonrpc":"2.0","result":[{"header":"\u0417\u0430\u043f\u0447\u0430\u0441\u0442\u0438","id":"1","parent_id":0},{"header":"\u0425\u0438\u043c\u0438\u044f","id":"2","parent_id":0}],"id":2}
         * @param bool $isNotification [optional] set to true if call is notification
         * @return BaseJsonRpcCall (result: array)
         */
        public function GetAllGroups( $isNotification = false ) {
            return $this->call( __FUNCTION__, array(), $this->getRequestId( $isNotification ) );
        }


        /**
         * Данные по одному бренду ( возвращает описание бренда, описание кодировано base64 )<br />
         <b>Пример вызова:</b>  {"jsonrpc":"2.0","id":2,"method":"GetBrand","params":{"id":2}}<br />
         <b>Пример ответ сервера:</b> {"jsonrpc":"2.0","result":[{"header":"YEC","id":"1","text":"base64_encode текст"}],"id":2}
         * @param int $id [optional] id бренда
         * @param bool $isNotification [optional] set to true if call is notification
         * @return BaseJsonRpcCall (result: array)
         */
        public function GetBrand( $id = false, $isNotification = false ) {
            return $this->call( __FUNCTION__, array( 'id' => $id ), $this->getRequestId( $isNotification ) );
        }


        /**
         * Доступные склады для пользователя<br/><b>Пример вызова:</b> {"jsonrpc":"2.0","id":2,"method":"GetAllStorages","params":{"token":"токен"}}
         <br><b>Пример ответа сервера:</b>: {"jsonrpc":"2.0","result":[{"header":"\u0422\u0445","full_header":"\u0422\u0443\u0445\u0430\u0447\u0435\u0432\u0441\u043a\u043e\u0433\u043e 48\u0410","address":"\u0426\u0420\u0421 \u0412\u043e\u0441\u0442\u043e\u043a","id":"56"},{"header":"\u04212","full_header":"\u0421\u043a\u043b\u0430\u0434 \u21162 (\u0415\u043d\u0438\u0441\u0435\u0439\u0441\u043a\u0430\u044f)","address":"\u0426\u0420\u0421 \u0412\u043e\u0441\u0442\u043e\u043a","id":"74"},{"header":"\u0426\u0420\u0421","full_header":"\u0426\u0420\u0421 \u0412\u043e\u0441\u0442\u043e\u043a","id":"76"},{"header":"\u0426\u0420\u0421","address":"\u0426\u0420\u0421 \u0412\u043e\u0441\u0442\u043e\u043a","id":"76"}],"id":2}
         * @param string $token токен
         * @param bool $isNotification [optional] set to true if call is notification
         * @return BaseJsonRpcCall (result: array)
         */
        public function GetAllStorages( $token, $isNotification = false ) {
            return $this->call( __FUNCTION__, array( 'token' => $token ), $this->getRequestId( $isNotification ) );
        }


        /**
         * Все бренды из каталога Uniqom, 
         <br/><b>Пример вызова:</b> {"jsonrpc":"2.0","id":3,"method":"GetAllBrands","params":[]}
         <br/><b>Пример ответ сервера:</b> {"jsonrpc":"2.0","result":[{"header":"YEC","id":"1"},{"header":"OSK","id":"2"}],"id":2}
         * @param bool $isNotification [optional] set to true if call is notification
         * @return BaseJsonRpcCall (result: array)
         */
        public function GetAllBrands( $isNotification = false ) {
            return $this->call( __FUNCTION__, array(), $this->getRequestId( $isNotification ) );
        }
    }
?>