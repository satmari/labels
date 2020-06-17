<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePadPrintConfsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pad_print_confs', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('style');
			$table->string('color');
			$table->string('cliche');
			$table->string('cliche_color');
			$table->string('size_relevant');

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
		Schema::drop('pad_print_confs');
	}

}
