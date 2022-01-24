<?php

namespace Raydragneel\HerauthLib\Controllers\Api\Master;

use Raydragneel\HerauthLib\Controllers\Api\BaseResourceApi;
use Raydragneel\HerauthLib\Models\ClientModel;

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
            'hit_limit' => null
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
}