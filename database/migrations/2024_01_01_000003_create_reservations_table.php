<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parking_spot_id')->constrained()->onDelete('cascade');
            $table->date('datum');
            $table->time('start_tijd');
            $table->time('eind_tijd');
            $table->string('voertuig')->default('Auto'); // Auto, Motor, Fiets
            $table->string('kenteken')->nullable();
            $table->decimal('totaal_prijs', 8, 2)->default(0);
            $table->boolean('betaald')->default(false);
            $table->enum('betaal_methode', ['ideal', 'paypal', 'tikkie', 'maestro'])->nullable();
            $table->enum('status', ['actief', 'geannuleerd', 'voltooid'])->default('actief');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('reservations');
    }
};
