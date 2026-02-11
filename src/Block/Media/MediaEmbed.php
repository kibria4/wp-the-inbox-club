<?php
namespace Boogiewoogie\Theme\Block\Media;

use Boogiewoogie\Core\Block\AbstractBlock;
use Boogiewoogie\Core\Field\Heading;
use Boogiewoogie\Core\Field\Buttons;
use Boogiewoogie\Core\Field\Color;
use Extended\ACF\Fields\Accordion;
use Extended\ACF\Fields\File;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\WYSIWYGEditor;

class MediaEmbed extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/media-embed';
        $this->title       = 'MediaEmbed';
        $this->description = 'MediaEmbed block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image'; // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['media-embed', 'Media', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
            Image::make('Background Image', 'background_image'),
            ...new Heading(label: 'Title', name: 'title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'black'),
            
            Image::make('Placeholder Image', 'placeholder_image'),
            File::make('Media File', 'media_file'),
            WYSIWYGEditor::make('Embed Content', 'embed_content'),

            Link::make('CTA Link', 'cta_link'),
            Image::make('CTA Image', 'cta_image'),

            ...new Color(label: 'Bottom Banner BG Color', name: 'bottom_banner_bg_color', default: 'purple'),
            WYSIWYGEditor::make('Bottom Banner Content', 'bottom_banner_content'),

            Image::make('Bottom Border Image', 'bottom_border_image'),

            Text::make('Block ID', 'block_id'),
        ];
    }

    protected function addToContext(array &$context): void
    {
        // Add extra data for Twig here if needed
    }
}
