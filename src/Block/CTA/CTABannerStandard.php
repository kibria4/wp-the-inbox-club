<?php
namespace Boogiewoogie\Theme\Block\CTA;

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

class CTABannerStandard extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/ctabanner-standard';
        $this->title       = 'CTABannerStandard';
        $this->description = 'CTABannerStandard block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image'; // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['ctabanner-standard', 'CTA', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
            ...new Heading(label: 'Title', name: 'title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'black'),
            WYSIWYGEditor::make('Content', 'content'),
            Image::make('Image', 'image'),
            Select::make('Width Desktop', 'width_desktop')->choices([
                'w-full' => 'Full',
                'lg:w-[85%]' => 'Wide',
                'lg:w-1/2' => 'Normal',
            ])->default('lg:w-1/2'),

            Image::make('Bottom Border Image', 'bottom_border_image'),

            Text::make('Block ID', 'block_id'),
        ];
    }

    protected function addToContext(array &$context): void
    {
        // Add extra data for Twig here if needed
    }
}
