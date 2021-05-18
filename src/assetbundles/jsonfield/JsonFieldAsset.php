<?php

namespace augmentations\craft\json\assetbundles\jsonfield;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * Class JsonFieldAsset
 *
 * @package augmentations\craft\json\assetbundles\jsonfield
 */
class JsonFieldAsset extends AssetBundle
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        $this->sourcePath = "@augmentations/craft/json/assetbundles/jsonfield/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'index.js',
        ];

        $this->css = [
            'index.css',
        ];

        parent::init();
    }
}
