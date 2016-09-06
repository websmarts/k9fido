<?php

namespace App\Console\Commands;

use App\ProductTypeImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Symfony\Component\Finder\Finder;

class K9ImportProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'k9:importproductimages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import product images to db and copy files to new folder format';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');

        // Delete all staff
        DB::connection('mysql')->delete('delete from producttypeimages');

        $sourceFolder = app_path() . '/../../k9catalog/catalog/source/';

        $newFolder = app_path() . '/../public/source/';

        $thumbFolder = $newFolder . 'tn/';

        $files = Finder::create()->in($sourceFolder)
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

            // if (preg_match('/\.JPG/', $filename)) {

            //     $orgpath = $realpath;

            //     $tmppath = $realpath . 'X';

            //     rename($orgpath, $tmppath);

            //     $realpath = strtolower($realpath);

            //     //var_dump($realpath);

            //     rename($tmppath, $realpath);

            //     $filename = strtolower($filename); // because it is now !

            // }
            echo $filename . "\n";
            preg_match('/(\d+)?(_*)?(\d*)\.jpg/i', $filename, $m);
            //var_dump(count($m));
            if (!isset($m[1])) {
                echo 'skipping ' . "\n";
                continue;
            }
            $data = [
                'typeid' => (int) $m[1],
                'filename' => strtolower($m[0]),
                'order' => (int) $m['3'],
            ];
            echo $filename . " - process\n";
            //dd($data);
            // image dimensions

            $image = Image::make($realpath);
            $image->save($newFolder . strtolower($filename));

            $data['width'] = $image->width();
            $data['height'] = $image->height();

            ProductTypeImage::create($data);

            // create thumb
            $image->resize(150, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            // save thumb
            $image->save($thumbFolder . strtolower($filename));

            $image->destroy();

        }
        $this->info('Image import and move complete');
    }
}
