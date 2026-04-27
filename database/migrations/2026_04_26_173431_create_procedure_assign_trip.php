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
            p_return TIMESTAMP,
            p_km_departure FLOAT,
            p_km_return FLOAT,
            p_observations TEXT,
            p_status TEXT
        )
        LANGUAGE plpgsql
        AS $$
        BEGIN

            IF NOT EXISTS (
                SELECT 1 FROM vehicles
                WHERE id = p_vehicle_id
                AND status = \'available\'
            ) THEN
                RAISE EXCEPTION \'Vehicle is not available or does not exist\';
            END IF;

            IF p_departure >= p_return THEN
                RAISE EXCEPTION \'Invalid time range: departure must be before return\';
            END IF;

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

            INSERT INTO trips (
                user_id,
                vehicle_id,
                route_id,
                departure_time,
                return_time,
                km_departure,
                km_return,
                observations,
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
                p_km_departure,
                p_km_return,
                p_observations,
                p_status,
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