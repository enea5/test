<?php


class ContactPageSettings extends GenericSettings
{
    protected $adminTitle = 'Contact Page Schema';
    protected $defaultSection = 'my_seo_settings_pluginPage_section';
    protected $name = 'contact_page';
    protected $settingsGroup = 'mySEOPluginContactPagePage';

    public function initSettingsPage()
    {
        $this->_addCheckboxField('generate_json_ld_contact_page', __( 'Contact Page Schema', 'my_seo_settings' ));
        $this->_addPagesDropdown('generate_json_ld_contact_page_related_page', __( 'Related page', 'my_seo_settings' ));
        $this->_addInputField('generate_json_ld_contact_page_copyright_year', __( 'Copyright Year', 'my_seo_settings' ));
        $this->_addInputField('generate_json_ld_contact_page_creator', __( 'Creator', 'my_seo_settings' ));
        $this->_addInputField('generate_json_ld_contact_page_date_published', __( 'Date Published', 'my_seo_settings' ));
        $this->_addInputField('generate_json_ld_contact_page_map', __( 'Map', 'my_seo_settings' ));
        $this->_addInputField('generate_json_ld_contact_page_name', __( 'Name', 'my_seo_settings' ));
    }

}