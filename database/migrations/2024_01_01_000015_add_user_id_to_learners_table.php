<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * The Learner model's $fillable and user() relation have always referenced
 * `user_id`, but the original create_learners_table migration never added
 * the column. On SQLite this fails silently (SQLite falls back to treating
 * an unresolvable double-quoted identifier as a string literal, so
 * `where "user_id" = ?` just returns zero rows instead of erroring).
 * On MySQL — what this app actually runs on — the same query throws
 * "Unknown column 'user_id' in 'where clause'", which fatal-errors the
 * entire learner portal the moment a learner logs in (Auth::user()->learner
 * is called from LearnerHomeworkPortal on every learner dashboard/homework
 * page load).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('learners', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('learners', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
