<?php
namespace Boogiewoogie\Theme\Block\Content;

use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Select;
use Extended\ACF\Fields\Repeater;
use Boogiewoogie\Core\Field\Color;
use Extended\ACF\Fields\Accordion;
use Boogiewoogie\Core\Field\Buttons;
use Boogiewoogie\Core\Field\Heading;
use Extended\ACF\Fields\WYSIWYGEditor;
use Boogiewoogie\Core\Block\AbstractBlock;

class ContentResults extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/content-results';
        $this->title       = 'ContentResults';
        $this->description = 'ContentResults block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image'; // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['content-results', 'Content', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
            Image::make('Background Image', 'background_image'),
            ...new Heading(label: 'Title', name: 'title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'white'),
            WYSIWYGEditor::make('Content', 'content'),
            Image::make('Separator Image', 'separator_image'),
            ...new Heading(label: 'Subtitle', name: 'subtitle', defaultLevel: 'h3', defaultStyle: 'h3', defaultTextColor: 'white'),
            Repeater::make('Images', 'images')
                ->layout('block')
                ->fields([
                    Image::make('Image', 'image'),
                    Select::make('Image Size', 'image_size')
                        ->choices([
                            'lg:col-span-2' => 'Wide',
                            'lg:col-span-1' => 'Small',
                        ])
                        ->default('lg:col-span-1'),
            ]),

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
