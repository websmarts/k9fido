<?php

namespace App\Console\Commands;

use App\Http\Controllers\ProductTypeImageController;
use App\Legacy\Product\ProductType;
use App\ProductTypeImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Symfony\Component\Finder\Finder;

class K9UpdateProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'k9:updateproductimages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update db to match image files in source folder';

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

        $sourceFolder = app_path() . '/../public/source/';

        $thumbFolder = $sourceFolder . 'tn/';

        $files = Finder::create()->in($sourceFolder)
            ->exclude('tn')
            ->notName('/tn_.*/')
            ->name('/^\d+.*?\.jpg/i');
        $count = 0;
        foreach ($files as $file) {

            $realpath = $file->getRealPath();

            $filename = $file->getRelativePathname();

            //echo $filename . "\n";
            preg_match('/(\d+)?(_*)?(\d*)\.jpg/i', $filename, $m);
            //var_dump(count($m));
            if (!isset($m[1])) {
                echo 'skipping ' . "\n";
                continue;
            }

            $order = (int) $m['3'];
            $order = $order > 1 ? --$order : 0;
            $data = [
                'typeid' => (int) $m[1],
                'filename' => strtolower($m[0]),
                'order' => $order,
            ];
            $count++;
            echo '.';
            //dd($data);
            // image dimensions

            $image = Image::make($realpath);

            $data['width'] = $image->width();
            $data['height'] = $image->height();

            $image->destroy();

            ProductTypeImage::create($data);

        }
        $this->info($count . ' Images info has been imported to database productypeimages table');
        // sort the image collection for each type
        $types = ProductType::all();
        // login user with id 1 so we can use the controller
        Auth::loginUsingId(1);
        $C = new ProductTypeImageController();
        $count = 0;
        foreach ($types as $type) {
            echo 's';
            $count++;
            $C->doSortIfRequired($type->typeid);
        }
        $this->info($count . ' Types have had theior images sorted');
    }
}
