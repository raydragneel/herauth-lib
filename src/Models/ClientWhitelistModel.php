<?php

namespace Raydragneel\HerauthLib\Models;

use Raydragneel\HerauthLib\Entities\ClientWhitelistEntity;

class ClientWhitelistModel extends BaseModel
{
    protected $table                = 'herauth_client_whitelist';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = ClientWhitelistEntity::class;
    protected $useSoftDeletes        = true;
    protected $protectFields        = true;
    protected $allowedFields        = ['client_id', 'whitelist_name','whitelist_type', 'whitelist_key', 'deleted_at'];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = [];
    protected $afterInsert          = [];
    protected $beforeUpdate         = [];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];
}