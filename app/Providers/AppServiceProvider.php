<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Blog;
use App\Models\Consultation;
use App\Models\ConsultationAnswer;
use App\Models\Development;
use App\Models\Event;
use App\Models\Feeding;
use App\Models\Forum;
use App\Models\Playlist;
use App\Models\Record;
use App\Models\Vaccine;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'article' => Article::class,
            'forum' => Forum::class,
            'event' => Event::class,
            'playlist' => Playlist::class,
            'vaccine' => Vaccine::class,
            'feeding' => Feeding::class,
            'consultation' => Consultation::class,
            'consultation_answer' => ConsultationAnswer::class,
            'development' => Development::class,
            'blog' => Blog::class,
            'record' => Record::class,
        ]);
    }
}
