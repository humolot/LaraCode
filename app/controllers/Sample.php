<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\ServiceProvider;
use Ramsey\Uuid\Uuid;
use Encryption\Encryption;
use Encryption\Exception\EncryptionException;

class Sample extends MY_Controller {

    public function index() {
		$this->load->library('validation');
		$uuid = Uuid::uuid4();
		
		echo domain(); 
		echo '<br>';
		echo $uuid;
		
		//$this->load->model('samplemodel');
		//$users = SampleModel::where('id', '>', 1)->get();
		//$users = DB::select('select * from faqs where id = :id', ['id' => 1]);
		//print_r($users);
		
		
		
	}
	
	// Example Datatable AJAX 
	public function ajax() {
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query('select id,title,description from faqs');
		echo $datatables->generate();
	}
}
