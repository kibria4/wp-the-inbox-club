<?php
namespace Boogiewoogie\Theme\Block\Content;

use Boogiewoogie\Core\Block\AbstractBlock;
use Boogiewoogie\Core\Field\Heading;
use Boogiewoogie\Core\Field\Buttons;
use Boogiewoogie\Core\Field\Color;
use Extended\ACF\Fields\Accordion;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Fields\WYSIWYGEditor;

class ContentWhyEmail extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/content-why-email';
        $this->title       = 'ContentWhyEmail';
        $this->description = 'ContentWhyEmail block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image'; // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['content-why-email', 'Content', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
            Image::make('Background Image', 'background_image'),
            ...new Heading(label: 'Title', name: 'title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'white'),
            Image::make('Banner Title Image', 'banner_title_image'),
            
            Repeater::make('Points', 'points')
                ->layout('block')
                ->fields([
                    ...new Color(
                        label: 'Background Color',
                        name: 'background_color',
                        default: 'blue-light',
                    ),
                    Text::make('Title', 'title'),
                    Textarea::make('Content', 'content')
                        ->helperText('Add a description for this point'),
            ]),

            ...new Heading(label: 'Steps Title', name: 'steps_title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'white'),
            ...new Color(
                label: 'Steps Border Color',
                name: 'steps_border_color',
                default: 'pink',
            ),
            Repeater::make('Steps', 'steps')
                ->layout('block')
                ->fields([
                    Image::make('Icon', 'icon'),
                    Textarea::make('Content', 'content'),
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
