<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('
            CREATE OR REPLACE FUNCTION vehicle_available(
                p_vehicle_id INT,
                p_departure TIMESTAMP,
                p_return TIMESTAMP
            )
            RETURNS BOOLEAN
            LANGUAGE plpgsql
            AS $$
            BEGIN
                RETURN NOT EXISTS (
                    SELECT 1 FROM trips
                    WHERE vehicle_id = p_vehicle_id
                    AND NOT (
                        p_return <= departure_time
                        OR
                        p_departure >= return_time
                    )
                    AND deleted_at IS NULL
                );
            END;
            $$;
        ');
    }

    public function down(): void
    {
        DB::unprepared('
            DROP FUNCTION vehicle_available;
        ');
    }
};