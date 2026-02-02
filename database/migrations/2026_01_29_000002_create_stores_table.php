<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('code')->unique();
            $table->decimal('total_area_sqft', 12, 2)->default(0);
            $table->text('address')->nullable();
            $table->string('area')->nullable();
            $table->string('thana')->nullable();
            $table->string('district')->nullable();
            $table->string('division')->nullable();
            $table->string('postal_code')->nullable();
            $table
                ->decimal('latitude', 12, 12)
                ->default(0)
                ->nullable();
            $table
                ->decimal('longitude', 12, 12)
                ->default(0)
                ->nullable();
            $table
                ->float('monthly_rent', 10, 2)
                ->default(0)
                ->nullable();
            $table->text('store_layout_img')->nullable();
            $table->text('store_layout_pdf')->nullable();
            $table->string('contact_persion')->nullable();
            $table->string('shop_official_mobile')->nullable();
            $table->string('shop_official_email')->nullable();
            $table
                ->tinyInteger('status')
                ->default(1)
                ->nullable();
            $table->unsignedBigInteger('store_manager_id')->nullable();
            $table->string('opened_date')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
