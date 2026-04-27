<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('
            CREATE OR REPLACE PROCEDURE assign_trip(
                p_user_id INT,
                p_vehicle_id INT,
                p_route_id INT,
                p_departure TIMESTAMP,
                p_return TIMESTAMP
            )
            LANGUAGE plpgsql
            AS $$
            BEGIN

                -- Validate vehicle status
                IF NOT EXISTS (
                    SELECT 1 FROM vehicles
                    WHERE id = p_vehicle_id
                    AND status = \'active\'
                ) THEN
                    RAISE EXCEPTION \'Vehicle is not available\';
                END IF;

                -- Validate overlap
                IF EXISTS (
                    SELECT 1 FROM trips
                    WHERE vehicle_id = p_vehicle_id
                    AND NOT (
                        p_return <= departure_time
                        OR
                        p_departure >= return_time
                    )
                    AND deleted_at IS NULL
                ) THEN
                    RAISE EXCEPTION \'Vehicle already assigned in this time range\';
                END IF;

                -- Insert trip
                INSERT INTO trips (
                    user_id,
                    vehicle_id,
                    route_id,
                    departure_time,
                    return_time,
                    km_departure,
                    status,
                    created_at,
                    updated_at
                )
                VALUES (
                    p_user_id,
                    p_vehicle_id,
                    p_route_id,
                    p_departure,
                    p_return,
                    0,
                    \'pending\',
                    NOW(),
                    NOW()
                );

            END;
            $$;
        ');
    }

    public function down(): void
    {
        DB::unprepared('
            DROP PROCEDURE assign_trip;
        ');
    }
};