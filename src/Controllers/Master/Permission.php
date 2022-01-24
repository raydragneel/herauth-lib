<?php

namespace Raydragneel\HerauthLib\Controllers\Master;

use CodeIgniter\Exceptions\PageNotFoundException;
use Raydragneel\HerauthLib\Models\PermissionModel;

class Permission extends BaseController
{
    protected $modelName = PermissionModel::class;

    public function index()
    {
        $data = [
            'page_title' => lang("Web.master.permission"),
            'url_datatable' => herauth_web_url($this->root_view . "permission/datatable"),
            'url_add' => herauth_base_locale_url($this->root_view . "permission/add"),
            'url_edit' => herauth_base_locale_url($this->root_view . "permission/edit/"),
            'url_delete' => herauth_web_url($this->root_view . "permission/delete/"),
            'url_restore' => herauth_web_url($this->root_view . "permission/restore/"),
        ];
        return $this->view("permission/index", $data);
    }

    public function list()
    {
        return view($this->root_view . "permission/table", [
            '_json' => $this->getDataRequest(),
            'url_delete' => herauth_web_url($this->root_view . "permission/delete/"),
            'url_restore' => herauth_web_url($this->root_view . "permission/restore/"),
            'datas' => $this->model->orderBy('nama', 'asc')->withDeleted(true)->findAll()
        ]);
    }

    public function add()
    {
        $data = [
            'page_title' => lang("Web.add")." ".lang("Web.master.permission"),
            'url_add' => herauth_web_url($this->root_view . "permission/add"),
        ];
        return $this->view("permission/add", $data);
    }
    public function edit($id = null)
    {
        $permission = $this->model->withDeleted(true)->find($id);
        if (!$permission) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Web.edit")." ".lang("Web.master.permission")." " . $permission->nama,
            'permission' => $permission,
            'url_edit' => herauth_web_url($this->root_view . "permission/edit/".$id),
        ];
        return $this->view("permission/edit", $data);
    }

}
