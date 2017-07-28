<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTagsTable
 */
class CreateTagsTable extends Migration
{
    /**
     * Table names.
     *
     * @var string  $table        The main table name for this migration.
     * @var string  $table_pivot  The pivot table name.
     */
    protected $table;
    protected $table_pivot;

    /**
     * Create a new migration instance.
     */
    public function __construct()
    {
        $this->table       = config('lecturize.tags.table',       'tags');
        $this->table_pivot = config('lecturize.tags.table_pivot', 'taggables');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function(Blueprint $table)
		{
		    $table->increments('id');

            $table->string('tag')->nullable()->unique();
            $table->string('slug')->nullable()->unique();

            $table->boolean('suggest')->default(0);

            $table->integer('language_id')
                  ->nullable()
                  ->unsigned()
                  ->default(1)
                  ->references('id')
                  ->on(config('lecturize.languages.table', 'languages'));

            $table->timestamps();
            $table->softDeletes();
		});

        Schema::create($this->table_pivot, function(Blueprint $table)
        {
            $table->integer('tag_id')
                  ->nullable()
                  ->unsigned()
                  ->references('id')
                  ->on($this->table)
                  ->onDelete('cascade');

            $table->nullableMorphs('taggable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table_pivot);
        Schema::dropIfExists($this->table);
    }
}