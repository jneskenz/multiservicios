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
        Schema::create('grupo_empresa_propietario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_empresa_id')->constrained('grupo_empresas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Nivel de propiedad
            $table->enum('tipo', ['principal', 'asociado'])->default('principal');
            $table->decimal('porcentaje_participacion', 5, 2)->default(0.00);
            
            // Permisos específicos
            $table->boolean('puede_modificar_plan')->default(false);
            $table->boolean('puede_crear_empresas')->default(true);
            $table->boolean('puede_asignar_usuarios')->default(true);
            
            $table->timestamp('fecha_desde')->useCurrent();
            $table->timestamp('fecha_hasta')->nullable();
            $table->boolean('activo')->default(true);
            
            $table->timestamps();
            
            // Índices
            $table->unique(['grupo_empresa_id', 'user_id']);
            $table->index(['user_id', 'activo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_empresa_propietario');
        
    }
};
