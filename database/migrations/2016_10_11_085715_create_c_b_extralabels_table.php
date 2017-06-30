<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCBExtralabelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cb_extralabels', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('po')->nullable();
			$table->string('bb_3')->nullable();

			$table->string('bagno')->nullable();
			$table->string('marker')->nullable();

			$table->string('style');
			$table->string('color');
			$table->string('color_desc');

			$table->string('size_ita');
			$table->string('size_eng');
			$table->string('size_spa');
			$table->string('size_eur');
			$table->string('size_usa');

			$table->integer('qty_to_print');

			$table->integer('bb_qty');
			$table->integer('physical_qty')->nullable();
			$table->integer('no_of_box')->nullable();

			$table->tinyInteger('extrabb')->nullable();
			$table->tinyInteger('groupextrabb')->nullable();
			$table->tinyInteger('readybb')->nullable();

			$table->string('date')->nullable();
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
		Schema::drop('cb_extralabels');
	}

}
