<?php
class ArticleSchema extends GenericSchema
{
    public function mustRenderOnPage()
    {
        global $post;

        return $post !== null && Markapp_Schema_Matcher::isArticle($post); // TODO: Change the autogenerated stub
    }

    public function schema()
    {
        global $my_seo_settings_article_options;
        global $my_seo_settings_options;
        global $post;
        global $optype;
        global $artype;
		global $my_seo_settings_person_options;
		

        $optype = $my_seo_settings_options['generate_json_ld_fpwebpage_optype'] ?? null;
        $artype = $my_seo_settings_article_options['generate_json_ld_artype'] ?? null;

        $content = wp_strip_all_tags(apply_filters('the_content', get_the_content(null, null, $post)));

        $data = [];
        	$data["@context"] = "http://schema.org";
            $data["@type"] = $artype;
			$data["name"] = "Article";
            $data["@id"] = "schema:" . $artype;
            $data["additionalType"] = "Article";
            $data["headline"] = get_the_title();
            $data["description"] = get_the_excerpt(200);
            $data["articleBody"] = $content;
            $data["datePublished"] = get_the_date( 'c' );
            $data["dateModified"]  = get_the_modified_date( 'c' );
            $data["speakable"] = [
                "@type" => "SpeakableSpecification",
                "@id" => "schema:SpeakableSpecification",
                'xpath' => [
                    "/html/head/title",
                    "/html/head/meta[@name='description']/@content"
                ]
            ];
            $data["publisher"] = array(
                "@type" => "Organization",
                "@id" => "schema:Organization",
                "name"  => get_bloginfo( 'name' ),
            );
            $data["author"] = array(
                "@type" => "Person",
                "@id" => "schema:Person",
				"name" => $my_seo_settings_person_options['generate_json_ld_person_given_name'] ?? null
			);
		$data["isPartOf"] = array(
        "@type" => ["WebPage"],
        "@id" => "schema:WebPage"
      );
            $data["image"] = array(
                "@type"  => "ImageObject",
				"@id" => "schema:ImageObject",
                "url"    => get_the_post_thumbnail_url()
            );

        return $data;
    }

    public function settings()
    {
        $settings = ArticleSettings::instance();
        $settings->initDefaultSection();
        $settings->initSidebarSettingsPage(get_class($this));
        $settings->renderSidebarForm();
    }

}