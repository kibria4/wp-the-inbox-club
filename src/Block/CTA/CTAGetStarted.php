<?php
namespace Boogiewoogie\Theme\Block\CTA;

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

class CTAGetStarted extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/ctaget-started';
        $this->title       = 'CTAGetStarted';
        $this->description = 'CTAGetStarted block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image'; // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['ctaget-started', 'CTA', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
            ...new Heading(label: 'Title', name: 'title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'black'),
            WYSIWYGEditor::make('Content', 'content'),

            Link::make('CTA Link', 'cta_link'),
            Image::make('CTA Image', 'cta_image'),

            Select::make('Margin Bottom Reduction', 'margin_bottom_reduction')
                ->choices([
                    '-mb-[5rem]' => 'Extra Small',
                    '-mb-[7.5rem]' => 'Small',
                    '-mb-[8rem]' => 'Medium',
                    '-mb-[9rem]' => 'Large',
                    '-mb-[10rem]' => 'Extra Large',
                ]),

            Text::make('Block ID', 'block_id'),
        ];
    }

    protected function addToContext(array &$context): void
    {
        // Add extra data for Twig here if needed
    }
}
