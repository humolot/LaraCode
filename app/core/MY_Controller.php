<?php
/**
 * Created by PhpStorm.
 * User: aka
 * Date: 07/03/18
 * Time: 2:18
 */

use Philo\Blade\Blade;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Validation\DatabasePresenceVerifier;

class MY_Controller extends CI_Controller {

    // codeigniter instance
    protected $instance;

    // attribute for view
    protected $blade;
    protected $data;

    // attribute for validation
    protected $validator;

    public function __construct() {
        parent::__construct();
        // set codeigniter instance
        $this->instance =& get_instance();

        // set blade stuff
        $this->blade = new Blade(VIEWPATH, APPPATH . 'cache');
        $this->data = [];

        // Create a new FileLoader instance specifying the translation path
        $loader = new FileLoader(new Filesystem, 'lang');
        $trans = new Translator($loader, 'en');
        $this->validator = new Factory($trans);
        $this->validator->setPresenceVerifier($this->getPresenceVerifier());
    }

    protected function view($view, $data = [], $return = false){

        // check if there is validation error
        if($this->session->flashdata('errors')) {
            $data['old'] = $this->session->flashdata('old');
            $data['errors'] = $this->session->flashdata('errors');
        } else {
            $data['old'] = [];
            $validation = $this->validator->make([], []);
            $data['errors'] = $validation->errors();
        }

        $this->data = array_merge($this->data, $data);
        $blview = $this->blade->view()->make($view, $this->data)->render();
        if(! $return )
            return print( $blview );
        return $blview;
    }

    protected function validate($request = [], $rule = []) {
        $validation = $this->validator->make($request, $rule);

        if($validation->fails()) {
            $this->session->set_flashdata('errors', $validation->errors());
            $this->session->set_flashdata('old', $request);
            redirect($this->agent->referrer(), 'refresh');
        }
    }

    protected function getPresenceVerifier() {
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => $this->instance->db->hostname,
            'database'  => $this->instance->db->database,
            'username'  => $this->instance->db->username,
            'password'  => $this->instance->db->password,
            'charset'   => $this->instance->db->char_set,
            'collation' => $this->instance->db->dbcollat,
            'prefix'    => $this->instance->db->dbprefix
        ]);
        return new DatabasePresenceVerifier($capsule->getDatabaseManager());
    }

    protected function do_upload($field, $path, $type = '', $encrypt = false, $inject_config = []) {

        $config['upload_path'] = $path;
        $config['encrypt_name'] = $encrypt;

        switch ($type) {
            case 'image':
                $config['allowed_types'] = 'jpg|jpeg|png';
                break;
            case 'document':
                $config['allowed_types'] = 'docx|pdf';
                break;
            case 'video':
                $config['allowed_types'] = '3gp|mp4|mkv';
                break;
            case 'music':
                $config['allowed_types'] = 'mp3';
        }

        foreach ($inject_config as $key => $value) {
            $config[$key] = $value;
        }

        $this->load->library('upload', $config);
        $uploaded_filename = "";

        if (!$this->upload->do_upload($field)) {
            $validation = $this->validator->make([], []);
            $validation->errors()->add($field, strip_tags($this->upload->display_errors()));

            $this->session->set_flashdata('errors', $validation->errors());
            $this->session->set_flashdata('old', $this->input->post());
            redirect($this->agent->referrer(), 'refresh');
        } else {
            $upload_data = $this->upload->data();
            $uploaded_filename = $upload_data['file_name'];
        }

        return $uploaded_filename;
    }
}