<?php

namespace Raydragneel\HerauthLib\Controllers\Api\Master;

use Raydragneel\HerauthLib\Controllers\Api\BaseResourceApi;
use Raydragneel\HerauthLib\Models\ClientModel;
use Raydragneel\HerauthLib\Models\ClientPermissionModel;
use Raydragneel\HerauthLib\Models\ClientWhitelistModel;

class Client extends BaseResourceApi
{
    protected $modelName = ClientModel::class;

    public function datatable()
    {
        $data = $this->getDataRequest();
        $like = [
            'nama' => $data['search']['value'] ?? ''
        ];
        $this->request->message_after = lang("Api.successRetrieveRequest", [lang("Web.master.client")]);
        return $this->respond($this->datatable_get(['withDeleted' => true,'like' => $like]), 200);
    }

    public function add()
    {
        $data = $this->getDataRequest();
        $rules = [
            'nama' => [
                'label'  => lang("Api.validation.master.nama", [lang("Web.master.client")]),
                'rules'  => "required",
                'errors' => []
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $insertData = [
            'nama' => $data['nama']
        ];
        if(!empty($data['expired'])){
            $insertData['expired'] = date("Y-m-d H:i:s",strtotime($data['expired']));
        }
        if(!empty($data['hit_limit'])){
            $insertData['hit_limit'] = $data['hit_limit'];
        }
        if ($this->model->save($insertData)) {
            return $this->respond(["status" => true, "message" => lang("Api.successAddRequest", [lang("Web.master.client")]), "data" => ['redir' => herauth_base_locale_url('master/client')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failAddRequest", [lang("Web.master.client")]), "data" => []], 400);
        }
    }
    public function edit($id = null)
    {
        $client = $this->model->withDeleted(true)->find($id);
        if (!$client) {
            return $this->response->setStatusCode(404)->setJSON(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.client")]), "data" => []]);
        }
        $data = $this->getDataRequest();
        $rules = [
            'nama' => [
                'label'  => lang("Api.validation.master.nama", [lang("Web.master.client")]),
                'rules'  => "required",
                'errors' => []
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $update_data = [
            'nama' => $data['nama'],
            'expired' => null,
            'hit_limit' => null,
            'client_key' => $client->client_key
        ];
        if(!empty($data['expired'])){
            $update_data['expired'] = date("Y-m-d H:i:s",strtotime($data['expired']));
        }
        if(!empty($data['hit_limit'])){
            $update_data['hit_limit'] = $data['hit_limit'];
        }
        if ($this->model->update($id, $update_data)) {
            return $this->respond(["status" => true, "message" => lang("Api.successEditRequest", [lang("Web.master.client")]), "data" => ['redir' => herauth_base_locale_url('master/client')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failEditRequest", [lang("Web.master.client")]), "data" => []], 400);
        }
    }
    public function delete($id = null)
    {
        $data = $this->getDataRequest();
        if (isset($data['purge'])) {
            $client = $this->model->withDeleted(true)->find($id);
        } else {
            $client = $this->model->find($id);
        }
        if ($client) {
            if (isset($data['purge'])) {
                $delete = $this->model->delete($id, true);
            } else {
                $delete = $this->model->delete($id);
            }
            if ($delete) {
                if (isset($data['purge'])) {
                    $message = lang("Api.successPurgeRequest", [lang("Web.master.client")]);
                } else {
                    $message = lang("Api.successDeleteRequest", [lang("Web.master.client")]);
                }
                return $this->respond(["status" => true, "message" => $message, "data" => []], 200);
            } else {
                if (isset($data['purge'])) {
                    $message = lang("Api.failPurgeRequest", [lang("Web.master.client")]);
                } else {
                    $message = lang("Api.failDeleteRequest", [lang("Web.master.client")]);
                }
                return $this->respond(["status" => false, "message" => $message, "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.client")]), "data" => []], 404);
    }
    public function restore($id = null)
    {
        $client = $this->model->withDeleted(true)->find($id);
        if ($client) {
            if ($this->model->restore($id)) {
                return $this->respond(["status" => true, "message" => lang("Api.successRestoreRequest", [lang("Web.master.client")]), "data" => []], 200);
            } else {
                return $this->respond(["status" => false, "message" => lang("Api.failRestoreRequest", [lang("Web.master.client")]), "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.client")]), "data" => []], 404);
    }
    public function regenerate_key($id = null)
    {
        $client = $this->model->withDeleted(true)->find($id);
        if ($client) {
            if ($this->model->regenerate_key($id)) {
                return $this->respond(["status" => true, "message" => lang("Api.successRegenerateKeyRequest", [lang("Web.master.client")]), "data" => []], 200);
            } else {
                return $this->respond(["status" => false, "message" => lang("Api.failRegenerateKeyRequest", [lang("Web.master.client")]), "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.client")]), "data" => []], 404);
    }

    public function permissions($id = null)
    {
        $client = $this->model->withDeleted(true)->find($id);
        if ($client) {
            return $this->respond(["status" => true, "message" => lang("Api.successRetrieveRequest", [lang("Web.master.client")]), "data" => $client->permissions], 200);
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.client")]), "data" => []], 404);
    }

    public function save_permissions($id = null)
    {
        $data = $this->getDataRequest();
        $client = $this->model->withDeleted(true)->find($id);
        if ($client) {
            $client_permission_model = model(ClientPermissionModel::class);
            foreach ($data['permissions'] as $permission) {
                $client_permission = $client_permission_model->where(['client_id' => $client->id, 'permission_id' => $permission['id']])->withDeleted(true)->first();
                if ($client_permission) {
                    if ($permission['checked']) {
                        $client_permission_model->update($client_permission->id, [
                            'deleted_at' => null
                        ]);
                    } else {
                        $client_permission_model->delete($client_permission->id);
                    }
                } else {
                    if ($permission['checked']) {
                        $client_permission_model->save(['client_id' => $client->id, 'permission_id' => $permission['id']]);
                    }
                }
            }
            return $this->respond(["status" => true, "message" => lang("Api.successSaveClientRequest",[lang("Web.master.permission")]), "data" => []], 200);
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.client")]), "data" => []], 404);
    }

    public function save_whitelists($id = null)
    {
        $data = $this->getDataRequest();
        $client = $this->model->withDeleted(true)->find($id);
        if ($client) {
            $client_whitelist_model = model(ClientWhitelistModel::class);
            $client_whitelist_model->where(['client_id' => $id])->delete();
            foreach ($data['web'] as $web) {
                if((int)$web['id'] !== 0){
                    $client_whitelist_model->where(['client_id' => $id,'id' => $web['id']])->set([
                        'whitelist_name' => $web['whitelist_name'],
                        'whitelist_key' => $web['whitelist_key'],
                        'whitelist_type' => 'ip',
                        'deleted_at' => null
                    ])->update();
                }else{
                    $client_whitelist = $client_whitelist_model->where(['client_id' => $id,'whitelist_key' => $web['whitelist_key']])->withDeleted(true)->first();
                    var_dump($client_whitelist);
                    if($client_whitelist){
                        $client_whitelist_model->where(['client_id' => $id,'id' => $client_whitelist->id])->set([
                            'whitelist_name' => $web['whitelist_name'],
                            'whitelist_key' => $web['whitelist_key'],
                            'whitelist_type' => 'ip',
                            'deleted_at' => null,
                        ])->update();
                    }else{
                        $client_whitelist_model->save([
                            'client_id' => $id,
                            'whitelist_name' => $web['whitelist_name'],
                            'whitelist_key' => $web['whitelist_key'],
                            'whitelist_type' => 'ip'
                        ]);
                    }

                }
            }
            foreach ($data['android'] as $android) {
                if((int)$android['id'] !== 0){
                    $client_whitelist_model->where(['client_id' => $id,'id' => $android['id']])->set([
                        'whitelist_name' => $android['whitelist_name'],
                        'whitelist_key' => $android['whitelist_key'],
                        'whitelist_type' => 'android',
                        'deleted_at' => null
                    ])->update();
                }else{
                    $client_whitelist = $client_whitelist_model->where(['client_id' => $id,'whitelist_key' => $android['whitelist_key']])->withDeleted(true)->first();
                    if($client_whitelist){
                        $client_whitelist_model->where(['client_id' => $id,'id' => $client_whitelist->id])->set([
                            'whitelist_name' => $android['whitelist_name'],
                            'whitelist_key' => $android['whitelist_key'],
                            'whitelist_type' => 'android',
                            'deleted_at' => null,
                        ])->update();
                    }else{
                        $client_whitelist_model->save([
                            'client_id' => $id,
                            'whitelist_name' => $android['whitelist_name'],
                            'whitelist_key' => $android['whitelist_key'],
                            'whitelist_type' => 'android'
                        ]);
                    }

                }
            }
            foreach ($data['ios'] as $ios) {
                if((int)$ios['id'] !== 0){
                    $client_whitelist_model->where(['client_id' => $id,'id' => $ios['id']])->set([
                        'whitelist_name' => $ios['whitelist_name'],
                        'whitelist_key' => $ios['whitelist_key'],
                        'whitelist_type' => 'ios',
                        'deleted_at' => null
                    ])->update();
                }else{
                    $client_whitelist = $client_whitelist_model->where(['client_id' => $id,'whitelist_key' => $ios['whitelist_key']])->withDeleted(true)->first();
                    if($client_whitelist){
                        $client_whitelist_model->where(['client_id' => $id,'id' => $client_whitelist->id])->set([
                            'whitelist_name' => $ios['whitelist_name'],
                            'whitelist_key' => $ios['whitelist_key'],
                            'whitelist_type' => 'ios',
                            'deleted_at' => null,
                        ])->update();
                    }else{
                        $client_whitelist_model->save([
                            'client_id' => $id,
                            'whitelist_name' => $ios['whitelist_name'],
                            'whitelist_key' => $ios['whitelist_key'],
                            'whitelist_type' => 'ios'
                        ]);
                    }

                }
            }
            return $this->respond(["status" => true, "message" => lang("Api.successSaveClientRequest",[lang("Web.master.whitelist")]), "data" => []], 200);
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.client")]), "data" => []], 404);
    }
}
