<?php
class WebPageSchema extends GenericSchema
{
    use NavigationTrait;

    public function mustRenderOnPage()
    {
        global $post;

        return $post !== null && (Markapp_Schema_Matcher::isSubPage($post) || Markapp_Schema_Matcher::isFrontPage($post)) || Markapp_Schema_Matcher::isAuthor($post) || Markapp_Schema_Matcher::isPublisher($post);
    }

    public function schema()
    {
        global $my_seo_settings_options;
        global $post;
        global $my_seo_settings_author_options;
        global $authors;
        global $my_seo_settings_about_page_options;
        global $my_seo_settings_article_options;

        
        $ancestors = ma_get_page_ancestors($post);
        $breadcrumbs = [];
        foreach ($ancestors as $i => $p) {
            // Docs: https://developers.google.com/search/docs/data-types/breadcrumb
            $breadcrumbs[] = [
                "@type" => "ListItem",
                "position" => ($i + 1),
                "@id" => "schema:ListItem",
                "name" => get_the_title($p),
                "item" => get_permalink($p)
                ];
        }
        $options = GeneralSettings::instance()->getOptions();
        $publisher = PublisherSettings::instance()->getOptions();
        

        $data = [];
        $data["@context"] = "http://schema.org";
        $data["hasPart"] = [
        "@type" => "WebPageElement",
        "@id" => "schema:WebPageElement"];
        $data["@type"] = "WebPage";
        $data["@id"] = "schema:WebPage";
        $data["url"] = home_url(add_query_arg(null, null));
        $data["name"] = [get_the_title(),"WebPage"];
        $data["inLanguage"] = [
                "@type" => "Language",
            "@id" => "schema:Language",
                "name" => $my_seo_settings_options["generate_json_ld_fpwebpage_inlanguage"] ?? null
            ];
        $data["datePublished"] = get_the_date('c');
        $data["dateModified"] = get_the_modified_date('c');
        $data["description"] = get_the_excerpt();
        $data["speakable"] = [
                '@type' => 'SpeakableSpecification',
                "@id" => "schema:SpeakableSpecification",
                'xpath' => [
                    "/html/head/title", "/html/head/meta[@name='description']/@content",
                ]
            ];
         $data["isPartOf"] = [
                "@type" => "WebSite",
                "@id" => "schema:WebSite",
                "url" => home_url(),
                "name" => $my_seo_settings_options["generate_json_ld_fpwebpage_name"] ?? null
                ];

        if (!Markapp_Schema_Matcher::isFrontPage($post)) {
            $mainEntity[] = array(
                "@type" => "BreadcrumbList",
                "name" => "BreadcrumbList",
                "@id" => "schema:BreadcrumbList",
                "numberOfItems" => count($breadcrumbs),
                "itemListElement" => $breadcrumbs
            );
            $data["breadcrumb"] = $mainEntity;                  
        }

        if (!Markapp_Schema_Matcher::isAuthor($post)) {         
            $authors[] = [
            "@type" => "Person",
            "@id" => "schema:Person",
            "email" => $my_seo_settings_author_options["generate_json_ld_author_email"] ?? null,
            "gender" => $my_seo_settings_author_options["generate_json_ld_author_gender"] ?? null,
            "familyName" => $my_seo_settings_author_options["generate_json_ld_author_family_name"] ?? null,
            "givenName" => $my_seo_settings_author_options["generate_json_ld_author_given_name"] ?? null,
            "jobTitle" => $my_seo_settings_author_options["generate_json_ld_author_job_title"] ?? null,
            "height" => $my_seo_settings_author_options["generate_json_ld_author_height"] ?? null,
            "knowsLanguage" => $my_seo_settings_author_options["generate_json_ld_author_knowslanguage"] ?? null,
            "memberOf" => $my_seo_settings_author_options["generate_json_ld_author_memberof"] ?? null,
            "nationality" => $my_seo_settings_author_options["generate_json_ld_author_nationality"] ?? null,
            "spouse" => $my_seo_settings_author_options["generate_json_ld_author_spouse"] ?? null,
            "telephone" => $my_seo_settings_author_options["generate_json_ld_author_telephone"] ?? null,
            "workLocation" =>  [
                "@type" => "Place",
                "name" => $my_seo_settings_author_options["generate_json_ld_author_place_name"] ?? null,
                "sameAs" => $my_seo_settings_author_options["generate_json_ld_author_place_sameas"] ?? null,
                "address" => array(
                    "@type" => "PostalAddress",
                    "streetAddress" => $my_seo_settings_author_options["generate_json_ld_author_work_address_street_address"] ?? null,
                    "addressLocality" => $my_seo_settings_author_options["generate_json_ld_author_work_address_address_locality"] ?? null,
                    "addressRegion" => $my_seo_settings_author_options["generate_json_ld_author_work_address_address_region"] ?? null,
                    "postalCode" => $my_seo_settings_author_options["generate_json_ld_author_work_address_postal_code"] ?? null,
                    "addressCountry" => $my_seo_settings_author_options["generate_json_ld_author_work_address_address_country"] ?? null
                )
            ],
            "worksFor" => [
            "@type" => "Organization",
            "@id" => "schema:Organization"],
            "description" => $my_seo_settings_author_options["generate_json_ld_author_description"] ?? null,
            "image" => array(
                "@type"  => "ImageObject",
                "url"    => $my_seo_settings_author_options["generate_json_ld_author_image"] ?? null
            ),
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => $my_seo_settings_author_options["generate_json_ld_author_location_street_address"] ?? null,
                "addressLocality" => $my_seo_settings_author_options["generate_json_ld_author_location_address_locality"] ?? null,
                "addressRegion" => $my_seo_settings_author_options["generate_json_ld_author_location_address_region"] ?? null,
                "postalCode" => $my_seo_settings_author_options["generate_json_ld_author_location_postal_code"] ?? null,
                "addressCountry" => $my_seo_settings_author_options["generate_json_ld_author_location_address_country"] ?? null
            ]];

            $data["author"] = $authors;
        }
        
