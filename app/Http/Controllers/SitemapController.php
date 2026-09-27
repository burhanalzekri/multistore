<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;

class SitemapController extends Controller
{
    public function index()
    {
        $shop = Shop::where('status', 'active')->first();
        $products = Product::withoutGlobalScope('tenant')
            ->where('shop_id', $shop?->id)
            ->where('is_active', true)
            ->get();

        $baseUrl = request()->getSchemeAndHttpHost();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // الصفحة الرئيسية
        $xml .= "  <url>\n    <loc>{$baseUrl}/</loc>\n    <changefreq>daily</changefreq>\n    <priority>1.0</priority>\n  </url>\n";

        // المتجر
        $xml .= "  <url>\n    <loc>{$baseUrl}/shop</loc>\n    <changefreq>daily</changefreq>\n    <priority>0.9</priority>\n  </url>\n";

        // المنتجات
        foreach ($products as $p) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$baseUrl}/product/{$p->id}</loc>\n";
            $xml .= "    <lastmod>{$p->updated_at->toAtomString()}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
