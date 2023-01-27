<?php

namespace App\Http\Controllers;

use App\Http\Requests;

use App\ProductTypeFile;
use Illuminate\Http\Request;

class ProductTypeFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $typeid = $request->get('id');
        $files = ProductTypeFile::where('typeid', $typeid)->orderBy('order', 'asc')->get();
        

        return view('admin.type.file', compact('files', 'typeid'));
    }

    public function sort()
    {

    }

    public function upload(Request $request,$typeid)
    {
       // check and get the file
       if (!$request->file('uploadfile')->isValid()) {
        return false;
    }

    ini_set('memory_limit', '-1');

        // Force a sort of all images for this typeid if needed
        // $this->doSortIfRequired($typeid);

        $file = $request->file('uploadfile');
        // dd($file);

        // get the typeid - already passed in

        // get the current max order value from db for this typeid
        // $files = ProductTypefile::where('typeid', $typeid)->orderBy('order', 'desc')->get();
        // $order = $files->first() ? $files->first()->order : -1;

        // $order++; // inc order to add new file to the end of the list

        // $filename = $this->makeFilename($typeid, $order);
        $filename = $file->getClientOriginalName();
        $order=0;
        $publicPath = public_path();
        $relfilepath = '/files/product/types/'.$typeid.'/';
        $absfilepath = $publicPath . $relfilepath;

        if(file_exists($absfilepath.$filename)) {
            return ['error'=>'file exists'];
        }

        $file->move($absfilepath, $filename);

        
        $data['filename'] = $filename;
        $data['filepath'] = $relfilepath;
        $data['order'] = $order;
        $data['typeid'] = $typeid;

        if($title = $request->title){
            $data['title'] = $title;
        }
        if($description = $request->description){
            $data['description'] = $description;
        }


        
        $created = ProductTypeFile::create($data);

        $files = ProductTypeFile::where('typeid',$typeid)->get();

        return ['success' => true, 'files' => $files->toArray()];

        
    }

    public function delete($id)
    {
        $item = ProductTypeFile::find($id);

        // Delete the file
        $publicPath = public_path();
        $filePath = $publicPath . '/files/product/types/'.$item->typeid.'/'.$item->filename;
        @unlink($filePath);

        $item->delete();

        return $id;



    }

    protected function makeFilename($typeid, $order)
    {

        $tail = '';
        $ext = '.pdf';
        if ($order > 0) {
            $tail = '_' . ($order + 1);
        }
        return $typeid . $tail . $ext;

    }
}
