<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = \App\Models\Post::class;

    public function definition()
    {
        // Force Faker to use English (US)
        $this->faker = FakerFactory::create('en_US');

        $title = $this->faker->sentence(8); // Real English sentence
        $body = $this->generateEnglishContent();

        $readingSpeed = 200;
        $words = str_word_count(strip_tags($body));
        $readingTime = ceil($words / $readingSpeed);

        return [
            'title' => $title,
            'excerpt' => $this->faker->sentence(40),
            'body' => '<p>' . nl2br($body) . '</p>',
            'image_path' => $this->faker->randomElement(['/images/posts/picture2.jpg', '/images/posts/picture.jpg']),
            'slug' => Str::slug($title),
            'is_published' => true,
            'user_id' => 1,
            'category_id' => $this->faker->numberBetween(1, 15),
            'read_time' => $readingTime,
            'change_user_id' => 1,
        ];
    }

    private function generateEnglishContent()
    {
        return collect([
            "The beauty of the mountains is unmatched by any other landscape.",
            "Technology continues to revolutionize the way we communicate and work.",
            "Traveling opens up new perspectives and helps us appreciate different cultures.",
            "Health and wellness are essential for a balanced and fulfilling life.",
            "Learning new skills can boost your career and personal growth.",
            "Nature is a constant source of inspiration and peace for the soul."
        ])->random() . ' ' . $this->faker->paragraphs(4, true);
    }
}
