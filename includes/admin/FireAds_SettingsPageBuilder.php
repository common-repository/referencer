<?php

defined('ABSPATH') or die('No access!');

class FireAds_SettingsPageBuilder
{
    private $fireads_postback_url;
    private $fireads_procent;

    public function initReferenceSettingsPage()
    {
        add_settings_section(
            'fireads-settings-section',
            '',
            array($this, 'addSettingsSectionCallback'),
            'fireads-page'
        );

        register_setting(
            'referencer_group',
            'fireads_postback_url',
            array($this, 'sanitize')
        );

        add_settings_field(
            'fireads_postback_url',
            'Postback URL',
            array($this, 'createPostbackUrlInput'),
            'fireads-page',
            'fireads-settings-section'
        );

        register_setting(
            'referencer_group',
            'fireads_procent',
            array($this, 'sanitize')
        );

        add_settings_field(
            'fireads_procent',
            'Lead %',
            array($this, 'createPercentInput'),
            'fireads-page',
            'fireads-settings-section'
        );
    }

    public function sanitize($input)
    {
        return sanitize_text_field($input);
    }

    public function addSettingsSectionCallback()
    {
        print 'Enter your settings below:';
    }

    public function createPostbackUrlInput()
    {
        printf(
            '
            <input id="fireads-postback-url" type="text" name="fireads_postback_url" value="%s" />',
            isset($this->fireads_postback_url) ? esc_attr($this->fireads_postback_url) : ''
        );
    }

    public function createPercentInput()
    {
        printf(
            '
            <input id="fireads-procent" type="text" name="fireads_procent" value="%s" />',
            isset($this->fireads_procent) ? esc_attr($this->fireads_procent) : ''
        );
    }

    public function createSettingsPage()
    {
        $this->fireads_postback_url = get_option('fireads_postback_url');
        $this->fireads_procent = get_option('fireads_procent');
        ?>

        <style>
            #fireads-postback-url {
                width: 100%;
            }

            #fireads-procent {
                width: 50px;
                text-align: center;
            }
        </style>
        <div class="wrap">
            <h1>
                Plugin settings
            </h1>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields('referencer_group');
                do_settings_sections('fireads-page');
                submit_button();
                ?>
            </form>
        </div>

        <?php
    }
}

