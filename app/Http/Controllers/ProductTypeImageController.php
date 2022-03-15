<?php

namespace App\Http\Controllers;

use App\ProductTypeImage;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ProductTypeImageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $typeid = $request->get('id');
        $images = ProductTypeImage::where('typeid', $typeid)->orderBy('order', 'asc')->get();

        return view('admin.type.image', compact('images', 'typeid'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return ProductTypeImage::find($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($imageId)
    {
        $item = ProductTypeImage::find($imageId);

        // Find all images for this typeid
        $images = ProductTypeImage::where('typeid', $item->typeid)
            ->whereNotIn('id', [$imageId])
            ->select('id')
            ->orderBy('order', 'asc')
            ->get();

        foreach ($images as $i) {
            $list[] = $i->id;
        }
        // Add the first image 
        $list[] = (int) $imageId;
        //return $list;

        $item = $this->sort($list)->last();

        //return $item;

        //delete the file
        $filename = public_path() . '/source/' . $item->filename;

        @unlink($filename); // the image

        $filename = public_path() . '/source/tn/' . $item->filename;

        @unlink($filename); // the image

        $item->delete();

        return $imageId;
    }

    public function upload(Request $request, $typeid)
    {
        // check and get the file
        if (!$request->file('uploadfile')->isValid()) {
            return false;
        }

        ini_set('memory_limit', '-1');

        // Force a sort of all images for this typeid if needed
        $this->doSortIfRequired($typeid);

        $file = $request->file('uploadfile');

        // get the typeid - already passed in

        // get the current max order value from db for this typeid
        $images = ProductTypeImage::where('typeid', $typeid)->orderBy('order', 'desc')->get();
        $order = $images->first() ? $images->first()->order : -1;

        $order++; // inc order to add new image to the end of the list

        $filename = $this->makeFilename($typeid, $order);

        $publicPath = public_path();
        $imagePath = $publicPath . '/source';
        $thumbPath = $imagePath . '/tn/';

        $file->move($imagePath, $filename);

        $image = Image::make($imagePath . '/' . $filename);

        $data['width'] = $image->width();
        $data['height'] = $image->height();
        $data['filename'] = $filename;
        $data['order'] = $order;
        $data['typeid'] = $typeid;
        $created = ProductTypeImage::create($data);

        // create thumb
        $image->resize(150, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        // save thumb
        $image->save($thumbPath . $filename);

        return ['success' => true, 'imageid' => $created->id];

        // mv the file to its new home with the new name

        // save a thumbnail of the file
        // create a db entry
        // return list of images for typeid
    }

    public function doSortIfRequired($typeid)
    {
        $images = ProductTypeImage::where('typeid', $typeid);
        $result = $images->get()->sortBy('order')->keyBy('order');
        // if keys are not sequential then sort is required
        $sortRequired = false;
        $lastKey = -1;
        $ids = [];
        $m = false;
        //dd($result);
        foreach ($result as $key => $value) {
            if (($key - $lastKey) != 1) {
                $sortRequired = 10;
            }
            $lastKey = $key;

            // or if filename suffix not equal to $key +1 if exists
            preg_match('/_(\d+)\.jpg$/', $value->filename, $m);
            $mm[] = $m;
            if (isset($m[1])) {

                if ($m[1] != ($value->order + 1)) {
                    $sortRequired = $value->order;
                }
            }
            $ids[] = $value->id;
        }
        //echo $sortRequired;

        if ($sortRequired) {
            $this->sort($ids);
        }
    }

    public function sort($itemIDs = false)
    {
        $request = request();
        if (!$itemIDs) {
            $itemIDs = $request->get('item'); // itemIDs is an array of record ids for the images
        }

        $order = 0;
        foreach ($itemIDs as $id) {
            $image[$id] = ProductTypeImage::find($id);
            $image[$id]->order = $order++;
            //$image[$id]->save();
        }

        // rename all images to temp names
        $folder = app_path() . '/../public/source/';
        $thumbFolder = $folder . 'tn/';

        foreach ($image as $i) {
            @rename($folder . $i->filename, $folder . $i->filename . 'X');
            @rename($thumbFolder . $i->filename, $thumbFolder . $i->filename . 'X');
        }
        foreach ($image as $i) {

            $newFilename = $this->makeFilename($i->typeid, $i->order);

            @rename($folder . $i->filename . 'X', $folder . $newFilename);
            @rename($thumbFolder . $i->filename . 'X', $thumbFolder . $newFilename);

            $i->filename = $newFilename;
            $i->save();
        }

        $items = ProductTypeImage::whereIn('id', $itemIDs)->orderby('order', 'asc')->get();

        return $items;

    }

    protected function makeFilename($typeid, $order)
    {

        $tail = '';
        $ext = '.jpg';
        if ($order > 0) {
            $tail = '_' . ($order + 1);
        }
        return $typeid . $tail . $ext;

    }
}
