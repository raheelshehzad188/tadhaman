<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Question extends CI_Controller {

		 function __construct() {
        parent::__construct();
        if(!isset($_SESSION['knet_login']))
		{
			redirect('/login');
		}
        $data= array();
		$this->load->library('template');
		$this->load->model('Common_model');
		$this->Common_model->table = 'question';
		$this->modal = $this->Common_model;
		$this->url = base_url('/admin/question');
		$this->load->model('auth_model');
    }
    //variables
    private $single= 'Question';
    private $multi= 'Questions';
    private $add= 'addques';
    private $all= 'allques';
    private $url= '';
    private $modal= '';
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

	 * @see https://codeigniter.com/user_guide/general/urls.html

	 */

	
	public function save($id = 0)
	{
        $this->form_validation->set_rules('name', $this->single, 'required');
        $this->form_validation->set_rules('quiz', $this->single. ' Quiz Type', 'required');
        $this->form_validation->set_rules('option', $this->single. ' Option', 'required');
        $this->form_validation->set_rules('ans', $this->single. ' Awnser', 'required');
        
        if ($this->form_validation->run() == FALSE)
        {
                $this->session->set_flashdata('error', validation_errors());
        }
        else
        {
        	$user = $this->session->userdata('knet_login');
        	if($id)
        	{
        		$arr = array(
	        		"name" => $this->input->post('name'),
	        		"quiz" => $this->input->post('quiz'),
	        		"option" => $this->input->post('option'),
	        		"ans" => $this->input->post('ans'),
	        		"userID" => $user->UserID,
	        		
	        	);
        		if($this->modal->update($id,$arr))
	        	{
	                $this->session->set_flashdata('success', $this->single.' update successfully!');
	        	}
	        	else
	        	{
	        		$this->session->set_flashdata('error', 'server error');
	        	}
        	}
        	else
        	{
        		$arr = array(
	        		"name" => $this->input->post('name'),
	        		"userID" => $user->UserID,
	        		"quiz" => $this->input->post('quiz'),
	        		"option" => $this->input->post('option'),
	        		"ans" => $this->input->post('ans'),
	        	);
	        	if($mediaID >0)
	        	{
	        		$arr['mediaID'] = $mediaID;
	        	}
	        	if($this->modal->add($arr))
	        	{
	                $this->session->set_flashdata('success', $this->single.' add successfully!');
	        	}
	        	else
	        	{
	        	    $s = $this->db->last_query();
                    die($s);
	        		$this->session->set_flashdata('error', 'server error');
	        	}
	        }
        }
        redirect($_SERVER['HTTP_REFERER']);
	}

	public function delete($id = 0)
	{
		if($id)
		{
			if($this->modal->update($id, array('status'=> 1)))
        	{
                $this->session->set_flashdata('success', $this->single.' delete successfully!');
        	}
        	else
        	{
        		$this->session->set_flashdata('error', 'server error');
        	}
		}
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function create($id= 0)
	{
		$data= array();
		$data['url']= $this->url; 

		$data['data']= $this->modal->get_tag(array('status'=> 0));
		$data['assets']= base_url('assets/admin/');
		$breed = array();
		$page = 'Add '.$this->single;
		$breed['Home'] = Base_url('/admin/admin');
		$breed[$page] = '';
		

		$data['page']= $this->single;
		$data['breed']= $breed;
		if($id)
		{
			$data['edit']= $this->modal->getbyid($id);
		}
		$this->template->admin($this->add,$data);
	}

	public function all()
	{
		$data= array();
		$data['url']= $this->url; 
		$data['assets'] = $assets= base_url('/assets').'/';
		$breed = array();
		$breed['Home'] = Base_url('/admin/admin');
		$page = 'Manage '.$this->single;
		$breed[$page] = '';
		$css = array();
		$css[] = $assets.'/app-assets/css/core/menu/menu-types/vertical-menu.css';
		$css[] = $assets.'/app-assets/css/core/colors/palette-gradient.css';
		$css[] = $assets.'/app-assets/css/plugins/file-uploaders/dropzone.css';
		$css[] = $assets.'/app-assets/css/pages/data-list-view.css';
		$js = array();
		$js[] = $assets.'/app-assets/js/scripts/ui/data-list-view.js';


		$data['page']= $page;
		$data['css']= $css;
		$data['js']= $js;
		$data['breed']= $breed;
		$data['data']= $this->modal->get(array('status'=> 0));
		$this->template->admin($this->all,$data);
	}



}

