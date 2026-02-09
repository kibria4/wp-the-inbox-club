<?php
namespace Boogiewoogie\Theme\Block\Layout;

use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Repeater;
use Boogiewoogie\Core\Field\Heading;
use Extended\ACF\Fields\WYSIWYGEditor;
use Boogiewoogie\Core\Block\AbstractBlock;

class LayoutWrapper extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/layout-wrapper';
        $this->title       = 'LayoutWrapper';
        $this->description = 'LayoutWrapper block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image'; // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['layout-wrapper', 'Layout', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
            ...new Heading(label: 'Small Title', name: 'small_title', defaultLevel: 'h1', defaultStyle: 'h3', defaultTextColor: 'rosewood', defaultBackgroundColor: 'transparent'),
            ...new Heading(label: 'Title', name: 'title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'rosewood', defaultBackgroundColor: 'transparent'),
            WYSIWYGEditor::make('Content', 'content'),
            Link::make('CTA Link', 'cta_link'),

            Text::make('Block ID', 'block_id'),
        ];
    }

    protected function addToContext(array &$context): void
    {}
}
