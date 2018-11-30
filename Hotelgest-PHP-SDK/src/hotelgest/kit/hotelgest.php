<?php

class KitHotelgest {

    const HOST = 'https://api.hotelgest.com/v1/'; //:40

    private $user;
    private $pwd;
    private $lcd;
    private $token;
    private $err;
    public $srv;

    /**
     * For each facility you will need to create separate object
     * 
     * @param type $username
     * @param type $password
     * @param type $pkey
     * @param type $pcode = Facility ID, you can find it head panel HotelGest
     */
    function __construct($username, $password, $pkey = false, $pcode = false) {

        $this->srv = self::HOST;
        $this->user = $username;
        $this->pwd = $password;
        $this->pkey = $pkey;

        $this->pcd = (int) $pcode;
        $this->rcd = '';

        // overwrite url server
        $this->srv = ( $_SERVER ["HTTP_HOST"] == 'sandboxapi.hotelgest.com') ? 'http://sandboxapi.hotelgest.com/v1/' : $this->srv;
        $this->get_token();

        $this->err = '';
    }

    /**
     *  inset new reservation
     * 
     * @param date $chechIn: date("Y-m-d")
     * @param date $chechOut: date("Y-m-d")
     * @param type $rooms
     * @return type
     */
    public function set_booking($args) {
        $args["token"] = $this->token;
        return $this->curl_contents_json('reservation/set', $args, 'post');
    }

    public function reloadPush($args) {
        $args["token"] = $this->token;
        return $this->curl_contents_json('reservation/reloadPush', $args, 'POST');
    }

    public function set_payment($args) {
        $args["token"] = $this->token;
        return $this->curl_contents_json('invoice/setPayment', $args, 'POST');
    }

    public function get_property($pcode) {
        $args["token"] = $this->token;
        $args["pcode"] = $pcode;
        return $this->curl_contents_json('property/get', $args, 'POST');
    }

    /**
     *  set item
     * 
     * @param date $chechIn: date("Y-m-d")
     * @param date $chechOut: date("Y-m-d")
     * @param type $rooms
     * @return type
     */
    public function setItembooking($args) {
        $args["token"] = $this->token;
        return $booking_values = $this->curl_contents_json('reservationItem/set', $args, 'POST', 0);
    }

    public function getConfig($pcode) {
        $args["token"] = $this->token;
        $args["pcode"] = $pcode;
        return $this->curl_contents_json('property/getConfig', $args, 'POST');
    }

    public function getProduct($args) {
        $args["token"] = $this->token;
        return $this->curl_contents_json('property/getProduct', $args, 'POST');
    }

    public function isPack($args) {
        $args["token"] = $this->token;
        //var_dump($args);
        $packList = (array) $this->curl_contents_json('price/Pack', $args, 'POST');
        $args["pack_code"] = (string) $args["pack_code"];
        foreach ($packList as $key => $pack):
            if ($key == $args["pack_code"]) {
                return $pack->id;
            }
        endforeach;
        die('error pack');
    }

    public function get_pricePack($args) {
        $args["token"] = $this->token;
        $pack = $this->curl_contents_json('price/pack', $args, 'POST');
        return $pack->{$args["pack_code"]};
    }

    public function request($value, $method = 'post', $default = false) {
        global $app;
        return ( $app->request()->{$method}($value) ) ? $app->request()->{$method}($value) : $default;
    }

    /**
     * connexion methods curl 
     * 
     * @param type $service: name of the url service
     * @param type $curl_post_data
     * @param type $type: 'POST', 'GET'
     * @param type $json
     * @return type
     */
    private function curl_contents_json($service, $curl_post_data, $type = 'POST', $json = true) {

        $curl = curl_init($this->srv . $service);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if ($type == 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }
        curl_close($curl);
        if ($json):
            $decoded = json_decode($curl_response);
            if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
                die('error occured: ' . $decoded->response->errormessage);
            }

            return $decoded;
        else:
            return $curl_response;
        endif;
    }

    /**
     * gets the token needed for every  query
     * 
     * @return type
     */
    private function get_token() {

        $args = array("username" => $this->user,
            "password" => $this->pwd,
            "pkey" => $this->pkey);

        $returnvalues = $this->curl_contents_json('user/getToken', $args);
        $this->token = $returnvalues->token;
        return TRUE;
    }

    function cleanArray($good, $bad) {
        $auxbbdd = array();
        foreach ($good as $aux):
            if (isset($bad[$aux]))
                $auxbbdd[$aux] = $bad[$aux];
        endforeach;

        return $auxbbdd;
    }

    function getWubookIdRoom($good, $bad) {

        return $auxbbdd;
    }

    /**
     *  inset new reservation
     * 
     * @param date $chechIn: date("Y-m-d")
     * @param date $chechOut: date("Y-m-d")
     * @param type $rooms
     * @return type
     */
    public function get_price($args) {
        $booking_values = $this->curl_contents_json('price/room', $args, 'POST');
        return $booking_values;
    }

    public function get_rscode($args) {
        $args["token"] = $this->token;
        return $this->curl_contents_json('reservation/get', $args, 'POST');
    }

    public function set_cancel($args) {
        $args["token"] = $this->token;
        $args["push"] = 1;
        return $this->curl_contents_json('reservation/cancel', $args, 'POST', 0);
    }

    public function changeStatus($args) {
        $args["token"] = $this->token;
        return $this->curl_contents_json('reservation/changeStatus', $args, 'POST');
    }

    public function logFile($args) {
        ob_start();
        var_dump($args);
        $log = ob_get_contents();
        ob_end_clean();
        $log_line = date(DATE_RFC822) . ':' . $log; //json_encode($booking, JSON_UNESCAPED_UNICODE);
        $log_file = 'debug.log';
        $log = fopen($log_file, 'a');
        fwrite($log, $log_line . "\n");
        fclose($log);
    }

}