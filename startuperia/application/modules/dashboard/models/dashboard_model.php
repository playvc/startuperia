<?php

class Dashboard_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function get_credits(){
	  $this->db->select("credits");
	  $this->db->from("user_profiles");
    $this->db->where('user_id', $this->tank_auth->get_user_id());

	  $query = $this->db->get();
	   
	  return $query->result();
	}
	
	function get_stock_value(){
    $this->db->select('SUM(shares * total_value) as stocks_value');
    $this->db->from('stocks');
    $this->db->where('stocks.users_id', $this->tank_auth->get_user_id());
    
    $query = $this->db->get();
    //print $this->db->last_query();
    return $query->result();
	}
	
	function get_startups(){
	  $this->db->select("SUM(id) as startups");
	  $this->db->from("startups");
	  $query = $this->db->get();
	   
	  return $query->result();
	}
	
	function portfolio(){
    $this->db->select('name, symbol, startups.value_per_share as vps, SUM(stocks.shares) as total_shares, sum(total_value) as value');
    $this->db->from('startups');
    $this->db->join('stocks', 'startups.id = stocks.startups_id');
    $this->db->where('stocks.users_id', $this->tank_auth->get_user_id());
    $this->db->group_by('users_id, name');
    
    $query = $this->db->get();
    return $query;
	}
	
	function pending_orders(){
	  $this->db->select('*');
    $this->db->from('startups');
    $this->db->join('orders', 'startups.id = startups_id');
    $this->db->where('status', 'pending');
    $this->db->where('users_id', $this->tank_auth->get_user_id());
    
    $query = $this->db->get();
    return $query;
	}
}