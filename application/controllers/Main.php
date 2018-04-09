<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// guzzle
use \GuzzleHttp\Client;
class Main extends CI_Controller {
	public function index()
	{

        require "config.php";
        $url = 'http://apis.data.go.kr/B552061/trafficAccidentDeath/getRestTrafficAccidentDeath?servicekey='.$serviceKey.'&searchYear=2016&siDo=1100';

        $client = new Client();
        $res = $client->request('GET', $url);
        $traffic_reault = $res->getBody();
        $traffic_reault = json_decode($traffic_reault, true);

        $attr = Array(
            'naver_client_id'   => 'd4StaCfeJPCevZ9VmXCc',
            'traffic_reault'    => $traffic_reault
        );
        $this->load->view('/main/main_view', $attr);
    }
}