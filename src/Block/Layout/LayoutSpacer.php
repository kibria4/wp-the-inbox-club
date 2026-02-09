<?php
namespace Boogiewoogie\Theme\Block\Layout;

use Extended\ACF\Fields\Select;
use Boogiewoogie\Core\Field\Color;
use Boogiewoogie\Core\Block\AbstractBlock;

class LayoutSpacer extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/layout-spacer';
        $this->title       = 'LayoutSpacer';
        $this->description = 'LayoutSpacer block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image';  // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['layout-spacer', 'Layout', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
             ...new Color(label: 'Background Colour', name: 'background_color', default: 'white'),
            Select::make('Space for Large Screens', 'lg_space')
                ->choices([
                    'lg:h-[0px]'   => '0px',
                    'lg:h-[25px]'  => '25px',
                    'lg:h-[50px]'  => '50px',
                    'lg:h-[75px]'  => '75px',
                    'lg:h-[100px]' => '100px',
                    'lg:h-[125px]' => '125px',
                    'lg:h-[150px]' => '150px',
                    'lg:h-[175px]' => '175px',
                    'lg:h-[200px]' => '200px',
                    'lg:h-[250px]' => '250px',
                    'lg:h-[300px]' => '300px',
                ])
                ->default('lg:h-[100px]')
                ->helperText('Space for large screens'),
            Select::make('Space for Small Screens', 'sm_space')
                ->choices([
                    'h-[0px]'   => '0px',
                    'h-[25px]'  => '25px',
                    'h-[50px]'  => '50px',
                    'h-[75px]'  => '75px',
                    'h-[100px]' => '100px',
                    'h-[125px]' => '125px',
                    'h-[150px]' => '150px',
                    'h-[175px]' => '175px',
                    'h-[200px]' => '200px',
                    'h-[250px]' => '250px',
                    'h-[300px]' => '300px',
                ])
                ->default('h-[50px]')
                ->helperText('Space for small screens'),
        ];
    }

    protected function addToContext(array &$context): void
    {}
}
