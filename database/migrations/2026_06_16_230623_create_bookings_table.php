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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('booking_ref')->unique();
            $table->foreignId('event_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('venue_package_id')->constrained()->cascadeOnDelete();
            $table->json('package_add_ons')->nullable();
            $table->integer('walk_in_guests')->default(0);
            $table->foreignId('payment_option_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('payment_transaction_ref')->nullable();
            $table->string('payment_account_number')->nullable();
            $table->string('guest_first_name');
            $table->string('guest_last_name');
            $table->string('guest_email');
            $table->string('guest_phone');
            $table->integer('guest_count');
            $table->text('guest_request_notes')->nullable();
            $table->string('time_slot');
            $table->decimal('total_payment', 10, 2);
            $table->date('date');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
