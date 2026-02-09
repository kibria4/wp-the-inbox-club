<?php
namespace Boogiewoogie\Theme\Options;

use Boogiewoogie\Core\Options\AbstractOptionsPage;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Fields\URL;

class SiteOptions extends AbstractOptionsPage
{
    protected function setProperties(): void
    {
        $this->pageTitle  = 'Site Settings';
        $this->menuTitle  = 'Site Settings';
        $this->menuSlug   = 'site-settings';
        $this->capability = 'manage_options';
        $this->position   = 30;
        $this->parentSlug = 'themes.php'; // Places under Appearance
        $this->iconUrl    = 'dashicons-admin-generic';
        $this->redirect   = false;
    }

    protected function setFields(): void
    {
        $this->fields = [
            Tab::make('General Settings')
                ->placement('left'),
            Text::make('Site Name', 'site_name')
                ->helperText('Override the default site name if needed'),
            Text::make('Site Description', 'site_description')
                ->helperText('A brief description of your site'),
            Text::make('Copyright Text', 'copyright_text')
                ->default('&copy; ' . get_bloginfo('name') . ' ' . date('Y') . '. All Rights Reserved.')
                ->helperText('Custom copyright text for the footer'),

            Tab::make('Contact Settings')
                ->placement('left'),
            Text::make('Email Address', 'contact_email')
                ->helperText('Primary contact email address'),
            Text::make('Phone Number', 'contact_phone')
                ->helperText('Primary contact phone number'),
            Textarea::make('Address', 'contact_address')
                ->rows(4)
                ->helperText('Physical address (supports line breaks)'),

            Tab::make('Social Media')
                ->placement('left'),
            URL::make('Facebook URL', 'social_facebook')
                ->helperText('Full URL to your Facebook page'),
            URL::make('Instagram URL', 'social_instagram')
                ->helperText('Full URL to your Instagram profile'),
            URL::make('LinkedIn URL', 'social_linkedin')
                ->helperText('Full URL to your LinkedIn profile'),
            URL::make('Pinterest URL', 'social_pinterest')
                ->helperText('Full URL to your Pinterest profile'),
            URL::make('YouTube URL', 'social_youtube')
                ->helperText('Full URL to your YouTube channel'),
        ];
    }
}