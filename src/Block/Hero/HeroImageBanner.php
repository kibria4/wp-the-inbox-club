<?php
namespace Boogiewoogie\Theme\Block\Hero;

use Boogiewoogie\Core\Block\AbstractBlock;
use Boogiewoogie\Core\Field\Heading;
use Boogiewoogie\Core\Field\Buttons;
use Boogiewoogie\Core\Field\Color;
use Extended\ACF\Fields\Accordion;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\WYSIWYGEditor;

class HeroImageBanner extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/hero-image-banner';
        $this->title       = 'HeroImageBanner';
        $this->description = 'HeroImageBanner block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image'; // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['hero-image-banner', 'Hero', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
            Image::make('Image', 'image'),

            ...new Color(label: 'Banner BG Colour', name: 'banner_bg_color', default: 'blue-mid'),
            ...new Heading(label: 'Banner Title', name: 'title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'black'),

            Link::make('CTA Link', 'cta_link'),
            Image::make('CTA Image', 'cta_image'),

            Image::make('Bottom Border Image', 'bottom_border_image'),

            Text::make('Block ID', 'block_id'),
        ];
    }

    protected function addToContext(array &$context): void
    {
        // Add extra data for Twig here if needed
    }
}
