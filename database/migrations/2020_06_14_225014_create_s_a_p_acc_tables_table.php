<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSAPAccTablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sap_acc_table', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('sap_sku')->nullable();
			$table->string('sap_color')->nullable();
			$table->string('sap_item')->nullable();
			$table->string('sap_size')->nullable();

			$table->string('nav_item')->nullable();
			$table->string('nav_variant')->nullable();
			$table->string('nav_desc1')->nullable();
			$table->string('nav_desc2')->nullable();
			$table->string('nav_color')->nullable();
			$table->string('nav_size')->nullable();

			$table->string('not_hu')->nullable();

			$table->string('sap_uom')->nullable();

			$table->bigInteger('barcode')->nullable();
			$table->string('full_barcode')->nullable();

			$table->string('location')->nullable();
			$table->integer('qty');

			$table->string('printer');
			$table->string('printed');

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
		Schema::drop('sap_acc_table');
	}

}
