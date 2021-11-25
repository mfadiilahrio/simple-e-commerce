<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_bankaccount extends CI_Model {

	function getBankAccounts($where) {
		$this->db->select('
			bank_accounts.*,
			banks.name');
		$this->db->join('banks', 'banks.id = bank_accounts.bank_id', 'left');

		$datas = $this->db->get_where('bank_accounts', $where)->result();

		foreach ($datas as $data) {
			$data->method_name = ($data->account_number != null) ? $data->name.' - '.$data->account_number : $data->name;
		}

		return $datas;
	}

}