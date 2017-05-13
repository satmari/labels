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

			$table->string('bbcode')->nullable();
			$table->string('bb')->nullable();

			$table->string('style');
			$table->string('color');
			$table->string('color_desc');
			
			$table->string('size_ita');
			$table->string('size_eng');
			$table->string('size_spa');
			$table->string('size_eur');
			$table->string('size_usa');

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


/*
USE [finalaudit]
GO


SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[cartiglio](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[Cod_Bar] [nvarchar](255) NOT NULL,
	[Cod_Art_CZ] [nvarchar](255) NOT NULL,
	[Cod_Col_CZ] [nvarchar](255) NOT NULL,
	[Tgl_ITA] [nvarchar](255) NOT NULL,
	[Tgl_ENG] [nvarchar](255) NOT NULL,
	[Tgl_SPA] [nvarchar](255) NOT NULL,
	[Tgl_EUR] [nvarchar](255) NOT NULL,
	[Tgl_USA] [nvarchar](255) NOT NULL,
	[Descr_Col_CZ] [nvarchar](255) NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
*/