        return $data;
        
        
        if (Markapp_Schema_Matcher::isPublisher($post)) {
            
            $publishers[] = array(
            "@type" => "Organization",
            "@id" => "schema:Organization",
            "url" => home_url(add_query_arg(null, null)),
            "name" => get_bloginfo('name'),
            "sameAs" => array(
                        $my_seo_settings_about_page_options['generate_json_ld_about_page_facebook'] ?? null,
                        $my_seo_settings_about_page_options['generate_json_ld_about_page_twitter'] ?? null,
                        $my_seo_settings_about_page_options['generate_json_ld_about_page_instagram'] ?? null,
                        $my_seo_settings_about_page_options['generate_json_ld_about_page_linkedin'] ?? null
                    ),
             "logo" => array(
                        "@type" => "ImageObject",
                        "url" => $my_seo_settings_article_options['generate_json_ld_logo_url'] ?? null,
                        "@id" => "schema:ImageObject",
                    ));
            $data["publisher"] = $publishers;
            
        };
    
    }
        
    public function settings()
    {
        $settings = GeneralSettings::instance();
        $settings->initDefaultSection();
        $settings->initSidebarSettingsPage(get_class($this));
        $settings->renderSidebarForm();

        $settings = AboutPageSettings::instance();
        $settings->initDefaultSection();
        $settings->initSidebarSettingsPage(get_class($this));
        $settings->renderSidebarForm();
        
        $settings = AuthorSettings::instance();
        $settings->initDefaultSection();
        $settings->initSidebarSettingsPage(get_class($this));
        $settings->renderSidebarForm();
        
        $settings = PublisherSettings::instance();
        $settings->initDefaultSection();
        $settings->initSidebarSettingsPage(get_class($this));
        $settings->renderSidebarForm();
    }
}
