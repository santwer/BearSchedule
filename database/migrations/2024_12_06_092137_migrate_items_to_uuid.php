<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
//throw new \Exception('This migration is not reversible');
        Schema::table('item_item_link', function (Blueprint $table) {
            $table->dropForeign('key_itemid');
            $table->uuid('item_uuid')
                ->after('id')
                ->nullable()
                ->default(null);
        });
        Schema::table('issues', function (Blueprint $table) {
            $table->dropForeign('item_id_issue');
            $table->uuid('item_uuid')
                ->after('id')
                ->nullable()
                ->default(null);
        });
        Schema::table('project_log', function (Blueprint $table) {
            $table->dropForeign('log_item_id');
            $table->uuid('item_uuid')
                ->after('id')
                ->nullable()
                ->default(null);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->uuid('uuid')
                ->after('id')
                ->unique()
                ->default(\Illuminate\Support\Facades\DB::raw('UUID()'));
        });

        DB::statement('UPDATE item_item_link SET item_uuid = (SELECT uuid FROM items WHERE id = item_id)');
        DB::statement('UPDATE issues SET item_uuid = (SELECT uuid FROM items WHERE id = item_id)');
        DB::statement('UPDATE project_log SET item_uuid = (SELECT uuid FROM items WHERE id = item_id)');

        Schema::table('item_item_link', function (Blueprint $table) {
            $table->dropColumn('item_id');
        });
        Schema::table('issues', function (Blueprint $table) {
            $table->dropColumn('item_id');
        });
        Schema::table('project_log', function (Blueprint $table) {
            $table->dropColumn('item_id');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->bigInteger('id')->change();
        });
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        Schema::table('items', function (Blueprint $table) {
            $table->renameColumn('uuid', 'id');
            $table->primary('id');
        });

        Schema::table('item_item_link', function (Blueprint $table) {
            $table->renameColumn('item_uuid', 'item_id');

        });
        Schema::table('issues', function (Blueprint $table) {
            $table->renameColumn('item_uuid', 'item_id');
        });
        Schema::table('project_log', function (Blueprint $table) {
            $table->renameColumn('item_uuid', 'item_id');
        });
        Schema::table('item_item_link', function (Blueprint $table) {
            $table->foreign('item_id', 'key_itemid')
                ->references('id')
                ->on('items')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
        Schema::table('issues', function (Blueprint $table) {
            $table->foreign('item_id', 'item_id_issue')
                ->references('id')
                ->on('items')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
        Schema::table('project_log', function (Blueprint $table) {
            $table->foreign('item_id', 'log_item_id')
                ->references('id')
                ->on('items')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropPrimary('items_id_primary');
        });
        Schema::table('items', function (Blueprint $table) {
            $table->renameColumn('id', 'uuid');
        });
        Schema::table('items', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
        Schema::table('items', function (Blueprint $table) {
            $table->bigIncrements('id');
        });

        Schema::table('item_item_link', function (Blueprint $table) {
            $table->foreign('item_id', 'key_itemid')
                ->references('id')
                ->on('items')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
        Schema::table('issues', function (Blueprint $table) {
            $table->foreign('item_id', 'item_id_issue')
                ->references('id')
                ->on('items')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
        Schema::table('project_log', function (Blueprint $table) {
            $table->foreign('item_id', 'log_item_id')
                ->references('id')
                ->on('items')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });


    }
};
