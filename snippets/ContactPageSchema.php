<?php
class ContactPageSchema extends GenericSchema
{
    public function mustRenderOnPage()
    {
        global $post;

        return $post != null && Markapp_Schema_Matcher::isContactPage($post);
    }

    public function schema()
    {
        global $my_seo_settings_contact_page_options;
        global $my_seo_settings_local_business_options;

        $data = [];

        $data["@context"]      = "http://schema.org";
        $data["@type"] = "ContactPage";
        $data["@id"] = "schema:ContactPage";
		$data["name"] = "Contact Page";
		$data["isPartOf"] = [
			"@type" => "Webpage",
			"@id" => "schema:WebPage",
			"url" => "schema:WebPage"];
        


        return $data;
    }

    public function settings()
    {
        $settings = ContactPageSettings::instance();
        $settings->initDefaultSection();
        $settings->initSidebarSettingsPage(get_class($this));
        $settings->renderSidebarForm();
    }
}