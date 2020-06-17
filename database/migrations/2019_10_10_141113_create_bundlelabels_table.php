<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBundlelabelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bundlelabels', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('bbcode')->nullable();
			$table->string('bb')->nullable();
			$table->string('po')->nullable();
			$table->string('bb_3')->nullable();

			$table->string('style');
			$table->string('color');
			$table->string('size_ita');
			
			$table->integer('bundle');

			$table->string('printer_name');
			$table->tinyInteger('printed');

			$table->integer('labels_per_bundle')->nullable(); //added later
			$table->string('bagno')->nullable();  //added later

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
		Schema::drop('bundlelabels');
	}

}
