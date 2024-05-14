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
	public function index()
	{
		$this->load->helpers('google_sheets');

		$google_sheet_helper = new Google_sheets_helper();

		$url = 'https://docs.google.com/spreadsheets/u/2/d/e/2PACX-1vRDJuFnQs2MpjQeGoM6ccPu4TXEL1JuBmPVVJUrUWIBpyp-GVAZj_isSIOQZQEaXe1HtPdNuMqXtPde/pubhtml?gid=0&single=true';
		$google_sheet_helper->load_published_sheet($url);
		$data['table'] =  $google_sheet_helper->get_loaded_data();

		$this->load->view('sheet', $data);
	}

	public function sheet_filter($param)
	{
		$this->load->helpers('google_sheets');

		$google_sheet_helper = new Google_sheets_helper();

		$url = 'https://docs.google.com/spreadsheets/u/2/d/e/2PACX-1vRDJuFnQs2MpjQeGoM6ccPu4TXEL1JuBmPVVJUrUWIBpyp-GVAZj_isSIOQZQEaXe1HtPdNuMqXtPde/pubhtml?gid=0&single=true';
		$google_sheet_helper->load_published_sheet($url);
		$data['table'] = $google_sheet_helper->filter(urldecode($param));

		$this->load->view('sheet', $data);
	}
}
