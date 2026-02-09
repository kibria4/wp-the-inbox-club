<?php
namespace Boogiewoogie\Theme\Block\Accordion;

use Boogiewoogie\Core\Block\AbstractBlock;
use Boogiewoogie\Core\Field\Heading;
use Boogiewoogie\Core\Field\Buttons;
use Boogiewoogie\Core\Field\Color;
use Extended\ACF\Fields\Accordion;
use Extended\ACF\Fields\Message;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\WYSIWYGEditor;

class AccordionFaqs extends AbstractBlock
{
    protected function setProperties(): void
    {
        // Must match block.json "name"
        $this->name        = 'acf/accordion-faqs';
        $this->title       = 'AccordionFaqs';
        $this->description = 'AccordionFaqs block.';
        $this->category    = 'theme-blocks'; // [ theme-blocks | common | formatting | layout | widgets | embed ]
        $this->icon        = 'cover-image'; // https://developer.wordpress.org/resource/dashicons/
        $this->keywords    = ['accordion-faqs', 'Accordion', 'block'];
    }

    protected function setFields(): void
    {
        // Adjust fields per block after generation
        $this->fields = [
            ...new Heading(label: 'Title', name: 'title', defaultLevel: 'h2', defaultStyle: 'h2', defaultTextColor: 'pink', defaultBackgroundColor: 'transparent'),
            
            ...new Color(label: 'Question Background Color', name: 'question_bg_color', default: 'blue-light'),
            Image::make('Question Arrow Image', 'question_arrow_image'),
            
            ...new Heading(label: 'Summary Title', name: 'summary_title', defaultLevel: 'h3', defaultStyle: 'h3', defaultTextColor: 'pink', defaultBackgroundColor: 'transparent'),
            Image::make('Summary CTA Image', 'summary_cta_image'),
            Link::make('Summary CTA Link', 'summary_cta_link'),

            Repeater::make('FAQs', 'faqs')
                ->layout('block')
                ->fields([
                    Text::make('Question', 'question')
                        ->helperText('Add the question for this FAQ item'),
                    WYSIWYGEditor::make('Answer', 'answer')
                        ->helperText('Add the answer for this FAQ item'),
            ]),

            Text::make('Block ID', 'block_id'),
        ];
    }

    protected function addToContext(array &$context): void
    {
        // Add extra data for Twig here if needed
    }
}
