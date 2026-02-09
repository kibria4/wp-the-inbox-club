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

class HeroImageOverlay extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/hero-image-overlay';
        $this->title       = 'HeroImageOverlay';
        $this->description = 'HeroImageOverlay block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image'; // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['hero-image-overlay', 'Hero', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
            Image::make('Background Image', 'background_image'),
            ...new Heading(label: 'Title', name: 'title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'white', defaultBackgroundColor: 'transparent'),
            ...new Heading(label: 'Subtitle', name: 'subtitle', defaultLevel: 'h3', defaultStyle: 'h3', defaultTextColor: 'blue-light', defaultBackgroundColor: 'transparent'),
            WYSIWYGEditor::make('Content', 'content'),
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
