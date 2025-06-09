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
      Schema::create('academia_unidade_planos', function (Blueprint $table) {
         $table->id();
         $table->foreignId('academia_unidade_id')->constrained('academia_unidades')->onDelete('cascade');
         $table->foreignId('planos_id')->constrained('planos')->onDelete('cascade');
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('academia_unidade_planos');
   }
};
