<?php
namespace FiveSay;

use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;

class VendorCleaner
{
    public static function backup()
    {
        $vendorDir = dirname(dirname(dirname(dirname(__DIR__))));
        $topDir    = dirname($vendorDir);
        $rules     = require 'VendorCleaner/VendorCleaner.config.php';
        $File      = new Filesystem();
        $finder    = new Finder();

        var_dump($vendorDir, $rules);
        // foreach($rules as $packageDir => $rule){
        //     if(!file_exists($vendorDir . '/' . $packageDir)){
        //         continue;
        //     }
        //     $patterns = explode(' ', $rule);
        //     foreach($patterns as $pattern){
        //         try{
        //             $finder = new Finder();

        //             /** @var \SplFileInfo $file */
        //             foreach($finder->name($pattern)->in( $vendorDir . '/' . $packageDir) as $file){
        //                 if($file->isDir()){
        //                     $filesystem->deleteDirectory($file);
        //                 }elseif($file->isFile()){
        //                     $filesystem->delete($file);
        //                 }
        //             }
        //         }catch(\Exception $e){
        //             //TODO; check why error are thrown on deleting directories
        //             //$this->error("Could not parse $packageDir ($pattern): ".$e->getMessage());
        //         }
        //     }
        // }


    }


    


}
