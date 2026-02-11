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

class HeroOptions extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/hero-options';
        $this->title       = 'HeroOptions';
        $this->description = 'HeroOptions block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image'; // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['hero-options', 'Hero', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
            Image::make('Background Image', 'background_image'),
            ...new Heading(label: 'Title', name: 'title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'white'),
            
            Repeater::make('Options', 'options')
            ->layout('block')
            ->fields([
                Image::make('Background Image', 'background_image'),
                Text::make('Title', 'title'),
                Link::make('Link', 'link'),
                ]),
                
                Link::make('CTA Link', 'cta_link'),
                Image::make('CTA Image', 'cta_image'),
                
                ...new Color(label: 'Bottom Banner BG Color', name: 'bottom_banner_bg_color', default: 'purple'),
                ...new Heading(label: 'Bottom Banner Title', name: 'bottom_banner_title', defaultLevel: 'h3', defaultStyle: 'h3', defaultTextColor: 'white'),
            WYSIWYGEditor::make('Bottom Banner Content', 'bottom_banner_content'),

            Image::make('Bottom Border Image', 'bottom_border_image'),

            Text::make('Block ID', 'block_id'),
        ];
    }

    protected function addToContext(array &$context): void
    {
        // Add extra data for Twig here if needed
    }
}
