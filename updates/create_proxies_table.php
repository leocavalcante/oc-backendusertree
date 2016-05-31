<?php namespace LeoCavalcante\BackendUserTree\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateProxiesTable extends Migration
{
    private $table = 'leocavalcante_backendusertree_proxies';

    public function up()
    {
        Schema::create($this->table, function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();

            $table->foreign('parent_id')
                  ->references('id')
                  ->on($this->table)
                  ->onDelete('set null');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('backend_users')
                  ->onDelete('cascade');


            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
