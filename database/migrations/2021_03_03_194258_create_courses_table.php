<?php

use App\Enums\CourseStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
	        $table->unsignedBigInteger('user_id');
	        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
	        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
	        $table->string('name_en');
            $table->string('name_ar')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('maximum_seats');
            $table->unsignedInteger('minimum_seats');
            $table->unsignedDouble('cost');
            $table->enum('type', ['Online', 'Offline']);
            $table->enum('status', CourseStatus::all())->default('In Progress');
            $table->string('object_type')->default('course');
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
        Schema::dropIfExists('courses');
    }
}
