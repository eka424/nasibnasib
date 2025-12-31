<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kids_contents', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['video', 'story', 'quote']); // video youtube, story pdf, quote text
            $table->string('title');
            $table->string('slug')->unique();

            // Video
            $table->string('youtube_id')->nullable();     // ex: dQw4w9WgXcQ
            $table->string('youtube_url')->nullable();    // optional, for admin convenience

            // Story (PDF)
            $table->string('pdf_path')->nullable();       // storage path

            // Quote
            $table->text('quote_text')->nullable();
            $table->string('quote_source')->nullable();   // optional

            // Common
            $table->string('thumbnail')->nullable();      // optional url/path
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);

            $table->timestamps();

            $table->index(['type', 'is_published', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kids_contents');
    }
};
