<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateSitemap extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        // modify this to your own needs
        $file = "sitemap.xml";
        $doc = new \DOMDocument('1.0', 'utf-8');
        $doc->formatOutput = true;

        $storesX = $doc->createElement("urlset");
        $storesX->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $storesX->setAttribute('xmlns:content', 'http://www.google.com/schemas/sitemap-content/1.0');
        $storesX->setAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');
        $doc->appendChild($storesX);

        // Home page
        $store = $doc->createElement("url");
        $loc = $doc->createElement("loc");
        $loc->appendChild($doc->createTextNode(url('/')));
        $store->appendChild($loc);

        $lastmod = $doc->createElement("lastmod");
        $lastmod->appendChild($doc->createTextNode(\Carbon\Carbon::now()->format("c")));
        $store->appendChild($lastmod);

        $changefreq = $doc->createElement("changefreq");
        $changefreq->appendChild($doc->createTextNode("daily"));
        $store->appendChild($changefreq);

        $priority = $doc->createElement("priority");
        $priority->appendChild($doc->createTextNode("1.0"));
        $store->appendChild($priority);

        $storesX->appendChild($store);

        // Contact Us page
        $store = $doc->createElement("url");
        $loc = $doc->createElement("loc");
        $loc->appendChild($doc->createTextNode(url('/contact-us')));
        $store->appendChild($loc);

        $lastmod = $doc->createElement("lastmod");
        $lastmod->appendChild($doc->createTextNode(\Carbon\Carbon::now()->format("c")));
        $store->appendChild($lastmod);

        $changefreq = $doc->createElement("changefreq");
        $changefreq->appendChild($doc->createTextNode("daily"));
        $store->appendChild($changefreq);

        $priority = $doc->createElement("priority");
        $priority->appendChild($doc->createTextNode("0.8"));
        $store->appendChild($priority);

        $storesX->appendChild($store);

        // Help page
        $store = $doc->createElement("url");
        $loc = $doc->createElement("loc");
        $loc->appendChild($doc->createTextNode(url('/help')));
        $store->appendChild($loc);

        $lastmod = $doc->createElement("lastmod");
        $lastmod->appendChild($doc->createTextNode(\Carbon\Carbon::now()->format("c")));
        $store->appendChild($lastmod);

        $changefreq = $doc->createElement("changefreq");
        $changefreq->appendChild($doc->createTextNode("daily"));
        $store->appendChild($changefreq);

        $priority = $doc->createElement("priority");
        $priority->appendChild($doc->createTextNode("0.8"));
        $store->appendChild($priority);

        $storesX->appendChild($store);

        // Blog page
        $store = $doc->createElement("url");
        $loc = $doc->createElement("loc");
        $loc->appendChild($doc->createTextNode(url('/blog')));
        $store->appendChild($loc);

        $lastmod = $doc->createElement("lastmod");
        $lastmod->appendChild($doc->createTextNode(\Carbon\Carbon::now()->format("c")));
        $store->appendChild($lastmod);

        $changefreq = $doc->createElement("changefreq");
        $changefreq->appendChild($doc->createTextNode("daily"));
        $store->appendChild($changefreq);

        $priority = $doc->createElement("priority");
        $priority->appendChild($doc->createTextNode("0.8"));
        $store->appendChild($priority);

        $storesX->appendChild($store);

        // Blog Data
        $objBlogs = \App\Blog::select("name", "slug", "updated_at")->where("is_draft", 0)->orderby("updated_at", "DESC")->get();
        $bg = 0;
        foreach ($objBlogs as $objBlog) {
            $store = $doc->createElement("url");

            $loc = $doc->createElement("loc");
            $loc->appendChild($doc->createTextNode(url('/blog/' . $objBlog->slug . "/")));
            $store->appendChild($loc);

            /*$name = $doc->createElement("name");
            $name->appendChild($doc->createTextNode(trim($objBlog->name)));
            $store->appendChild($name);*/

            $lastmod = $doc->createElement("lastmod");
            $lastmod->appendChild($doc->createTextNode(\Carbon\Carbon::parse($objBlog->updated_at)->format("c")));
            $store->appendChild($lastmod);

            $changefreq = $doc->createElement("changefreq");
            $changefreq->appendChild($doc->createTextNode("daily"));
            $store->appendChild($changefreq);

            $priority = $doc->createElement("priority");
            $priority->appendChild($doc->createTextNode("0.8"));
            $store->appendChild($priority);

            /*$blogcount = $doc->createElement("blogcount");
            $blogcount->appendChild($doc->createTextNode($bg++));
            $store->appendChild($blogcount);*/

            $storesX->appendChild($store);
        }

        // Teachers page
        $store = $doc->createElement("url");
        $loc = $doc->createElement("loc");
        $loc->appendChild($doc->createTextNode(url('/teachers')));
        $store->appendChild($loc);

        $lastmod = $doc->createElement("lastmod");
        $lastmod->appendChild($doc->createTextNode(\Carbon\Carbon::now()->format("c")));
        $store->appendChild($lastmod);

        $changefreq = $doc->createElement("changefreq");
        $changefreq->appendChild($doc->createTextNode("daily"));
        $store->appendChild($changefreq);

        $priority = $doc->createElement("priority");
        $priority->appendChild($doc->createTextNode("0.8"));
        $store->appendChild($priority);

        $storesX->appendChild($store);

        // Teacher Data
        $objTeachers = \App\Teachers::select("name", "slug", "updated_at")->orderBy("updated_at", "DESC")->get();
        $tc = 0;
        foreach ($objTeachers as $objTeacher) {
            $store = $doc->createElement("url");

            $loc = $doc->createElement("loc");
            $loc->appendChild($doc->createTextNode(url('/teacher/' . $objTeacher->slug . "/")));
            $store->appendChild($loc);

            /*$name = $doc->createElement("name");
            $name->appendChild($doc->createTextNode(trim($objTeacher->name)));
            $store->appendChild($name);*/

            $lastmod = $doc->createElement("lastmod");
            $lastmod->appendChild($doc->createTextNode(\Carbon\Carbon::parse($objTeacher->updated_at)->format("c")));
            $store->appendChild($lastmod);

            $changefreq = $doc->createElement("changefreq");
            $changefreq->appendChild($doc->createTextNode("daily"));
            $store->appendChild($changefreq);

            $priority = $doc->createElement("priority");
            $priority->appendChild($doc->createTextNode("0.8"));
            $store->appendChild($priority);

            /*$teachcount = $doc->createElement("teachcount");
            $teachcount->appendChild($doc->createTextNode($tc++));
            $store->appendChild($teachcount);*/

            $storesX->appendChild($store);
        }

        // Category Data
        $objCategories = \App\ExperienceCategory::select('category.*')
                        ->join("category", "category_id", "=", "category.id")
                        ->join("experiences", "experiences.id", "=", "experience_category.experience_id")
                        ->where('is_draft', 0)->where("parent", "=", 0)->where('category.type', 0)->orderBy('category.name', 'asc')->groupBy('category.name')->get();
        $cat = 0;
        foreach ($objCategories as $objCategory) {
            $store = $doc->createElement("url");

            $loc = $doc->createElement("loc");
            $loc->appendChild($doc->createTextNode(url('/category/' . $objCategory->slug . "/")));
            $store->appendChild($loc);

            /*$name = $doc->createElement("name");
            $name->appendChild($doc->createTextNode(trim($objCategory->name)));
            $store->appendChild($name);*/

            $lastmod = $doc->createElement("lastmod");
            $lastmod->appendChild($doc->createTextNode(\Carbon\Carbon::parse($objCategory->updated_at)->format("c")));
            $store->appendChild($lastmod);

            $changefreq = $doc->createElement("changefreq");
            $changefreq->appendChild($doc->createTextNode("daily"));
            $store->appendChild($changefreq);

            $priority = $doc->createElement("priority");
            $priority->appendChild($doc->createTextNode("0.8"));
            $store->appendChild($priority);

            /*$catcount = $doc->createElement("catcount");
            $catcount->appendChild($doc->createTextNode($cat++));
            $store->appendChild($catcount);*/

            $storesX->appendChild($store);

            // Sub Category Data
            $objSubCategories = \App\ExperienceCategory::select('category.*')
                            ->join("category", "category_id", "=", "category.id")
                            ->join("experiences", "experiences.id", "=", "experience_category.experience_id")
                            ->where('is_draft', 0)->where("parent", "=", $objCategory->id)->where('category.type', 0)->orderBy('category.name', 'asc')->groupBy('category.name')->get();
            if ($objSubCategories) {

                $subcat = 0;
                foreach ($objSubCategories as $objSubCategory) {
                    $store = $doc->createElement("url");

                    $loc = $doc->createElement("loc");
                    $loc->appendChild($doc->createTextNode(url('/category/' . $objCategory->slug . "/" . $objSubCategory->slug . "/")));
                    $store->appendChild($loc);

                    /*$name = $doc->createElement("name");
                    $name->appendChild($doc->createTextNode(trim($objSubCategory->name)));
                    $store->appendChild($name);*/

                    $lastmod = $doc->createElement("lastmod");
                    $lastmod->appendChild($doc->createTextNode(\Carbon\Carbon::parse($objSubCategory->updated_at)->format("c")));
                    $store->appendChild($lastmod);

                    $changefreq = $doc->createElement("changefreq");
                    $changefreq->appendChild($doc->createTextNode("daily"));
                    $store->appendChild($changefreq);

                    $priority = $doc->createElement("priority");
                    $priority->appendChild($doc->createTextNode("0.8"));
                    $store->appendChild($priority);

                    /*$subcatcount = $doc->createElement("subcatcount");
                    $subcatcount->appendChild($doc->createTextNode($subcat++));
                    $store->appendChild($subcatcount);*/

                    $storesX->appendChild($store);
                }
            }
        }

        // Country Data
        $objDestinations = \App\ExperienceCategory::select('category.*')
                        ->join("category", "category_id", "=", "category.id")
                        ->join("experiences", "experiences.id", "=", "experience_category.experience_id")
                        ->where('is_draft', 0)->where("parent", "=", 0)->where('category.type', 1)->orderBy('category.name', 'asc')->groupBy('category.name')->get();
        $country = 0;
        foreach ($objDestinations as $objDestination) {
            $store = $doc->createElement("url");

            $loc = $doc->createElement("loc");
            $loc->appendChild($doc->createTextNode(url('/location/' . $objDestination->slug . "/")));
            $store->appendChild($loc);

            /*$name = $doc->createElement("name");
            $name->appendChild($doc->createTextNode(trim($objDestination->name)));
            $store->appendChild($name);*/

            $lastmod = $doc->createElement("lastmod");
            $lastmod->appendChild($doc->createTextNode(\Carbon\Carbon::parse($objDestination->updated_at)->format("c")));
            $store->appendChild($lastmod);

            $changefreq = $doc->createElement("changefreq");
            $changefreq->appendChild($doc->createTextNode("daily"));
            $store->appendChild($changefreq);

            $priority = $doc->createElement("priority");
            $priority->appendChild($doc->createTextNode("0.8"));
            $store->appendChild($priority);

            /*$countrycount = $doc->createElement("countrycount");
            $countrycount->appendChild($doc->createTextNode($country++));
            $store->appendChild($countrycount);*/

            $storesX->appendChild($store);


            // City Data
            $objCities = \App\ExperienceCategory::select('category.*')
                            ->join("category", "category_id", "=", "category.id")
                            ->join("experiences", "experiences.id", "=", "experience_category.experience_id")
                            ->where('is_draft', 0)->where("parent", "=", $objDestination->id)->where('category.type', 1)->orderBy('category.name', 'desc')->groupBy('category.name')->get();
            $ct = 0;
            foreach ($objCities as $objCity) {
                $store = $doc->createElement("url");

                $loc = $doc->createElement("loc");
                $loc->appendChild($doc->createTextNode(url('/location/' . $objDestination->slug . '/' . $objCity->slug . "/")));
                $store->appendChild($loc);

                /*$name = $doc->createElement("name");
                $name->appendChild($doc->createTextNode(trim($objCity->name)));
                $store->appendChild($name);*/

                $lastmod = $doc->createElement("lastmod");
                $lastmod->appendChild($doc->createTextNode(\Carbon\Carbon::parse($objCity->updated_at)->format("c")));
                $store->appendChild($lastmod);

                $changefreq = $doc->createElement("changefreq");
                $changefreq->appendChild($doc->createTextNode("daily"));
                $store->appendChild($changefreq);

                $priority = $doc->createElement("priority");
                $priority->appendChild($doc->createTextNode("0.8"));
                $store->appendChild($priority);

                /*$ctcount = $doc->createElement("ctcount");
                $ctcount->appendChild($doc->createTextNode($ct++));
                $store->appendChild($ctcount);*/

                $storesX->appendChild($store);
            }
        }

        // Experiences Data
        $objExperiences = \App\Experiences::get_exp_price_data("", "e.updated_at", "DESC");
        $lst = 0;
        foreach ($objExperiences as $objExperience) {
            $store = $doc->createElement("url");

            $loc = $doc->createElement("loc");
            $loc->appendChild($doc->createTextNode(url("/experience/" . $objExperience->slug . "/")));
            $store->appendChild($loc);

            /*$name = $doc->createElement("name");
            $name->appendChild($doc->createTextNode(trim($objExperience->name)));
            $store->appendChild($name);*/

            $lastmod = $doc->createElement("lastmod");
            $lastmod->appendChild($doc->createTextNode(\Carbon\Carbon::parse($objExperience->updated_at)->format("c")));
            $store->appendChild($lastmod);

            $changefreq = $doc->createElement("changefreq");
            $changefreq->appendChild($doc->createTextNode("daily"));
            $store->appendChild($changefreq);

            $priority = $doc->createElement("priority");
            $priority->appendChild($doc->createTextNode("0.5"));
            $store->appendChild($priority);

            /*$lstcount = $doc->createElement("lstcount");
            $lstcount->appendChild($doc->createTextNode($lst++));
            $store->appendChild($lstcount);*/

            $storesX->appendChild($store);
        }
        
        // Centres Data
        $objCentres = \App\Centers::select("name", "slug", "updated_at")->where("is_draft", 0)->orderby("updated_at", "DESC")->get();
        $cntr = 0;
        foreach ($objCentres as $objCentre) {
            $store = $doc->createElement("url");

            $loc = $doc->createElement("loc");
            $loc->appendChild($doc->createTextNode(url("/center/" . $objCentre->slug . "/")));
            $store->appendChild($loc);

            /*$name = $doc->createElement("name");
            $name->appendChild($doc->createTextNode(trim($objCentre->name)));
            $store->appendChild($name);*/

            $lastmod = $doc->createElement("lastmod");
            $lastmod->appendChild($doc->createTextNode(\Carbon\Carbon::parse($objCentre->updated_at)->format("c")));
            $store->appendChild($lastmod);

            $changefreq = $doc->createElement("changefreq");
            $changefreq->appendChild($doc->createTextNode("daily"));
            $store->appendChild($changefreq);

            $priority = $doc->createElement("priority");
            $priority->appendChild($doc->createTextNode("0.5"));
            $store->appendChild($priority);

            /*$lstcount = $doc->createElement("cntrcount");
            $lstcount->appendChild($doc->createTextNode($cntr++));
            $store->appendChild($lstcount);*/

            $storesX->appendChild($store);
        }
        file_put_contents($file, $doc->saveXML());
        file_put_contents($file, $doc->saveXML());
    }

}
