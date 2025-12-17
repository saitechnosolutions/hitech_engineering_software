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
        $tables = [
            "categories",
            "customers",
            "employees",
            "l_r_documents",
            "products",
            "payment_details",
            "permissions",
            "production_histories",
            "quotations",
            "production_stages",
            "product_components",
            "quotation_batches",
            "quotation_production_stages",
            "quotation_products",
            "roles",
            "tasks",
            "users"
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
