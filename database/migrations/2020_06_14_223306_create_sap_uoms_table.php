<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSapUomsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sap_uoms', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('nav_item')->nullable();
			$table->string('sap_item')->nullable();
			$table->string('sap_uom')->nullable();

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sap_uoms');
	}

}
