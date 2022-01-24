<?php

namespace Raydragneel\HerauthLib\Entities;

use Raydragneel\HerauthLib\Models\UserGroupModel;

class AdminEntity extends AccountEntity
{
	public function __construct(array $data = null)
	{
		parent::__construct($data);
	}
	public $password_view = "";
	protected $datamap = [];
	protected $dates   = [
		'created_at',
		'updated_at',
		'deleted_at',
	];
	protected $casts   = [];

	public function setPassword($pass)
	{
		$this->attributes['password'] = password_hash($pass, PASSWORD_DEFAULT);
		$this->password_view = $pass;
		return $this;
	}

	public function getGroups()
	{
		$user_group_model = model(UserGroupModel::class);
		return $user_group_model->select('id,group_id')->where(['username' => $this->attributes['username']])->findAll();
	}

}
