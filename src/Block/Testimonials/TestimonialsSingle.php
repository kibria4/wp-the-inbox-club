<?php
namespace Boogiewoogie\Theme\Block\Testimonials;

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

class TestimonialsSingle extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/testimonials-single';
        $this->title       = 'Testimonials Single';
        $this->description = 'Testimonials Single block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image'; // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['testimonials-single', 'Testimonials', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
            Image::make('Quote Image', 'quote_image'),
            ...new Heading(label: 'Quote', name: 'quote', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'white'),
            Text::make('Name', 'name'),

            Text::make('Block ID', 'block_id'),
        ];
    }

    protected function addToContext(array &$context): void
    {
        // Add extra data for Twig here if needed
    }
}
