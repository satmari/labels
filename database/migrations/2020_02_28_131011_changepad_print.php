<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangepadPrint extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		 Schema::table('pad_print_confs', function($table)
		 {
     		// $table->string('size_relevant')->nullable();
		 });
		  
		  Schema::table('pad_print_labels', function($table)
		 {
     		// $table->string('size_relevant')->nullable();
		 });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
