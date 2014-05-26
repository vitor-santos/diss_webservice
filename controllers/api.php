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
          $this->response($promos, 200); // 200 being the HTTP response code  
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
			$this->response(array('email'=>$this->get('email'),'pontos' => $pontos), 200); // 200 being the HTTP response code
		}
    }
    
    function userpoints_post()
    {
		if(!$this->post('email')||!$this->post('pontos'))
        {
        	$this->response(NULL, 400);
        }
		
    	$result=$this->webmodel->setPointsByUser( $this->post('email'),$this->post('pontos') );
		
		if($result)
		{
			 $this->response(array('Result' => 'User points updated'), 200);
		}
		else
		{
			//Algo correu mal, nunca devia entrar aqui.
			$this->response(array('error' => 'Critical error'), 404); 			
		}
		
    }
	
    
    function voucher_get()
    {
        if(!$this->get('id_voucher'))
        {
        	$this->response(NULL, 400);
        }
		
		$voucher=$this->webmodel->getVoucherById($this->get('id_voucher'));
		
        if(is_null($voucher))
        {
            $this->response($voucher, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find the wanted voucher!'), 404);
        }
    }
	
	function uservouchers_get()
    {
		if(!$this->get('id_utilizador'))
        {
        	$this->response(NULL, 400);
        }
		
        $vouchers=$this->webmodel->getVouchersByUser($this->get('id_utilizador'));        
    	
        if(is_null($vouchers))
        {
			$this->response(array('error' => 'User could not be found or doesn\'t have any vouchers'), 404);            
        }
        else
        {
          $this->response($vouchers, 200); // 200 being the HTTP response code  
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
	
	function buyvoucher_put()
	{
		//substr(md5(rand()), 0, 7);
		if(!$this->put('id_utilizador')||!$this->put('id_promo'))
        {
        	$this->response(NULL, 400);
        }
		
		$data=array(
			'id_promo'=>$this->put('id_promo'),
			'id_utilizador'=>$this->put('id_utilizador'),
			'chave_validacao'=>substr(md5(rand()), 0, 10),
		);
	
		$this->webmodel->buyVoucher($data);
		$this->response(array('Success'=>'Voucher bought'), 200); // 200 being the HTTP response code 		
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