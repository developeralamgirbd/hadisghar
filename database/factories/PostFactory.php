<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = $this->faker->sentence;
        return [
            'title' => $title,
            'meta_description' => $this->faker->text,
            'description' => $this->faker->text,
            'category_id' => $this->faker->randomElement(Category::select('id')->get()),
            'user_id' => $this->faker->randomElement(User::select('id')->get()),
            'feature_img' => $this->faker->imageUrl,
            'slug' => Str::slug($title),
            'status' => Arr::random($this->status()),
        ];
    }


    public function status() {
        return ['draft', 'published', 'pending'];
    }
}
