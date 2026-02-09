<?php
namespace Boogiewoogie\Theme\Block\Header;

use Boogiewoogie\Core\Block\AbstractBlock;
use Boogiewoogie\Core\Field\Color;
use Boogiewoogie\Core\Field\Heading;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Text;

class HeaderSite extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/header-site';
        $this->title       = 'Header';
        $this->description = 'Header block for this site.';
        $this->category    = 'theme-blocks';
        $this->icon        = 'cover-image';
        $this->keywords    = ['header-site', 'Header', 'block'];
    }

    protected function setFields(): void
    {
        $this->fields = [
             ...new Color(label: 'Desktop Background Color', name: 'header_bg_desktop', default: 'pink'),
             ...new Heading(label: 'Header - Desktop Banner Title', name: 'header_desktop_banner_title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'white'),
            Image::make('Sign Up Button Image', 'header_signup_image'),
            Link::make('Sign Up Button Link', 'header_signup_link'),
            Image::make('Mobile Background Image', 'header_mobile_bg_image'),
            Image::make('Desktop Logo', 'header_logo_desktop'),
            Image::make('Mobile Logo', 'header_logo_mobile'),
            Repeater::make('Links', 'header_links')
                ->layout('block')
                ->fields([
                    Link::make('Link', 'link'),
                ]),
        ];
    }

    protected function addToContext(array &$context): void
    {
    }
}
