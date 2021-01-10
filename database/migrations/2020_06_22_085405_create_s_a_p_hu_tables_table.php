<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSAPHuTablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sap_hu_table', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('nav_hu')->nullable();
			$table->string('nav_item')->nullable();
			$table->string('nav_variant')->nullable();
			$table->string('nav_bagno')->nullable();
			$table->float('nav_qty');
			$table->string('nav_bin')->nullable();
			$table->string('nav_location_barcode')->nullable();
			$table->string('nav_location')->nullable();
			$table->string('nav_desc1')->nullable();
			$table->string('nav_desc2')->nullable();

			$table->string('sap_sku')->nullable();
			$table->string('sap_color')->nullable();
			$table->string('sap_item')->nullable();
			$table->string('sap_size')->nullable();

			$table->string('sap_uom')->nullable();
			$table->string('sap_batch')->nullable();

			$table->string('sap_bagno')->nullable();

			$table->bigInteger('barcode')->nullable();
			$table->string('sap_su')->nullable();

			$table->integer('scaned_count')->nullable();
			
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
		Schema::drop('sap_hu_table');
	}

}
