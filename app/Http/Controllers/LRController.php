<?php

namespace App\Http\Controllers;

use App\Models\LRDocuments;
use Illuminate\Http\Request;
use App\DataTables\LRDocumentDataTable;

class LRController extends Controller
{
    public function index(LRDocumentDataTable $dataTable)
    {
        return $dataTable->render('pages.lrDocuments.index');
    }

    public function store(Request $request)
{
    
    $multipleImages = $request->reference_images;

    $lrDocumentUrl = [];

    if ($multipleImages) {
        foreach ($multipleImages as $image) {

            $originalFilename = time() . "-" . str_replace(' ', '_', $image->getClientOriginalName());
            $destinationPath = public_path('lr_documents/');

            // Move the file
            $image->move($destinationPath, $originalFilename);

            // Store path
            $lrDocumentUrl[] = 'lr_documents/' . $originalFilename;
        }
    } else {
        $lrDocumentUrl = null;
    }

    // Save in DB
    $lrDocuments = new LRDocuments();
    $lrDocuments->quotation_id = $request->quotation_id;
    $lrDocuments->entry_date = date("Y-m-d");
    $lrDocuments->remarks = $request->remarks;

    // Important: JSON encode if array
    $lrDocuments->upload_documents = is_array($lrDocumentUrl) 
        ? json_encode($lrDocumentUrl) 
        : null;

    $lrDocuments->save();

    return response()->json([
        "status" => 'success',
        "message" => 'Document Uploaded Successfully'
    ]);
}

}