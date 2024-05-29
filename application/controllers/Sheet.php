<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sheet extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	protected $sheet, $sheet_delers_url, $sheet_stat_url;
	public function __construct() {
		parent::__construct();
		$this->sheet_dealers_url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vTYO2f8axAPLg2h9P2aBgG8gP-A6oJegH5W7b8E8ngWcy2S3puu2425SYqo3wxsMjao89EhQaZd0-Nb/pubhtml?gid=0&single=true';
		$this->sheet_stat_url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vTYO2f8axAPLg2h9P2aBgG8gP-A6oJegH5W7b8E8ngWcy2S3puu2425SYqo3wxsMjao89EhQaZd0-Nb/pubhtml?gid=99904499&single=true';

		$this->load->helpers('google_sheets');
		$this->sheet = new Google_sheets_helper();
	}

	public function index()
	{
		$this->sheet->load_published_sheet($this->sheet_dealers_url);
		$data['table'] =  $this->sheet->get_loaded_data();

		$this->load->view('sheet', $data);
	}

	public function sheet_filter($param)
	{
		$this->sheet->load_published_sheet($this->sheet_dealers_url);
		$data['table'] = $this->sheet->filter(urldecode($param));

		$this->load->view('sheet', $data);
	}

	public function statistic() {

	}
}
