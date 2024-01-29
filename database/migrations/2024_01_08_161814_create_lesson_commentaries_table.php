<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Lesson;
use App\Models\User;



return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_commentaries', function (Blueprint $table) {
            $table->id();
            $table-> foreignIdFor(Lesson::class) ->constrained()->cascadeOnDelete()->cascadeOnUpdate()->nullable();
            $table-> foreignIdFor(User::class) ->constrained()->cascadeOnDelete()->cascadeOnUpdate()-> nullable;
            $table-> string('commentario', 1000) -> nullable();
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
        Schema::dropIfExists('lesson_commentaries');
    }
};
