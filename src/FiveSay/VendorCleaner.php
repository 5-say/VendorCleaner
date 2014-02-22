<?php
namespace FiveSay;

use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;

class VendorCleaner
{
    /**
     * 将非必要文件转移至备份文件夹，以减小 vendor 目录体积
     * @return void
     */
    public static function backup()
    {
        $Filesystem = new Filesystem();
        $vendorDir  = dirname(dirname(dirname(dirname(__DIR__))));
        $topDir     = dirname($vendorDir);
        $backupDir  = $topDir.'/VendorCleanerBackup';
        // 获取主要规则文件
        // 若 vendor 同级目录下存在用户自定义文件，则使用用户自定义文件
        if ($Filesystem->exists($topDir.'/VendorCleaner.config.php'))
            $rules = require $topDir.'/VendorCleaner.config.php';
        else
            $rules = require 'VendorCleaner/VendorCleaner.config.php';

        // 分别处理各个资源包
        foreach ($rules as $packageDir => $rule) {
            if (!file_exists($vendorDir . '/' . $packageDir)) continue;
            // 拆分子规则
            $patterns = explode(' ', $rule);
            // 执行拆分后的规则
            foreach ($patterns as $pattern) {
                $Finder = new Finder();
                try {
                    foreach ($Finder->name($pattern)->in( $vendorDir . '/' . $packageDir) as $file) {
                        $backup = str_replace($vendorDir, $backupDir, $file);
                        if ($file->isDir()) {
                            // 文件夹处理
                            $Filesystem->copyDirectory($file, $backup);
                            $Filesystem->deleteDirectory($file);
                        } elseif ($file->isFile()) {
                            // 文件处理
                            $Filesystem->move($file, $backup);
                        }
                    }
                } catch (\Exception $e) {
                }
            }
        }

    }

    /**
     * 从备份文件夹恢复原文件，防止资源包更新时做多余的下载浪费时间
     * @return void
     */
    public static function restore()
    {
        $Filesystem = new Filesystem();
        $vendorDir  = dirname(dirname(dirname(dirname(__DIR__))));
        $topDir     = dirname($vendorDir);
        $backupDir  = $topDir.'/VendorCleanerBackup';
        if ($Filesystem->exists($backupDir)) {
            // 文件恢复
            $Filesystem->copyDirectory($backupDir, $vendorDir);
            $Filesystem->deleteDirectory($backupDir);
        }
    }


}
