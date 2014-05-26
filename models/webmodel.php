<?php

class Webmodel extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}
	
	//Devolve informação completa sobre uma promoção
	public function getPromoById($id)
	{
		$query=$this->db->get_where('promo', array('id_promo' => $id));		
		if($query->num_rows()>0)
		{
			foreach($query->result() as $rows)
			{
					$sql = "SELECT nome FROM categoria WHERE id_categoria = (SELECT id_categoria FROM catpromo WHERE id_promo= ?)";
					$query2=$this->db->query($sql, array($rows->id_promo));
					$row = $query2->row_array();
					$cat=$row['nome'];					
				
					$promo = array(
					  'desc_completa'  => $rows->desc_completa,
					  'inicio_promo'  => $rows->inicio_promo,
					  'fim_promo'    => $rows->fim_promo,
					  'valor_voucher'  => $rows->valor_voucher,
					  'no_vouchers'    => $rows->no_vouchers,
					  'destaque'	=>$rows->destaque,
					  'id_promo'	=>$rows->id_promo,
					  'fim_destaque'  => $rows->fim_destaque,
					  'id_entidade'    => $rows->id_entidade,
					  'desc_resumida'  => $rows->desc_resumida,
					  'reserva'    => $rows->reserva,
					  'condicoes'	=>$rows->condicoes,
					  'parceria'=>$rows->parceria,
					  'horario'  => $rows->horario,
					  'poupanca'    => $rows->poupanca,
					  'bilhete'    => $rows->bilhete,
					  'hora_criacao'	=> $rows->hora_criacao,
					  'categoria'    => $cat,
					  'zonas'    => $rows->zonas		  
					);	
			}
		}
		return $promo;
	}
	
	//Devolve informações resumidas de todas as promoções. Gastar menos dados.
	public function getAllPromos()
	{
		$query=$this->db->get('promo');
		
		$data=array();
		
		if($query->num_rows()>0)
		{
			foreach($query->result() as $rows)
			{
				/*$sql = "SELECT nome FROM categoria WHERE id_categoria = (SELECT id_categoria FROM catpromo WHERE id_promo= ?)";
				$query2=$this->db->query($sql, array($rows->id_promo));
				$row = $query2->row_array();
				$cat=$row['nome'];
				
				$promo = array(
					  'inicio_promo'  => $rows->inicio_promo,
					  'fim_promo'    => $rows->fim_promo,
					  'valor_voucher'  => $rows->valor_voucher,
					  'destaque'	=>$rows->destaque,
					  'id_promo'	=>$rows->id_promo,
					  'fim_destaque'  => $rows->fim_destaque,
					  'id_entidade'    => $rows->id_entidade,
					  'desc_resumida'  => $rows->desc_resumida,
					  'categoria'    => $cat
					);	
				*/
				$promo=$this->getPromoById($rows->id_promo);
				array_push($data, $promo);
			}
			return $data;
		}
		else return NULL;
		
	}
	
	public function getPromotedPromos()
	{
		$query=$this->db->get('promo');
		$data=array();
		if($query->num_rows()>0)
		{
			foreach($query->result() as $rows)
			{
				if($rows->destaque==1)
				{
					$promo =$rows->id_promo;	 
					array_push($data, $promo);
				}
			}
			return $data;
		}
		else return NULL;		
	}
	
	public function getEntityById($id)
	{
		$this->db->where("id_entidade",$id);		
		$query=$this->db->get("entidade");
		
		if($query->num_rows()>0)
		{
			foreach($query->result() as $rows)
			{	
				$user = array(
					  'id_entidade'  => $rows->id_entidade,
					  'nome'  => $rows->nome,
					  'email'    => $rows->email,
					  'contacto'  => $rows->contacto,
					  'descricao'    => $rows->descricao,
					  'morada'	=>$rows->morada,
					  'nome_prop'    => $rows->nome_prop,
				);				
				return $user;
			}			
		}
		else return NULL;
	}
	
	public function getPointsByUser($email)
	{
		$this->db->where("email",$email);		
		$query=$this->db->get("userpontos");
		if($query->num_rows()>0)
		{
			foreach($query->result() as $rows)
			{
				$pontos=$rows->pontos;
			}			
		}
		else
		{
			$this->setPointsByUser($email,0);
			$pontos=0;			
		}
		return $pontos;
	}
	
	public function setPointsByUser($email,$points)
	{
		$query=$this->db->get_where('userpontos', array('email' => $email));
		if($query->num_rows()>0)
		{
			$this->db->where('email',$email);
			$this->db->update('userpontos', array('pontos'=>$pontos));
			return true;
		}
		else
		{
			$this->db->insert('userpontos',array('email'=>$email,'pontos'=>$points));
			return true;
		}		
	}
	
	public function getVoucherById($id)
	{
		$this->db->where("id_voucher",$id);		
		$query=$this->db->get("voucher");
		if($query->num_rows()>0)
		{
			foreach($query->result() as $rows)
			{
				$voucher = array(
					  'chave_validacao'  => $rows->chave_validacao,
					  'data_validacao'  => $rows->data_validacao,
					  'estado'    => $rows->estado,
					  'id_promo'  => $rows->id_promo,
					  'id_utilizador'    => $rows->id_utilizador,
					  'id_voucher'	=>$rows->id_voucher					  
				);
				return $voucher;
			}			
		}
		else return NULL;		
	}
	
	//Devolve todos os vouchers.Será necessário?
	public function getVouchers()
	{		
		$query=$this->db->get("voucher");
		if($query->num_rows()>0)
		{
			foreach($query->result() as $rows)
			{
				$voucher = array(
					  'chave_validacao'  => $rows->chave_validacao,
					  'data_validacao'  => $rows->data_validacao,
					  'data_compra'  => $rows->data_validacao,
					  'estado'    => $rows->estado,
					  'id_promo'  => $rows->id_promo,
					  'id_utilizador'    => $rows->id_utilizador,
					  'id_voucher'	=>$rows->id_voucher					  
				);
				array_push($data,$voucher);			
			}
			return $data;
		}
		else return NULL;	
	}
	
	public function getVouchersByUser($id)
	{
		$this->db->where("id_utilizador",$id);	
		$query=$this->db->get("voucher");
		$data=array();
		if($query->num_rows()>0)
		{
			foreach($query->result() as $rows)
			{
				$voucher = array(
					  'chave_validacao'  => $rows->chave_validacao,
					  'data_validacao'  => $rows->data_validacao,
					  'data_compra'  => $rows->data_validacao,
					  'estado'    => $rows->estado,
					  'id_promo'  => $rows->id_promo,
					  'id_utilizador'    => $rows->id_utilizador,
					  'id_voucher'	=>$rows->id_voucher					  
				);
				array_push($data,$voucher);			
			}
			return $data;
		}
		else return NULL;	
	}
	
	public function buyVoucher($data)
	{
		$this->db->insert('voucher',$data); 
	}
	
	public function getHistory($id)
	{
		$this->db->where("estado","validado");	
		$this->db->where("id_utilizador",$id);
		$query=$this->db->get("voucher");
		if($query->num_rows()>0)
		{
			foreach($query->result() as $rows)
			{
				$voucher = array(
					  'chave_validacao'  => $rows->chave_validacao,
					  'data_validacao'  => $rows->data_validacao,
					  'data_compra'  => $rows->data_validacao,
					  'estado'    => $rows->estado,
					  'id_promo'  => $rows->id_promo,
					  'id_utilizador'    => $rows->id_utilizador,
					  'id_voucher'	=>$rows->id_voucher					  
				);
				array_push($data,$voucher);			
			}
			return $data;
		}
		else return NULL;
	}
	
	public function getPromosByCat($category)
	{
		$sql = "SELECT id_categoria FROM categoria WHERE nome= ?";
		$query2=$this->db->query($sql, array($category));
		$row = $query2->row_array();
		$id_cat=$row['id_categoria'];
		
		$data=array();
		$query=$this->db->get_where('catpromo', array('id_categoria' => $id_cat));
		if($query->num_rows()>0)
		{
			foreach($query->result() as $rows)
			{
				$promo=$this->getPromoById($rows->id_promo);
				array_push($data,$promo);	
			}
		}
		else return NULL;		
	}
}