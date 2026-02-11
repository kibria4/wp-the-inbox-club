<?php
namespace Boogiewoogie\Theme\Block\Footer;

use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Repeater;
use Boogiewoogie\Core\Field\Color;
use Boogiewoogie\Core\Field\Heading;
use Extended\ACF\Fields\WYSIWYGEditor;
use Boogiewoogie\Core\Block\AbstractBlock;

class FooterSite extends AbstractBlock
{
    protected function setProperties(): void
    {
        $this->name        = 'acf/footer-site';
        $this->title       = 'Footer Site';
        $this->description = 'Main site footer with navigation, contact, and legal links.';
        $this->category    = 'theme-blocks';
        $this->icon        = 'admin-customizer';
        $this->keywords    = ['footer', 'site', 'navigation', 'legal'];
    }

    protected function setFields(): void
    {
        $this->fields = [
            Image::make('Top Image Banner', 'footer_top_image_banner'),
            Image::make('Background Image', 'footer_background_image'),
            Image::make('Background Image Arrows', 'footer_background_image_arrows'),
            ...new Heading(label: 'Title', name: 'footer_title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'white'),
            Repeater::make('Links', 'footer_links')
                ->layout('block')
                ->fields([
                    Link::make('Link URL', 'link'),
                    Image::make('Link Icon', 'icon'),
                ]),
            Repeater::make('Social Links', 'footer_social_links')
                ->layout('block')
                ->fields([
                    Link::make('Link URL', 'link'),
                    Image::make('Link Icon', 'icon'),
                ]),
            ...new Color(label: 'Form BG Colour', name: 'footer_form_bg_color', default: 'white'),
            ...new Heading(label: 'Form Heading', name: 'footer_form_heading', defaultLevel: 'h3', defaultStyle: 'h3', defaultTextColor: 'black'),
            WYSIWYGEditor::make('Form Content', 'footer_form_content'),
            ...new Color(label: 'Bottom Bar BG Colour', name: 'footer_bottom_bar_bg_color', default: 'purple-dark'),

            Text::make('Block ID', 'block_id'),
        ];
    }

    protected function addToContext(array &$context): void
    {
        // Get site settings from ACF options page
        $context['site_settings'] = [
            'site_name'        => get_field('site_name', 'option') ?: get_bloginfo('name'),
            'site_description' => get_field('site_description', 'option') ?: get_bloginfo('description'),
            'copyright_text'   => get_field('copyright_text', 'option'),
            'contact_email'    => get_field('contact_email', 'option'),
            'contact_phone'    => get_field('contact_phone', 'option'),
            'contact_address'  => get_field('contact_address', 'option'),
            'social_facebook'  => get_field('social_facebook', 'option'),
            'social_instagram' => get_field('social_instagram', 'option'),
            'social_linkedin'  => get_field('social_linkedin', 'option'),
            'social_pinterest' => get_field('social_pinterest', 'option'),
            'social_youtube'   => get_field('social_youtube', 'option'),
        ];

        // Theme assets directory
        $context['theme_uri'] = get_template_directory_uri();
    }
}
