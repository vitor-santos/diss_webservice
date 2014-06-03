<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Api extends REST_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('webmodel');
	}
	//Retorna uma promoção dado o id
	function promo_get()
    {
        if(!$this->get('id_promo'))
        {
        	$this->response(NULL, 400);
        }

		$promo=$this->webmodel->getPromoById($this->get('id_promo'));        
    	
        if(is_null($promo))
        {
			$this->response(array('error' => 'User could not be found'), 404);            
        }
        else
        {
          $this->response($promo, 200); // 200 being the HTTP response code  
        }
    }
	
	//Retorna a informação resumida de todas as promoções
	function promos_get()
    {
        $promos=$this->webmodel->getAllPromos();        
    	
        if(is_null($promos))
        {
			$this->response(array('error' => 'User could not be found'), 404);            
        }
        else
        {
			$result=array(
			'Code'=>200,
			'Promos'=>$promos
			);
		
          $this->response($result, 200); // 200 being the HTTP response code  
        }
    }
	
	function highlightedpromos_get()
    {
        $promos=$this->webmodel->getPromotedPromos();        
    	
        if(is_null($promos)||empty($promos))
        {
			$this->response(array('error' => 'There are no highlighted promos'), 404);            
        }
        else
        {
          $this->response($promos, 200); // 200 being the HTTP response code  
        }
    }
	
	function entities_get()
	{
		$en=$this->webmodel->getAllEntities();        
    	
        if(is_null($en))
        {
			$this->response(array('error' => 'There are no entities'), 404);            
        }
        else
        {
			$result=array(
			'Code'=>200,
			'Entities'=>$en
			);
		
          $this->response($result, 200); // 200 being the HTTP response code  
        }
	}
	
	function entity_get()
    {
        if(!$this->get('id_entidade'))
        {
        	$this->response(NULL, 400);
        }

		$entity=$this->webmodel->getEntityById($this->get('id_entidade'));        
    	
        if(is_null($entity))
        {
			$this->response(array('error' => 'User could not be found'), 404);            
        }
        else
        {
          $this->response($entity, 200); // 200 being the HTTP response code  
        }
    }
    
    function userpoints_get()
    {
		if(!$this->get('email'))
        {
        	$this->response(NULL, 400);
        }
		
        $pontos=$this->webmodel->getPointsByUser($this->get('email'));
		
		if(is_null($pontos))
        { 
			//Algo correu mal, nunca devia entrar aqui.
			$this->response(array('error' => 'Critical error'), 404); 
		}
		else
		{	
			$result=array(
				"Code"=>200,
				"Pontos"=>$pontos
				);
			
			$this->response($result, 200); // 200 being the HTTP response code
		}
    }
    
    function updatepoints_get()
    {
		if(!$this->get('email')||!$this->get('pontos'))
        {
        	$data=array('Code'=>300,
						'Error'=>'Missing parameters');
        	$this->response($data, 300);			
        }
		
		$pontos=$this->webmodel->getPointsByUser($this->get('email'));
		
    	$result=$this->webmodel->setPointsByUser( $this->get('email'),$pontos+$this->get('pontos'));
		
		if($result)
		{
			$data=array('Code'=>200,
						'Success'=>'Points updated successfully');
        	$this->response($data, 200);
		}
		else
		{
			//Algo correu mal, nunca devia entrar aqui.
			$this->response(array('Error' => 'Critical error','Code'=>404), 404); 			
		}
		
    }	
    
    function voucher_get()
    {
        if(!$this->get('id_voucher'))
        {
        	$this->response(NULL, 400);
        }
		
		$voucher=$this->webmodel->getVoucherById($this->get('id_voucher'));
		
        if(!is_null($voucher))
        {
			$data=array(
			'Voucher'=>$voucher,
			'Code'=>200);
            $this->response($data, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('Error' => 'Couldn\'t find the wanted voucher!','Code'=>404), 404);
        }
    }
	
	function uservouchers_get()
    {
		if(!$this->get('email'))
        {
        	$this->response(NULL, 400);
        }
		
        $vouchers=$this->webmodel->getVouchersByUser($this->get('email'));        
    	
        if(is_null($vouchers))
        {
			$this->response(array('error' => 'User could not be found or doesn\'t have any vouchers','Code'=>404), 404);            
        }
        else
        {
			$result=array(
			'Code'=>200,
			'Vouchers'=>$vouchers
			);
			$this->response($result, 200); // 200 being the HTTP response code  
        }
    }
	
	function userhistory_get()
	{
		if(!$this->get('id_utilizador'))
        {
        	$this->response(NULL, 400);
        }
		
		$vouchers=$this->webmodel->getHistory($this->get('id_utilizador'));        
    	
        if(is_null($vouchers))
        {
			$this->response(array('error' => 'User could not be found or doesn\'t have any vouchers'), 404);            
        }
        else
        {
          $this->response($vouchers, 200); // 200 being the HTTP response code  
        }
	}
	
	function promosbycat_get()
	{	
		if(!$this->get('categoria'))
        {
        	$this->response(NULL, 400);
        }
		
		$promos=$this->webmodel->getPromosByCat($this->get('categoria'));        
    	
        if(is_null($promos))
        {
			$this->response(array('error' => 'There isn\'t any promo in this category'), 404);            
        }
        else
        {
          $this->response($promos, 200); // 200 being the HTTP response code  
        }
		
	}
	
	function buyvoucher_get()
	{
		//substr(md5(rand()), 0, 7);
		if(!$this->get('email')||!$this->get('id_promo')||!$this->get('pontos'))
        {
			$data=array('Code'=>300,
						'Error'=>'Missing parameters');
        	$this->response($data, 300);
        }
		
		if($this->get('pontos')>$this->webmodel->getPointsByUser($this->get('email')))
		{
			$data=array('Code'=>301,
						'Error'=>'Not enough points');
        	$this->response($data, 301);
		}
		
		$dataV=array(
			'id_promo'=>$this->get('id_promo'),
			'email'=>$this->get('email'),
			'chave_validacao'=>substr(md5(rand()), 0, 10)			
		);
	
		$this->webmodel->buyVoucher($dataV);
		$pontos=$this->webmodel->getPointsByUser($this->get('email'))-$this->get('pontos');
		$this->webmodel->setPointsByUser($this->get('email'),$pontos);
		
		$data=array('Code'=>200,
						'Success'=>'Voucher bought');
        $this->response($data, 200);			
	}	

	public function send_post()
	{
		var_dump($this->request->body);
	}


	public function send_put()
	{
		var_dump($this->put('foo'));
	}
}