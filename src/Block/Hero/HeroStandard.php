<?php
namespace Boogiewoogie\Theme\Block\Hero;

use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Repeater;
use Boogiewoogie\Core\Field\Color;
use Extended\ACF\Fields\Accordion;
use Extended\ACF\Fields\TrueFalse;
use Boogiewoogie\Core\Field\Buttons;
use Boogiewoogie\Core\Field\Heading;
use Extended\ACF\Fields\WYSIWYGEditor;
use Boogiewoogie\Core\Block\AbstractBlock;

class HeroStandard extends AbstractBlock
{
    protected function setProperties(): void
    {
        $this->name        = 'acf/hero-standard';
        $this->title       = 'Hero Standard';
        $this->description = 'Hero Standard block with full-width background image and centered overlay.';
        $this->category    = 'theme-blocks';
        $this->icon        = 'cover-image';
        $this->keywords    = ['hero', 'hero-standard', 'block'];
    }

    protected function setFields(): void
    {
        $this->fields = [
            Image::make('Image', 'image'),
            ... new Heading(
                label: 'Small Title', 
                name: 'small_title', 
                defaultLevel: 'h5', 
                defaultTextColor: 'sand', 
                defaultBackgroundColor: 'transparent'
            ),
            ... new Heading(
                label: 'Title', 
                name: 'title', 
                defaultLevel: 'h1', 
                defaultTextColor: 'sand', 
                defaultBackgroundColor: 'transparent'
            ),
            ... new Heading(
                label: 'Subtitle', 
                name: 'subtitle', 
                defaultLevel: 'h4', 
                defaultTextColor: 'sand', 
                defaultBackgroundColor: 'transparent'
            ),
            WYSIWYGEditor::make('Description', 'description'),

            Text::make('Block ID', 'block_id'),
        ];
    }

    protected function addToContext(array &$context): void
    {
    }
    
}