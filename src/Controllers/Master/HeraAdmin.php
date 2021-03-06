<?php

namespace Raydragneel\HerauthLib\Controllers\Master;

use CodeIgniter\Exceptions\PageNotFoundException;
use Raydragneel\HerauthLib\Models\AdminModel;

class HeraAdmin extends BaseController
{
    protected $modelName = AdminModel::class;

    public function index()
    {
        herauth_grant('admin.view_index','page');
        $data = [
            'page_title' => lang("Web.master.admin"),
            'url_datatable' => herauth_web_url($this->root_view . "admin/datatable"),
            'url_add' => herauth_base_locale_url($this->root_view . "admin/add"),
            'url_edit' => herauth_base_locale_url($this->root_view . "admin/edit/"),
            'url_delete' => herauth_web_url($this->root_view . "admin/delete/"),
            'url_restore' => herauth_web_url($this->root_view . "admin/restore/"),
            'url_group' => herauth_base_locale_url($this->root_view . "admin/group/"),
        ];
        return $this->view("admin/index", $data);
    }

    public function add()
    {
        herauth_grant('admin.view_add','page');
        $data = [
            'page_title' => lang("Web.add")." ".lang("Web.master.admin"),
            'url_add' => herauth_web_url($this->root_view . "admin/add"),
        ];
        return $this->view("admin/add", $data);
    }
    public function edit($id = null)
    {
        herauth_grant('admin.view_edit','page');
        $admin = $this->model->withDeleted(true)->find($id);
        if (!$admin) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Web.edit")." ".lang("Web.master.admin")." " . $admin->nama,
            'admin' => $admin,
            'url_edit' => herauth_web_url($this->root_view . "admin/edit/".$id),
        ];
        return $this->view("admin/edit", $data);
    }
    public function group($id = null)
    {
        herauth_grant('admin.view_group','page');
        $admin = $this->model->withDeleted(true)->find($id);
        if (!$admin) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Web.master.group")." ".lang("Web.master.admin")." " . $admin->nama,
            'admin' => $admin,
            'url_save' => herauth_web_url($this->root_view . "admin/save_group/".$id),
            'url_groups' => herauth_web_url($this->root_view . "group"),
            'url_admin_groups' => herauth_web_url($this->root_view . "admin/groups"),
        ];
        return $this->view("admin/group", $data);
    }

}
