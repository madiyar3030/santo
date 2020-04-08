<?php

use App\Models\Article;
use App\Models\Detail;
use App\Models\Event;
use App\Models\Forum;
use App\Models\Playlist;
use App\Models\Record;
use App\Models\Vaccine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->seedPromocodes();
    }

    public function seedArticles($count) {
        for ($i = 0; $i < $count; $i++) {
            $article = new Article();
            $article->type = 'article';
            $article->title = 'Статья '.($i + 1);
            $article->save();
            $detail = new Detail([
                'detailable_type' => 'article',
                'detailable_id' => $article->id,
                'order' => 1,
                'type' => 'title',
                'value' => 'Питание ребенка от 6 месяцев до года'
            ]);
            $detail->save();
            $detail = new Detail([
                'detailable_type' => 'article',
                'detailable_id' => $article->id,
                'order' => 1,
                'type' => 'citation',
                'value' => 'Идейные соображения высшего порядка, а также дальнейшее развитие различных форм деятельности'
            ]);
            $detail->save();
            $detail = new Detail([
                'detailable_type' => 'article',
                'detailable_id' => $article->id,
                'order' => 1,
                'type' => 'description',
                'value' => 'Питание ребенка от 6 месяцев до года Идейные соображения высшего порядка, а также дальнейшее развитие различных форм деятельности представляет собой интересный эксперимент проверки позиций, занимаемых участниками в отношении поставленных задач. Таким образом постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет оценить значение модели развития. С другой стороны рамки и место обучения кадров позволяет оценить значение дальнейших направлений развития. Повседневная практика показывает, что постоянное информационно-пропагандистское обеспечение нашей деятельности требуют определения и Питание ребенка от 6 месяцев до года уточнения системы обучения кадров, соответствует насущным потребностям. Товарищи! консультация с широким активом влечет за собой процесс внедрения и модернизации новых предложений. Повседневная практика показывает, что сложившаяся структура организации в значительной степени обуславливает создание существенных финансовых и административных условий. Товарищи! консультация с широким активом влечет за собой процесс внедрения и модернизации новых предложений. Повседневная практика показывает, что сложившаяся структура организации в значительной степени обуславливает создание существенных финансовых и административных условий. Товарищи! консультация с широким активом влечет за собой процесс внедрения и модернизации новых предложений. Повседневная практика показывает, что сложившаяся структура организации в значительной степени обуславливает создание существенны'
            ]);
            $detail->save();
        }
    }

    public function seedEvents($count) {
        for ($i = 0; $i < $count; $i++) {
            $event = new Event([
                'type' => 'forum',
                'title' => 'Событие №'.$i,
                'location' => 'Atakent',
                'date_from' => Carbon::today(),
                'date_to' => Carbon::tomorrow(),
                'time_from' => '16:00',
                'time_to' => '20:00'
            ]);
            $event->save();
        }
    }

    public function seedPlaylists() {

        $playlist = new Playlist();
        $playlist->title = 'Кофе как стимулятор мыслей';
        $playlist->save();

        $record = new Record();
        $record->playlist_id = $playlist->id;
        $record->title = 'Аудиозапись №1';
        $record->url = '';
        $record->play_time = '00:03:15';
        $record->save();


        $playlist = new Playlist();
        $playlist->title = 'Мысли материальны';
        $playlist->save();

        $record = new Record();
        $record->playlist_id = $playlist->id;
        $record->title = 'Аудиозапись №1';
        $record->url = '';
        $record->play_time = '00:03:15';
        $record->save();


        $playlist = new Playlist();
        $playlist->title = 'Дыши и живи';
        $playlist->save();

        $record = new Record();
        $record->playlist_id = $playlist->id;
        $record->title = 'Аудиозапись №1';
        $record->url = '';
        $record->play_time = '00:03:15';
        $record->save();

        $playlist = new Playlist();
        $playlist->title = 'Спокойствие с Анной Твен';
        $playlist->save();

        $record = new Record();
        $record->playlist_id = $playlist->id;
        $record->title = 'Аудиозапись №1';
        $record->url = '';
        $record->play_time = '00:03:15';
        $record->save();
    }

    public function seedForums() {
        $forum = new Forum();
        $forum->title = 'Психология общения';
        $forum->description = '';
        $forum->save();
        $forum = new Forum();
        $forum->title = 'Бодрость по утрам - залог здоровья матери';
        $forum->description = '';
        $forum->save();
        $forum = new Forum();
        $forum->title = 'Прививка АКДС - быть или не быть';
        $forum->description = '';
        $forum->save();
    }

    public function seedVaccines() {
        $vaccine = new Vaccine([
            'title' => 'Прививка АКДС',
            'description' => 'Товарищи! новая модель организационной деятельности позволяет позволяет выполнять',
            'age_from' => 1,
            'age_to' => 2,
            'age_type' => 'years'
        ]);
        $vaccine->save();

        $vaccine = new Vaccine([
            'title' => 'Прививка от кори',
            'description' => 'С другой стороны дальнейшее развитие различных форм деятельности в позволяет значительной',
            'age_from' => null,
            'age_to' => 5,
            'age_type' => 'years',
        ]);
        $vaccine->save();
    }

    public function seedPromocodes() {
        $users = \App\Models\User::withTrashed()->get();
        foreach ($users as $user) {
            $promocode = mt_rand(pow(10, 15), pow(10, 16)-1);
            $user->promocode = $promocode;
            $user->save();
        }
    }

}
