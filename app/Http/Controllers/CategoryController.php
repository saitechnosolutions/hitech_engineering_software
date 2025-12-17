<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\DataTables\CategoryDataTable;

class CategoryController extends Controller
{
    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('pages.categories.index');
    }

    public function store(Request $request)
    {

        try {
            $category = new Category();
        $category->name = $request->name;
        $category->category_code = $request->category_code;
        $category->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Category created successfully'
            ]);
        }
        catch (\Exception $e){
             return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
        }

    }

    public function edit($id)
    {
        $category = Category::find($id);

        return view('pages.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        $category->update([
            "name" => $request->name,
            "category_code" => $request->category_code
        ]);

        return response()->json([
            "status" => 'success',
            "message" => 'Category Updated Successfully',
            "redirectTo" => '/categories',
        ]);
    }

    public function delete($id)
    {
        $category = Category::find($id);
        $category->delete();

        return response()->json([
            "status" => 'success',
            "message" => 'Category Deleted Successfully',
            "redirectTo" => '/categories',
        ]);
    }
}