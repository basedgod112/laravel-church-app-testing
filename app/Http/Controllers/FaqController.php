<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Faq;
use App\Models\FaqCategory;

class FaqController extends Controller
{
    // Public listing grouped by category
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $categories = FaqCategory::with('faqs')->orderBy('name')->get();
        return view('faq.index', compact('categories'));
    }

    // Admin: category CRUD
    public function categories(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $categories = FaqCategory::orderBy('name')->get();
        return view('faq.admin.categories', compact('categories'));
    }

    public function createCategory(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('faq.admin.category-form', ['category' => new FaqCategory()]);
    }

    public function storeCategory(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        FaqCategory::create($data);
        return Redirect::route('faq.categories.index')->with('success', 'Category created');
    }

    public function editCategory($id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $category = FaqCategory::findOrFail($id);
        return view('faq.admin.category-form', compact('category'));
    }

    public function updateCategory(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $category = FaqCategory::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $category->update($data);
        return Redirect::route('faq.categories.index')->with('success', 'Category updated');
    }

    public function destroyCategory($id): \Illuminate\Http\RedirectResponse
    {
        $category = FaqCategory::findOrFail($id);
        $category->delete();
        return Redirect::route('faq.categories.index')->with('success', 'Category deleted');
    }

    // Admin: faq CRUD
    public function faqs(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $faqs = Faq::with('category')->orderBy('faq_category_id')->get();
        return view('faq.admin.faqs', compact('faqs'));
    }

    public function createFaq(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $categories = FaqCategory::orderBy('name')->get();
        return view('faq.admin.faq-form', ['faq' => new Faq(), 'categories' => $categories]);
    }

    public function storeFaq(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'faq_category_id' => 'required|exists:faq_categories,id',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);
        Faq::create($data);
        return Redirect::route('faq.faqs.index')->with('success', 'FAQ created');
    }

    public function editFaq($id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $faq = Faq::findOrFail($id);
        $categories = FaqCategory::orderBy('name')->get();
        return view('faq.admin.faq-form', compact('faq', 'categories'));
    }

    public function updateFaq(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $faq = Faq::findOrFail($id);
        $data = $request->validate([
            'faq_category_id' => 'required|exists:faq_categories,id',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);
        $faq->update($data);
        return Redirect::route('faq.faqs.index')->with('success', 'FAQ updated');
    }

    public function destroyFaq($id): \Illuminate\Http\RedirectResponse
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();
        return Redirect::route('faq.faqs.index')->with('success', 'FAQ deleted');
    }
}
