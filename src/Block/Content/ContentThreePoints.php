<?php
namespace Boogiewoogie\Theme\Block\Content;

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
use Extended\ACF\Fields\WYSIWYGEditor;

class ContentThreePoints extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/content-three-points';
        $this->title       = 'ContentThreePoints';
        $this->description = 'ContentThreePoints block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image'; // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['content-three-points', 'Content', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
            Image::make('Background Image', 'background_image'),
            ...new Heading(label: 'Title', name: 'title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'white', defaultBackgroundColor: 'transparent'),
            // WYSIWYGEditor::make('Content', 'content'),
            
            ...new Color(label: 'Inner Box BG Colour', name: 'inner_box_bg_color', default: 'blue-mid'),
            ...new Color(label: 'Inner Box Text Colour', name: 'inner_box_text_color', default: 'white'),
            ...new Heading(label: 'Inner Box Title', name: 'inner_box_title', defaultLevel: 'h3', defaultStyle: 'h3', defaultTextColor: 'white', defaultBackgroundColor: 'transparent'),
            
            ...new Color(label: 'Point BG Colour', name: 'point_bg_color', default: 'purple-dark'),
            ...new Color(label: 'Point Text Colour', name: 'point_text_color', default: 'white'),

            Select::make('Grid Columns Desktop', 'grid_columns_desktop')
                ->choices([
                    'lg:grid-cols-1' => '1 Column',
                    'lg:grid-cols-2' => '2 Columns',
                    'lg:grid-cols-3' => '3 Columns',
                    'lg:grid-cols-4' => '4 Columns',
                ])
                ->default('lg:grid-cols-3'),


            Repeater::make('Points', 'points')
                ->layout('block')
                ->fields([
                    Text::make('Content', 'content')
                        ->helperText('Add a description for this point'),
            ]),

            WYSIWYGEditor::make('Inner Box Summary Content', 'inner_box_summary_content'),
            Link::make('Inner Box Mail Link', 'inner_box_mail_link'),
            Image::make('Inner Box Mail Image', 'inner_box_mail_image'),
            
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
