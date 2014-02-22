# VendorCleaner

vendor 目录清理程序。

---

## 在什么情况下使用 VendorCleaner？

- 由于主机商的限制无法在部署阶段使用 Composer，需要由本地打包 Vendor 目录上传。
- Composer 默认安装的 Vendor 目录存在部分非必要文件，造成 Vendor 目录臃肿。

## 是否有框架限制？

- 没有。
- 任何使用 Composer 的项目均可使用。

## 使用效果如何？

- laravel-4.1 下进行测试，Vendor 目录瘦身近50%。

## 如何使用此项目？

在 composer.json 文件中申明依赖：

    "five-say/vendor-cleaner": "dev-master"

在 composer.json 文件 scripts 属性中加入对应的脚本事件回调：

    "scripts": {
        ...
        "pre-update-cmd": [
            "FiveSay\\VendorCleaner::restore"
        ],
        "post-update-cmd": [
            "FiveSay\\VendorCleaner::backup",
            "php artisan clear-compiled",
            "php artisan optimize"
        ],
        ...
    },

如此，在每次 update 操作时都将触发 vendor 目录的清理程序。

> **注意：** `FiveSay\\VendorCleaner::backup` 负责在每次更新后清理 Vendor 目录，清理完成后将在 Vendor 同级目录生成 VendorCleanerBackup 文件夹，用于存放清理出来的文件。而 `FiveSay\\VendorCleaner::restore` 则会在每次更新前将备份的文件放回 Vendor 目录，以确保 Composer 不会下载多余的文件，加快更新速度。

## 如何自定义清理规则？

默认使用的规则文件位于 `/vendor/five-say/vendor-cleaner/src/FiveSay/VendorCleaner/VendorCleaner.config.php`。如果需要使用自定义的规则，在 Vendor 同级目录建立自定义的 VendorCleaner.config.php 即可（注意：这将完全使用用户自定义规则，而忽略默认规则文件）。

这里有一个简单的例子：

    'ircmaxell/password-compat' => 'README.md test'

如此，程序将清理 ircmaxell/password-compat 资源包中的 README.md 文件和 test 目录。



