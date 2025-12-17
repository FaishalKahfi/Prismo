<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MitraProfile;
use Illuminate\Support\Facades\DB;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Homepage
        $sitemap .= $this->addUrl(url('/'), '1.0', 'daily', now());

        // Static pages
        $sitemap .= $this->addUrl(url('/login'), '0.8', 'monthly', now());
        $sitemap .= $this->addUrl(url('/register'), '0.8', 'monthly', now());

        // Mitra profiles (public)
        $mitras = MitraProfile::whereHas('user', function($query) {
            $query->where('approval_status', 'approved');
        })->get();

        foreach ($mitras as $mitra) {
            $sitemap .= $this->addUrl(
                url('/mitra/' . $mitra->id),
                '0.7',
                'weekly',
                $mitra->updated_at
            );
        }

        $sitemap .= '</urlset>';

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    private function addUrl($loc, $priority, $changefreq, $lastmod = null)
    {
        $url = '<url>';
        $url .= '<loc>' . htmlspecialchars($loc) . '</loc>';
        $url .= '<priority>' . $priority . '</priority>';
        $url .= '<changefreq>' . $changefreq . '</changefreq>';

        if ($lastmod) {
            $url .= '<lastmod>' . $lastmod->format('Y-m-d') . '</lastmod>';
        }

        $url .= '</url>';

        return $url;
    }

    public function robots()
    {
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /api/\n";
        $robots .= "Disallow: /dashboard-mitra/\n";
        $robots .= "Disallow: /dashboard-customer/\n";
        $robots .= "\n";
        $robots .= "Sitemap: " . url('/sitemap.xml');

        return response($robots, 200, [
            'Content-Type' => 'text/plain'
        ]);
    }
}
