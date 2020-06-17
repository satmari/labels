<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePadPrintLabelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pad_print_labels', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('marker');
			$table->string('cliche');
			$table->string('color');
			$table->string('size_relevant');
			$table->string('printer_name');
			$table->integer('printed');

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
		Schema::drop('pad_print_labels');
	}

}
