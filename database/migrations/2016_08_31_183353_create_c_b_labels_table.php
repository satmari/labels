<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCBLabelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cb_labels', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('bbcode')->nullable();
			$table->string('bb')->nullable();

			$table->string('style');
			$table->string('color');
			$table->string('color_desc');
			$table->string('size');

			$table->integer('qty_to_print');

			$table->string('barcode');
			$table->string('barcode_3');

			$table->string('printer_name');
			$table->tinyInteger('printed');

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
		Schema::drop('cb_labels');
	}

}
