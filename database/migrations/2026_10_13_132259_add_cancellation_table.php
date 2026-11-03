<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('cancellation_reason')->nullable();

            $table->timestamp('cancellation_requested_at')
                ->nullable();

            $table->string('cancellation_status', 20)
                ->nullable();

            $table->timestamp('cancellation_reviewed_at')
                ->nullable();

            $table->foreignId('cancellation_reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('cancellation_admin_note')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cancellation_reviewed_by');

            $table->dropColumn([
                'cancellation_reason',
                'cancellation_requested_at',
                'cancellation_status',
                'cancellation_reviewed_at',
                'cancellation_admin_note',
            ]);
        });
    }
};
