<?php
namespace Boogiewoogie\Theme\Block\Content;

use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Select;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Textarea;
use Boogiewoogie\Core\Field\Color;
use Extended\ACF\Fields\Accordion;
use Boogiewoogie\Core\Field\Buttons;
use Boogiewoogie\Core\Field\Heading;
use Extended\ACF\Fields\WYSIWYGEditor;
use Boogiewoogie\Core\Block\AbstractBlock;

class ContentSteps extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/content-steps';
        $this->title       = 'ContentSteps';
        $this->description = 'ContentSteps block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image'; // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['content-steps', 'Content', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
            ...new Heading(label: 'Title', name: 'title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'black'),
            
            Select::make('Grid Columns Desktop', 'grid_columns_desktop')
                ->choices([
                    'lg:grid-cols-1' => '1 Column',
                    'lg:grid-cols-2' => '2 Columns',
                    'lg:grid-cols-3' => '3 Columns',
                    'lg:grid-cols-4' => '4 Columns',
                ])
                ->default('lg:grid-cols-3'),
                
            Repeater::make('Steps', 'steps')
                ->layout('block')
                ->fields([
                    Text::make('Step', 'step'),
                    ...new Color(label: 'Step Color', name: 'step_color', default: 'pink'),

                    Text::make('Top Title', 'top_title')
                        ->default('The'),
                    Text::make('Title', 'title'),
                    ...new Color(label: 'Title Color', name: 'title_color', default: 'white'),
                    ...new Color(label: 'Background Color', name: 'background_color', default: 'blue-mid'),

                    Image::make('Image', 'image'),
                    Textarea::make('Description', 'description'),
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
