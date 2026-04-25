<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPageImage;
use App\Models\LandingPageTestimonial;
use App\Models\Product;
use App\Models\ProductLandingPage;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function edit(Product $product)
    {
        $product->load(['landingPage', 'landingPageImages', 'landingPageTestimonials']);
        return view('admin.products.landing-page', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'nullable|string|max:255',
            'hero_image' => 'nullable|image|max:5120',
            'video_url' => 'nullable|url|max:500',
            'about_content' => 'nullable|string',
            'is_published' => 'nullable',
        ]);

        $data = [
            'hero_title' => $request->hero_title,
            'hero_subtitle' => $request->hero_subtitle,
            'video_url' => $request->video_url,
            'about_content' => $request->about_content,
            'is_published' => $request->boolean('is_published'),
        ];

        if ($request->hasFile('hero_image')) {
            $data['hero_image'] = $request->file('hero_image')->store('landing-pages', 'public');
        }

        $product->landingPage()->updateOrCreate(
            ['product_id' => $product->id],
            $data
        );

        return redirect()->route('admin.products.landing-page', $product)
            ->with('success', 'Landing page berhasil diperbarui.');
    }

    public function uploadImage(Request $request, Product $product)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|max:5120',
            'captions' => 'nullable|array',
            'captions.*' => 'nullable|string|max:255',
        ]);

        $maxOrder = $product->landingPageImages()->max('sort_order') ?? 0;

        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('landing-pages/gallery', 'public');
            $caption = $request->input("captions.{$index}");

            $product->landingPageImages()->create([
                'image_path' => $path,
                'caption' => $caption,
                'sort_order' => ++$maxOrder,
            ]);
        }

        return redirect()->route('admin.products.landing-page', $product)
            ->with('success', 'Gambar berhasil diupload.');
    }

    public function deleteImage(Product $product, LandingPageImage $image)
    {
        $image->delete();
        return redirect()->route('admin.products.landing-page', $product)
            ->with('success', 'Gambar berhasil dihapus.');
    }

    public function reorderImages(Request $request, Product $product)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:landing_page_images,id',
        ]);

        foreach ($request->order as $index => $id) {
            LandingPageImage::where('id', $id)->where('product_id', $product->id)
                ->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function storeTestimonial(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string',
        ]);

        $data = [
            'name' => $request->name,
            'rating' => $request->rating,
            'content' => $request->content,
            'sort_order' => ($product->landingPageTestimonials()->max('sort_order') ?? 0) + 1,
        ];

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('landing-pages/avatars', 'public');
        }

        $product->landingPageTestimonials()->create($data);

        return redirect()->route('admin.products.landing-page', $product)
            ->with('success', 'Testimonial berhasil ditambahkan.');
    }

    public function updateTestimonial(Request $request, Product $product, LandingPageTestimonial $testimonial)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string',
            'is_active' => 'nullable',
        ]);

        $data = [
            'name' => $request->name,
            'rating' => $request->rating,
            'content' => $request->content,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('landing-pages/avatars', 'public');
        }

        $testimonial->update($data);

        return redirect()->route('admin.products.landing-page', $product)
            ->with('success', 'Testimonial berhasil diperbarui.');
    }

    public function deleteTestimonial(Product $product, LandingPageTestimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.products.landing-page', $product)
            ->with('success', 'Testimonial berhasil dihapus.');
    }

    public function toggleTestimonial(Product $product, LandingPageTestimonial $testimonial)
    {
        $testimonial->update(['is_active' => !$testimonial->is_active]);
        return redirect()->route('admin.products.landing-page', $product)
            ->with('success', 'Status testimonial berhasil diubah.');
    }
}
