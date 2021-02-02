<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialIdentitiesTable extends Migration
{
    public function up(): void
    {
        Schema::create('social_identities', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id');
            $table->string('provider_name')->nullable();
            $table->string('provider_id')->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_identities');
    }
}
