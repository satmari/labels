<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePLLabelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pl_labels', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('inbound');
			$table->string('vendor')->nullable();

			$table->string('style')->nullable();
			$table->string('color')->nullable();
			$table->string('size')->nullable();
			$table->string('desc')->nullable();
			$table->string('variant')->nullable();

			$table->string('hu')->nullable();
			$table->decimal('qty')->nullable();

			$table->string('uom')->nullable();
			$table->string('batch')->nullable();

			$table->string('file')->nullable();
			
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
		Schema::drop('pl_labels');
	}

}
