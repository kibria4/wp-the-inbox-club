<?php
namespace Boogiewoogie\Theme\Block\Testimonials;

use Boogiewoogie\Core\Block\AbstractBlock;
use Boogiewoogie\Core\Field\Heading;
use Boogiewoogie\Core\Field\Buttons;
use Boogiewoogie\Core\Field\Color;
use Extended\ACF\Fields\Accordion;
use Extended\ACF\Fields\Select;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Fields\WYSIWYGEditor;

class TestimonialsGrid extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/testimonials-grid';
        $this->title       = 'TestimonialsGrid';
        $this->description = 'TestimonialsGrid block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image'; // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['testimonials-grid', 'Testimonials', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
            ...new Heading(label: 'Title', name: 'title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'white', defaultBackgroundColor: 'transparent'),
            ...new Color(
                label: 'Quote Background Color',
                name: 'quote_background_color',
                default: 'purple',
            ),
            Image::make('Quote Image', 'quote_image'),

            Select::make('Grid Columns Desktop', 'grid_columns_desktop')
                ->choices([
                    'lg:grid-cols-2' => '2 Columns',
                    'lg:grid-cols-3' => '3 Columns',
                    'lg:grid-cols-4' => '4 Columns',
                ])
                ->default('lg:grid-cols-3'),

            Repeater::make('Quotes', 'quotes')
                ->layout('block')
                ->fields([
                    Image::make('Photo', 'photo'),
                    Text::make('Name', 'name'),
                    Text::make('Role', 'role'),
                    Textarea::make('Quote', 'quote')
                        ->helperText('Add a description for this point'),
            ]),
            Image::make('Border Bottom Image', 'border_bottom_image'),

            Text::make('Block ID', 'block_id'),
        ];
    }

    protected function addToContext(array &$context): void
    {
        // Add extra data for Twig here if needed
    }
}
