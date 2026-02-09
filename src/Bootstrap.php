<?php
namespace Boogiewoogie\Theme;

use Dotenv\Dotenv;
use Symfony\Component\Finder\Finder;
use Boogiewoogie\Core\Config\CoreFeatures;
use Boogiewoogie\Theme\Options\SiteOptions;
use Boogiewoogie\Core\Service\SecurityService;
use Boogiewoogie\Core\Service\ContainerFactory;
use Boogiewoogie\Core\PostType\AbstractPostType;
use Boogiewoogie\Core\Taxonomy\AbstractTaxonomy;
use Boogiewoogie\Core\Service\OptimisationService;
use Boogiewoogie\Core\Shortcode\AbstractShortcode;
use Boogiewoogie\Theme\Service\AssetLoaderService;
use Boogiewoogie\Core\Service\BlockRegistrarService;
use Boogiewoogie\Theme\Service\GdprAdminPageService;
use Boogiewoogie\Core\Service\JsonBlockLoaderService;
// use Boogiewoogie\Core\Service\GdprAnonymiserService; // for later
use Boogiewoogie\Core\Service\ButtonBlockStylesRegistrar;
use Boogiewoogie\Performance\Service\PerformanceBootstrap;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Bootstrap
{
    public static function init(): self
    {
        $instance = new self();
        $instance->run();
        return $instance;
    }

    private function run(): void
    {
        add_action('after_setup_theme', [$this, 'setup']);

        if (version_compare(get_bloginfo('version'), '5.8', '>=')) {
            add_filter('block_categories_all', [$this, 'register_layout_category'], 10, 2);
        } else {
            add_filter('block_categories', [$this, 'register_layout_category'], 10, 2);
        }
    }

    public function setup(): void
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');

        // Tell Timber where templates live
        if (class_exists(\Timber\Timber::class)) {
            \Timber\Timber::$dirname = ['templates'];
        }

        // WP-CLI command: wp bw make:block ...
        if (defined('WP_CLI') && WP_CLI) {
            \WP_CLI::add_command(
                'bw',
                \Boogiewoogie\Core\CLI\MakeBlockCommand::class
            );

            // GDPR anonymisation command:
            \WP_CLI::add_command(
                'bw-gdpr',
                \Boogiewoogie\Core\CLI\GdprAnonymiseUserCommand::class
            );
        }

        /**
         * Load .env.local and .env (theme-level environment)
         */
        $themeDir = get_stylesheet_directory();

        if (file_exists($themeDir . '/.env.local')) {
            $dotenv = Dotenv::createImmutable($themeDir, '.env.local');
            $dotenv->load();
        }

        if (file_exists($themeDir . '/.env')) {
            $dotenv = Dotenv::createImmutable($themeDir, '.env');
            $dotenv->safeLoad(); // Doesn't overwrite .env.local values
        }

        if (! isset($_ENV['WP_ENV'])) {
            $_ENV['WP_ENV'] = 'prod'; // default fallback
        }

        $themeDir = get_stylesheet_directory();

        /** @var ContainerBuilder $container */
        $container = ContainerFactory::create(
            [$themeDir . '/config'], // extraConfigDirs
            $themeDir                // projectRoot (for vendor/boogiewoogie)
        );

        $container->compile();

        // 🔥 Wire container into AbstractBlock so renderCallback can use DI
        if (\class_exists(\Boogiewoogie\Core\Block\AbstractBlock::class)) {
            \Boogiewoogie\Core\Block\AbstractBlock::setContainer($container);
        }

        /**
         * 🔌 Add custom Twig extensions from the container
         */
        if (class_exists(\Timber\Timber::class)) {
            add_filter('timber/twig', function ($twig) use ($container) {
                // Image hint extension from boogiewoogie-performance
                if ($container->has(\Boogiewoogie\Performance\Twig\ImageHintExtension::class)) {
                    $twig->addExtension(
                        $container->get(\Boogiewoogie\Performance\Twig\ImageHintExtension::class)
                    );
                }

                // If you add more Twig extensions later, wire them here in the same way.

                return $twig;
            });
        }

        // Assets
        /** @var AssetLoaderService $assetLoader */
        $assetLoader = $container->get(AssetLoaderService::class);
        $assetLoader->initialize();

        // Blocks from PHP classes (ACF Extended)
        /** @var BlockRegistrarService $blockRegistrar */
        $blockRegistrar = $container->get(BlockRegistrarService::class);
        $blockRegistrar->registerBlocks(); // already dynamic

        // JSON-defined blocks (block.json in /config/blocks)
        /** @var JsonBlockLoaderService $jsonLoader */
        $jsonLoader = $container->get(JsonBlockLoaderService::class);
        add_action('init', [$jsonLoader, 'registerFromConfig']);

        // 🔥 Security & optimisation
        if (CoreFeatures::isEnabled(CoreFeatures::SECURITY)) {
            /** @var \Boogiewoogie\Core\Service\SecurityService $security */
            $security = $container->get(\Boogiewoogie\Core\Service\SecurityService::class);
            $security->register();
        }

        if (CoreFeatures::isEnabled(CoreFeatures::OPTIMISATION) && $container->has(OptimisationService::class)) {
            // if ($container->has(OptimisationService::class)) {
            /** @var OptimisationService $optimisation */
            $optimisation = $container->get(OptimisationService::class);
            $optimisation->applyOptimizations();
            // }
        }

        if (CoreFeatures::isEnabled(CoreFeatures::HEALTH_CHECK) && $container->has(\Boogiewoogie\Core\Service\HealthCheckService::class)) {
            /** @var \Boogiewoogie\Core\Service\HealthCheckService $healthCheck */
            $healthCheck = $container->get(\Boogiewoogie\Core\Service\HealthCheckService::class);
        }

        if (CoreFeatures::isEnabled(CoreFeatures::GDPR) && $container->has(GdprAdminPageService::class)) {
            // GDPR admin tools page (manual anonymisation + log viewer)
            // if ($container->has(GdprAdminPageService::class)) {
            /** @var GdprAdminPageService $gdprAdmin */
            $gdprAdmin = $container->get(GdprAdminPageService::class);
            $gdprAdmin->register();
            // }
        }

        if (CoreFeatures::isEnabled(CoreFeatures::GDPR) && $container->has(\Boogiewoogie\Theme\Service\GdprScrubberService::class)) {
            // GDPR scrubber service: attaches its hooks to anonymisation.
            // if ($container->has(\Boogiewoogie\Theme\Service\GdprScrubberService::class)) {
            /** @var \Boogiewoogie\Theme\Service\GdprScrubberService $scrubber */
            $scrubber = $container->get(\Boogiewoogie\Theme\Service\GdprScrubberService::class);
            $scrubber->registerHooks();
            // }
        }

        /**
         * Auto-boot any service tagged as "boogiewoogie.bootable".
         *
         * This allows packages like boogiewoogie-cache to register
         * their own WP hooks (admin pages, front-end hooks, etc.)
         * without hard-coding them here.
         */
        if (method_exists($container, 'findTaggedServiceIds')) {
            foreach ($container->findTaggedServiceIds('boogiewoogie.bootable') as $id => $tags) {
                $service = $container->get($id);

                if (method_exists($service, 'register')) {
                    $service->register();
                }
            }
        }

        // GDPR anonymiser – no automatic boot yet, wire this via explicit calls later
        // if ($container->has(GdprAnonymiserService::class)) {
        //     $gdpr = $container->get(GdprAnonymiserService::class);
        //     // No boot() for now – we’ll call $gdpr->anonymiseUser(...) from admin tools later.
        // }

        // Load button block styles:
        $buttonStyleRegistrar = $container->get(ButtonBlockStylesRegistrar::class);
        $buttonStyleRegistrar->register();

        // performance optimisations
        if ($container->has(PerformanceBootstrap::class)) {
            $perfBootstrap = $container->get(PerformanceBootstrap::class);
            $perfBootstrap->register();
        } else {
            // Avoid frontend fatal if the performance package isn't loaded/installed.
            \error_log('boogiewoogie/performance not available in container; skipping performance bootstrap.');
        }

        // 🔥 Auto-register CPTs & taxonomies based on PSR-4 folders
        $this->autoRegisterPostTypes();
        $this->autoRegisterTaxonomies();
        $this->autoRegisterShortcodes();
        $this->registerOptionsPages();
        // (Blocks are already handled by BlockRegistrarService dynamically)
    }

    public function registerOptionsPages(): void
    {
        new SiteOptions();
    }

    /**
     * Register custom theme block category
     */
    public function register_layout_category($categories, $post)
    {
        $custom_category = [
            [
                'slug'  => 'theme-blocks',
                'title' => 'Theme Blocks',
            ],
        ];

        // Merge custom category at beginning of existing categories
        return array_merge($custom_category, $categories);
    }

    /**
     * Auto-discover and instantiate all PostType classes
     * under src/PostType that extend AbstractPostType.
     */
    protected function autoRegisterPostTypes(): void
    {
        $themeDir      = get_stylesheet_directory();
        $baseDir       = $themeDir . '/src/PostType';
        $baseNamespace = 'Boogiewoogie\\Theme\\PostType';

        if (! is_dir($baseDir)) {
            return;
        }

        $finder = new Finder();
        $finder->files()->in($baseDir)->name('*.php');

        foreach ($finder as $file) {
            $relativePath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $file->getRealPath());
            $relativePath = str_replace('.php', '', $relativePath);

            // e.g. "CaseStudy/CaseStudy" → "Boogiewoogie\Theme\PostType\CaseStudy\CaseStudy"
            $class = $baseNamespace . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);

            if (! class_exists($class)) {
                continue;
            }

            if (! is_subclass_of($class, AbstractPostType::class)) {
                continue;
            }

            $ref = new \ReflectionClass($class);
            if ($ref->isAbstract()) {
                continue;
            }

            // Constructor should hook into init → register_post_type
            new $class();
        }
    }

    /**
     * Auto-discover and instantiate all Taxonomy classes
     * under src/Taxonomy that extend AbstractTaxonomy.
     */
    protected function autoRegisterTaxonomies(): void
    {
        $themeDir      = get_stylesheet_directory();
        $baseDir       = $themeDir . '/src/Taxonomy';
        $baseNamespace = 'Boogiewoogie\\Theme\\Taxonomy';

        if (! is_dir($baseDir)) {
            return;
        }

        $finder = new Finder();
        $finder->files()->in($baseDir)->name('*.php');

        foreach ($finder as $file) {
            $relativePath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $file->getRealPath());
            $relativePath = str_replace('.php', '', $relativePath);

            $class = $baseNamespace . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);

            if (! class_exists($class)) {
                continue;
            }

            if (! is_subclass_of($class, AbstractTaxonomy::class)) {
                continue;
            }

            $ref = new \ReflectionClass($class);
            if ($ref->isAbstract()) {
                continue;
            }

            // Constructor should hook into init → register_taxonomy
            new $class();
        }
    }

    /**
     * Auto-discover and instantiate all Shortcode classes
     * under src/Shortcode that extend AbstractShortcode.
     */
    protected function autoRegisterShortcodes(): void
    {
        $themeDir      = get_stylesheet_directory();
        $baseDir       = $themeDir . '/src/Shortcode';
        $baseNamespace = 'Boogiewoogie\\Theme\\Shortcode';

        if (! is_dir($baseDir)) {
            return;
        }

        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->in($baseDir)->name('*.php');

        foreach ($finder as $file) {
            $relativePath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $file->getRealPath());
            $relativePath = str_replace('.php', '', $relativePath);

            $class = $baseNamespace . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);

            if (! class_exists($class)) {
                continue;
            }

            if (! is_subclass_of($class, AbstractShortcode::class)) {
                continue;
            }

            $ref = new \ReflectionClass($class);
            if ($ref->isAbstract()) {
                continue;
            }

            // Constructor hooks into init → add_shortcode()
            new $class();
        }
    }

    /**
     * OPTIONAL: If you ever decide to drop BlockRegistrarService
     * and rely purely on filesystem scanning, you can uncomment this
     * and remove BlockRegistrarService from setup().
     */
    /*
    protected function autoRegisterBlocks(): void
    {
        $themeDir      = get_stylesheet_directory();
        $baseDir       = $themeDir . '/src/Block';
        $baseNamespace = 'Boogiewoogie\\Theme\\Block';

        if (!is_dir($baseDir)) {
            return;
        }

        $finder = new Finder();
        $finder->files()->in($baseDir)->name('*.php');

        foreach ($finder as $file) {
            $relativePath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $file->getRealPath());
            $relativePath = str_replace('.php', '', $relativePath);

            $class = $baseNamespace . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);

            if (!class_exists($class)) {
                continue;
            }

            if (!is_subclass_of($class, AbstractBlock::class)) {
                continue;
            }

            $ref = new \ReflectionClass($class);
            if ($ref->isAbstract()) {
                continue;
            }

            new $class(); // ctor hooks into acf/init to register fields
        }
    }
    */
}
