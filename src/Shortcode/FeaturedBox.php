<?php
namespace Boogiewoogie\Theme\Shortcode;

use Boogiewoogie\Core\Shortcode\AbstractShortcode;
use Timber\Timber;

class FeaturedBox extends AbstractShortcode
{
    protected function getTag(): string
    {
        return 'featured_box';
    }

    protected function getDefaultAtts(): array
    {
        return [
            'title' => '',
            'class' => '',
        ];
    }

    protected function handle(array $atts, ?string $content, string $tag): string
    {
        $context = Timber::context();

        $context['atts'] = $atts;
        $context['content'] = $content;

        return Timber::compile('shortcodes/featured-box.twig', $context);
    }
}
