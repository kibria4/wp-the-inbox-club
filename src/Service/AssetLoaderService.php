<?php

namespace Boogiewoogie\Theme\Service;

use Boogiewoogie\Core\Service\AssetLoaderService as CoreAssetLoaderService;

/**
 * Theme-specific asset loading.
 *
 * - Inherits Vite handling from CoreAssetLoaderService.
 * - Adds auto-registration of Interactivity API script modules from
 *   resources/js/interactivity/*.js
 */
class AssetLoaderService extends CoreAssetLoaderService
{
    /**
     * Front-end enqueue hook.
     *
     * Load Vite and Interactivity API assets, then user theme-specific assets after parent call.
     */
    protected function enqueueFrontendAssets(): void
    {
        // Vite (from core), and interactivity modules
        parent::enqueueFrontendAssets();

        //Add your theme-specific front-end assets here

    }

    /**
     * Block editor enqueue hook.
     * 
     */
    protected function enqueueBlockEditorAssets(): void
    {
        parent::enqueueBlockEditorAssets();

        //Add your theme-specific block editor assets here

    }

    
}