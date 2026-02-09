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
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Fields\WYSIWYGEditor;

class ContentKeyPoints extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/content-key-points';
        $this->title       = 'ContentKeyPoints';
        $this->description = 'ContentKeyPoints block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image'; // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['content-key-points', 'Content', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
            ...new Heading(label: 'Title', name: 'title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'white', defaultBackgroundColor: 'transparent'),

            Repeater::make('Points', 'points')
                ->layout('block')
                ->fields([
                    Select::make('Width Desktop', 'width_desktop')
                        ->choices([
                            'lg:w-1/5' => '20%',
                            'lg:w-2/5' => '40%',
                            'lg:w-1/2' => '50%',
                            'lg:w-full' => 'Full Width',
                        ])
                        ->default('lg:w-2/5'),
                    ...new Color(
                        label: 'Background Color',
                        name: 'background_color',
                        default: 'blue-light',
                    ),
                    ...new Color(
                        label: 'Text Color',
                        name: 'text_color',
                        default: 'blue-dark',
                    ),
                    Textarea::make('Top Text', 'top_text'),
                    Textarea::make('Main Text', 'main_text'),
            ]),

            Text::make('Block ID', 'block_id'),
        ];
    }

    protected function addToContext(array &$context): void
    {
        // Add extra data for Twig here if needed
    }
}
