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
        Schema::create('empresa_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Configuración por relación
            $table->boolean('es_principal')->default(false); // Empresa por defecto del usuario
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_asignacion')->useCurrent();
            $table->timestamp('fecha_revocacion')->nullable();
            
            $table->timestamps();

            // Campo para auditoría: quién asignó esta empresa al usuario
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
                
            // Índices
            $table->index('created_by');
            $table->unique(['empresa_id', 'user_id']);
            $table->index(['user_id', 'es_principal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa_user');
        Schema::table('empresa_user', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropIndex(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};
