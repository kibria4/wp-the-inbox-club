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

class HeroFullscreen extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/hero-fullscreen';
        $this->title       = 'HeroFullscreen';
        $this->description = 'HeroFullscreen block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image'; // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['hero-fullscreen', 'Hero', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
            Image::make('Background Image', 'background_image')
                ->required()
                ->previewSize('large'),
            ... new Heading(label: 'Small Title', name: 'small_title', defaultLevel: 'h5', defaultTextColor: 'sand', defaultBackgroundColor: 'transparent'),
            ... new Heading(label: 'Title', name: 'title', defaultLevel: 'h1', defaultTextColor: 'sand', defaultBackgroundColor: 'transparent'),
            ... new Heading(label: 'Subtitle', name: 'subtitle', defaultLevel: 'h4', defaultTextColor: 'sand', defaultBackgroundColor: 'transparent'),
            WYSIWYGEditor::make('Description', 'description'),

            Text::make('Block ID', 'block_id'),
        ];
    }

    protected function addToContext(array &$context): void
    {
        // Add extra data for Twig here if needed
    }
}
