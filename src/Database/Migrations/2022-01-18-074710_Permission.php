<?php

namespace Raydragneel\HerauthLib\Database\Migrations;

use CodeIgniter\Database\Migration;

class Permission extends Migration
{
    public function up()
	{
		$this->forge->addField([
			'id'          => [
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'nama'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
			],
			'deskripsi'       => [
				'type'           => 'TEXT',
				'null'			=> true
			],
			'must_login'       => [
				'type'           => 'VARCHAR',
				'constraint' => 1,
				'default' => 1
			],
			'created_at'       => ['type' => 'datetime', 'null' => true],
			'updated_at'       => ['type' => 'datetime', 'null' => true],
			'deleted_at'       => ['type' => 'datetime', 'null' => true],
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('herauth_permission');
	}

	public function down()
	{
		$this->forge->dropTable('herauth_permission');
	}
}
