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

class HeroStandard1 extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/hero-standard1';
        $this->title       = 'HeroStandard1';
        $this->description = 'HeroStandard1 block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image'; // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['hero-standard1', 'Hero', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
            ...new Heading(label: 'Title', name: 'title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'black'),
            ...new Color(label: 'Title Highlight Text Color', name: 'title_highlight_text_color', default: 'blue-light'),
            
            ...new Color(label: 'Overlay BG Color', name: 'overlay_bg_color', default: 'white'),
            Image::make('Image', 'image'),
            WYSIWYGEditor::make('Overlay Content', 'overlay_content'),
            Image::make('Overlay Divider', 'overlay_divider'),

            Image::make('Bottom Border Image', 'bottom_border_image'),

            Text::make('Block ID', 'block_id'),
        ];
    }

    protected function addToContext(array &$context): void
    {
        // Add extra data for Twig here if needed
    }
}
