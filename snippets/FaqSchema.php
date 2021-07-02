<?php

class FaqSchema extends GenericSchema
{
    public function mustRenderOnPage()
    {
        global $post;

        return $post !== null && Markapp_Schema_Matcher::isFAQPage($post);
    }

    public function schema()
    {
        $faqArr = [];

        if( have_rows('schema_markapp_faq_questions') ) {
            $mainEntity = array();
            while( have_rows('schema_markapp_faq_questions') ) {
                the_row();
                $question = get_sub_field('schema_markapp_faq_question');
                $answer = get_sub_field('schema_markapp_faq_answer');
                if (!$question || !$answer) continue;
                $faqArr[] = [
                    "@type" => "Question",
                    "name" => $question,
                    "acceptedAnswer" => [
                        "@type" => "Answer",
                        "text" => $answer
                    ]
                ];
            }

        }

        if (sizeof($faqArr) == 0)
            return null;
        $data["@context"] = "http://schema.org";
        $data["@type"] = "CreativeWork";
        $data["@id"] = "schema:CreativeWork";
        $data["mainEntity"] = [[
            "@type" => "FAQPage",
            "@id" => home_url(add_query_arg(null, null)) . "/FAQPage",
            "inLanguage" => array(
                "@type" => "Language",
                "name" => get_field('sm_FAQinlanguage')
            ),
            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => home_url(add_query_arg(null, null))],
            "image" => array(
                "@type" => "ImageObject",
                "url" => get_the_post_thumbnail_url()
            ),
            "headline" => get_field('sm_FAQHeadline'),
            "keywords" => get_field('sm_FAQkeywords'),
            "mentions" => get_field('sm_FAQmentions'),
            "author" => get_field('sm_FAQauthor'),
            "creator" => get_field('sm_FAQcreator'),
            "mainEntity" => [$faqArr],
        ]];

        return $data;
    }

    public function postfields()
    {
        return new FaqPostFields();
    }
}