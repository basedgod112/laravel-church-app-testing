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
    public function index()
    {
        $categories = FaqCategory::with('faqs')->orderBy('name')->get();
        return view('faq.index', compact('categories'));
    }

    // Admin: category CRUD
    public function categories()
    {
        $this->authorizeAdmin();
        $categories = FaqCategory::orderBy('name')->get();
        return view('faq.admin.categories', compact('categories'));
    }

    public function createCategory()
    {
        $this->authorizeAdmin();
        return view('faq.admin.category-form', ['category' => new FaqCategory()]);
    }

    public function storeCategory(Request $request)
    {
        $this->authorizeAdmin();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        FaqCategory::create($data);
        return Redirect::route('faq.categories.index')->with('success', 'Category created');
    }

    public function editCategory($id)
    {
        $this->authorizeAdmin();
        $category = FaqCategory::findOrFail($id);
        return view('faq.admin.category-form', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
        $this->authorizeAdmin();
        $category = FaqCategory::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $category->update($data);
        return Redirect::route('faq.categories.index')->with('success', 'Category updated');
    }

    public function destroyCategory($id)
    {
        $this->authorizeAdmin();
        $category = FaqCategory::findOrFail($id);
        $category->delete();
        return Redirect::route('faq.categories.index')->with('success', 'Category deleted');
    }

    // Admin: faq CRUD
    public function faqs()
    {
        $this->authorizeAdmin();
        $faqs = Faq::with('category')->orderBy('faq_category_id')->get();
        return view('faq.admin.faqs', compact('faqs'));
    }

    public function createFaq()
    {
        $this->authorizeAdmin();
        $categories = FaqCategory::orderBy('name')->get();
        return view('faq.admin.faq-form', ['faq' => new Faq(), 'categories' => $categories]);
    }

    public function storeFaq(Request $request)
    {
        $this->authorizeAdmin();
        $data = $request->validate([
            'faq_category_id' => 'required|exists:faq_categories,id',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);
        Faq::create($data);
        return Redirect::route('faq.faqs.index')->with('success', 'FAQ created');
    }

    public function editFaq($id)
    {
        $this->authorizeAdmin();
        $faq = Faq::findOrFail($id);
        $categories = FaqCategory::orderBy('name')->get();
        return view('faq.admin.faq-form', compact('faq', 'categories'));
    }

    public function updateFaq(Request $request, $id)
    {
        $this->authorizeAdmin();
        $faq = Faq::findOrFail($id);
        $data = $request->validate([
            'faq_category_id' => 'required|exists:faq_categories,id',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);
        $faq->update($data);
        return Redirect::route('faq.faqs.index')->with('success', 'FAQ updated');
    }

    public function destroyFaq($id)
    {
        $this->authorizeAdmin();
        $faq = Faq::findOrFail($id);
        $faq->delete();
        return Redirect::route('faq.faqs.index')->with('success', 'FAQ deleted');
    }

    protected function authorizeAdmin()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }
    }
}
