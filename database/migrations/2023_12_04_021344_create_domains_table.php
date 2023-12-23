<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->string('hostname');
            $table->string('tld');
            $table->text('flair'); // Not indexable, it will be a base64 string that represents the image
            $table->integer('score');
            $table->boolean('verified');
            $table->timestamp('collected')->useCurrent();
            $table->jsonb('subdomains')->nullable();
            $table->timestamps();

            $table->unique(['team_id', 'hostname', 'tld']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
