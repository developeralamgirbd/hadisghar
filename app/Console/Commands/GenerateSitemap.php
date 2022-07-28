<?php
namespace App\Console\Commands;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Console\Command;
//use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Spatie\Crawler\Crawler;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;
class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';
    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Generate the sitemap';
    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        SitemapIndex::create()
            ->add('/posts_sitemap.xml')
            ->add('/categories_sitemap.xml')
            ->add('/pages_sitemap.xml')
            ->writeToFile(public_path('sitemap.xml'));
//        Post Sitemap
        $posts = Post::all()->pluck('slug');
        $postSitemap = Sitemap::create();
            foreach ($posts as $post){
                $postSitemap->add('post/'.$post);
            }
            $postSitemap->writeToFile(public_path('posts_sitemap.xml'));

//Category Sitemap
        $categories = Category::all()->pluck('slug');
       $categorySitemap = Sitemap::create();
        foreach ($categories as $category){
            $categorySitemap->add('category/'.$category);
        }
        $categorySitemap->writeToFile(public_path('categories_sitemap.xml'));
//Pages Sitemap
        $pages = ['/', 'privacy'];
        Sitemap::create()
            ->add($pages)
            ->writeToFile(public_path('pages_sitemap.xml'));

        $this->info('Sitemap generating...');
        $this->info('Sitemap generated successfully');
    }
    public function getSignature(): string
    {
        return $this->signature;
    }
}
