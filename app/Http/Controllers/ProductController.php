<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\BOMParts;
use Illuminate\Http\Request;
use App\Models\BomProcessTeams;
use App\Models\ProductComponents;
use App\DataTables\ProductDataTable;
use App\DataTables\ComponentDataTable;

class ProductController extends Controller
{
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('pages.products.index');
    }

    public function store(Request $request)
    {


        if ($request->hasFile('product_image')) {
            $uploadImage = $request->file('product_image');
            $originalFilename = time() . "-" . str_replace(' ', '_', $uploadImage->getClientOriginalName());
            $destinationPath = 'product_images/';
            $uploadImage->move($destinationPath, $originalFilename);
            $productImageUrl = '/product_images/' . $originalFilename;
        }
        else
        {
            $productImageUrl = null;
        }

        if ($request->hasFile('design_sheet')) {
            $uploadImage = $request->file('design_sheet');
            $originalFilename = time() . "-" . str_replace(' ', '_', $uploadImage->getClientOriginalName());
            $destinationPath = 'design_sheet/';
            $uploadImage->move($destinationPath, $originalFilename);
            $designSheetImageUrl = '/design_sheet/' . $originalFilename;
        }
        else
        {
            $designSheetImageUrl = null;
        }

         if ($request->hasFile('data_sheet')) {
            $uploadImage = $request->file('data_sheet');
            $originalFilename = time() . "-" . str_replace(' ', '_', $uploadImage->getClientOriginalName());
            $destinationPath = 'data_sheet/';
            $uploadImage->move($destinationPath, $originalFilename);
            $dataSheetImageUrl = '/data_sheet/' . $originalFilename;
        }
        else
        {
            $dataSheetImageUrl = null;
        }

        $products = new Product();
        $products->category_id = $request->category_id;
        $products->product_name = $request->product_name;
        $products->brand = $request->brand;
        $products->bike_model = $request->bike_model;
        $products->mrp_price = $request->mrp_price;
        $products->part_number = $request->part_number;
        $products->quantity = $request->product_quantity;
        $products->variation = $request->variation;
        $products->hsn_code = $request->hsn_code;
        $products->stock_qty = $request->stock_qty;
        $products->design_sheet = $designSheetImageUrl;
        $products->product_image = $productImageUrl;
        $products->data_sheet = $dataSheetImageUrl;
        $products->save();


       foreach ($request->bom_part_name as $index => $productName) {

        $categoryName = ProductComponents::find($request->packing_bom[$index]);

    $bom = new BOMParts();
    $bom->product_id = $products->id;
    $bom->bom_name = $productName;
    $bom->bom_category = ($request->bomType[$index] == 1) ? $request->bom_category[$index] : $categoryName->component_name;
    $bom->bom_unit = $request->bom_unit[$index] ?? null;
    $bom->bom_qty = $request->quantity[$index] ?? 0;
    $bom->bom_price = $request->price[$index] ?? 0;
    $bom->bom_type = ($request->bomType[$index] == 1) ? 'production' : 'packing';
    $bom->production_bom_id = ($request->bomType[$index] == 2) ? $request->packing_bom[$index] : null;
    $bom->save();

    $processTeams = $request->process_team[$index] ?? [];

    if (!is_array($processTeams)) {
        $processTeams = [$processTeams];
    }

    foreach ($processTeams as $row => $processTeam) {
        $bomProcessTeam = new BomProcessTeams();
        $bomProcessTeam->stage = "stage_" . ($row + 1);
        $bomProcessTeam->bom_id = $bom->id;
        $bomProcessTeam->product_id = $products->id;
        $bomProcessTeam->team_id = $processTeam;
        $bomProcessTeam->save();
    }
}



        return response()->json([
            "status" => 'success',
            "message" => 'Product Created Successfully...'
        ]);
    }

    public function show($id)
    {
        $product = Product::with('bomParts', 'bomParts.processTeam')->find($id);

        return view('pages.products.show', compact('product'));
    }

    public function create()
    {
        return view('pages.products.create');
    }

    public function components(ComponentDataTable $dataTable)
    {
        return $dataTable->render('pages.product_components.index');
    }

    public function componentsStore(Request $request)
    {
        $productComponent = new ProductComponents();
        $productComponent->component_name = $request->component_name;
        $productComponent->stock_qty = $request->stock_qty;
        $productComponent->code = $request->code;
        $productComponent->unit_price = $request->unit_price;
        $productComponent->save();

        return response()->json([
            "status" => 'success',
            "message" => 'Product Component Created Successfully...'
        ]);
    }
}