<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// guzzle
use \GuzzleHttp\Client;
class Main extends CI_Controller {
    public function __construct()
    {
        parent::__construct();

        require "config.php";
    }
	public function index()
	{
        $attr = Array(
            'naver_client_id'   => $this->naver_client_id
        );
        $this->load->view('/main/main_view', $attr);
    }

    public function getData() {
        $url = 'http://apis.data.go.kr/B552061/trafficAccidentDeath/getRestTrafficAccidentDeath?servicekey='.$this->serviceKey.'&searchYear=2016&siDo=1100';

        $client = new Client();
        $res = $client->request('GET', $url);
        $traffic_reault = $res->getBody();
    
        $this->output
			->set_content_type('application/json')
			->set_output($traffic_reault);
    }
}