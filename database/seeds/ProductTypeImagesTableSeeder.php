<?php

use App\ProductTypeImage;
use Illuminate\Database\Seeder;
use Symfony\Component\Finder\Finder;

class ProductTypeImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::delete('delete from producttypeimages');

        $folder = app_path() . '/../public/source/';
        $thumbFolder = $folder . 'tn/';

        $files = Finder::create()->in($folder)
            ->exclude('tn')
            ->notName('/tn_.*/')
            ->name('/^\d+.*?\.jpg/i');

        // foreach ($files as $file) {
        //     $filename = $file->getRelativePathname();
        //     var_dump($filename);
        //     var_dump($file->getRealPath());
        // }
        // exit;

        foreach ($files as $file) {

            $realpath = $file->getRealPath();

            $filename = $file->getRelativePathname();

            if (preg_match('/\.JPG/', $filename)) {

                $orgpath = $realpath;

                $tmppath = $realpath . 'X';

                rename($orgpath, $tmppath);

                $realpath = strtolower($realpath);

                //var_dump($realpath);

                rename($tmppath, $realpath);

                $filename = strtolower($filename); // because it is now !

            }
            //var_dump(['filename' => $filename]);
            preg_match('/(\d+)?(_*)?(\d*)\.jpg/', $filename, $m);
            //var_dump($m);
            $data = [
                'typeid' => (int) $m[1],
                'filename' => $m[0],
                'order' => (int) $m['3'],
            ];

            // image dimensions

            $image = Image::make($realpath);

            $data['width'] = $image->width();
            $data['height'] = $image->height();

            ProductTypeImage::create($data);

            // create thumb
            $image->resize(150, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            // save thumb
            $image->save($thumbFolder . $filename);

        }

        // ProductTypeImage::create(['field_id' => '1', 'value' => 'herb', 'order' => 0]);
    }
}
