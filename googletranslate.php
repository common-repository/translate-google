<?php
/*
Plugin Name: Translate - Google
Plugin URI: 
Description: This plugin is no longer maintained
Version: 1.1
Author: Sanauna
Author URI: 
      */

	   /*
        This program is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License, version 2, as
        published by the Free Software Foundation.

        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.

        You should have received a copy of the GNU General Public License
        along with this program; if not, write to the Free Software
        Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    */

add_action('widgets_init', array('googletranslate', 'register'));
add_action('admin_menu', array('googletranslate', 'admin_menu'));
add_action('init', array('googletranslate', 'enqueue_scripts'));
add_shortcode('googletranslate', array('googletranslate', 'get_widget_code'));
add_shortcode('googletranslate', array('googletranslate', 'get_widget_code'));



class googletranslate extends WP_Widget {

    function control() {
        $data = get_option('googletranslate');
        ?>
        <p><label>Title: <input name="googletranslate_title" type="text" class="widefat" value="<?php echo $data['googletranslate_title']; ?>"/></label></p>
        <p>Please go to Settings -> Google Translate for configuration.</p>
        <?php
        if (isset($_POST['googletranslate_title'])){
            $data['googletranslate_title'] = attribute_escape($_POST['googletranslate_title']);
            update_option('googletranslate', $data);
        }
    }

    function enqueue_scripts() {
        $data = get_option('googletranslate');
        googletranslate::load_defaults(& $data);
        $wp_plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );
        wp_enqueue_style('googletranslate-style', $wp_plugin_url.'/googletranslate-style'.$data['flag_size'].'.css');
    }

    function widget($args) {
        $data = get_option('googletranslate');
        googletranslate::load_defaults(& $data);

        echo $args['before_widget'];
        if(empty($data['widget_code']))
            echo 'You need to activate the plugin first, visit the WP-Admin -> Settings -> Google Translate and click the Save Changes button.';
        else
            echo $data['widget_code'];
        echo $args['after_widget'];
    }

    function get_widget_code($atts) {
        $data = get_option('googletranslate');
        googletranslate::load_defaults(& $data);

    }

    function register() {
        wp_register_sidebar_widget('googletranslate', 'Google Translate', array('googletranslate', 'widget'), array('description' => __('Google Automatic Translations')));
        wp_register_widget_control('googletranslate', 'Google Translate', array('googletranslate', 'control'));
    }

    function admin_menu() {
        add_options_page('googletranslate Options', 'Google Translate', 'administrator', 'googletranslate_options', array('googletranslate', 'options'));
    }
    function options() {
        ?>
        <div class="wrap">
        <div id="icon-options-general" class="icon32"><br/></div>
        <h2>googletranslate</h2>
        <?php
        if($_POST['save'])
            googletranslate::control_options();
        $data = get_option('googletranslate');
        googletranslate::load_defaults(& $data);

        $site_url = site_url();

        extract($data);

        #unset($data['widget_code']);
        #echo '<pre>', print_r($data, true), '</pre>';

$script = <<<EOT

var languages = ['Afrikaans','Albanian','Arabic','Armenian','Azerbaijani','Basque','Belarusian','Bulgarian','Catalan','Chinese (Simplified)','Chinese (Traditional)','Croatian','Czech','Danish','Dutch','English','Estonian','Filipino','Finnish','French','Galician','Georgian','German','Greek','Haitian Creole','Hebrew','Hindi','Hungarian','Icelandic','Indonesian','Irish','Italian','Japanese','Korean','Latvian','Lithuanian','Macedonian','Malay','Maltese','Norwegian','Persian','Polish','Portuguese','Romanian','Russian','Serbian','Slovak','Slovenian','Spanish','Swahili','Swedish','Thai','Turkish','Ukrainian','Urdu','Vietnamese','Welsh','Yiddish'];
var language_codes = ['af','sq','ar','hy','az','eu','be','bg','ca','zh-CN','zh-TW','hr','cs','da','nl','en','et','tl','fi','fr','gl','ka','de','el','ht','iw','hi','hu','is','id','ga','it','ja','ko','lv','lt','mk','ms','mt','no','fa','pl','pt','ro','ru','sr','sk','sl','es','sw','sv','th','tr','uk','ur','vi','cy','yi'];
var languages_map = {en_x: 0, en_y: 0, ar_x: 100, ar_y: 0, bg_x: 200, bg_y: 0, zhCN_x: 300, zhCN_y: 0, zhTW_x: 400, zhTW_y: 0, hr_x: 500, hr_y: 0, cs_x: 600, cs_y: 0, da_x: 700, da_y: 0, nl_x: 0, nl_y: 100, fi_x: 100, fi_y: 100, fr_x: 200, fr_y: 100, de_x: 300, de_y: 100, el_x: 400, el_y: 100, hi_x: 500, hi_y: 100, it_x: 600, it_y: 100, ja_x: 700, ja_y: 100, ko_x: 0, ko_y: 200, no_x: 100, no_y: 200, pl_x: 200, pl_y: 200, pt_x: 300, pt_y: 200, ro_x: 400, ro_y: 200, ru_x: 500, ru_y: 200, es_x: 600, es_y: 200, sv_x: 700, sv_y: 200, ca_x: 0, ca_y: 300, tl_x: 100, tl_y: 300, iw_x: 200, iw_y: 300, id_x: 300, id_y: 300, lv_x: 400, lv_y: 300, lt_x: 500, lt_y: 300, sr_x: 600, sr_y: 300, sk_x: 700, sk_y: 300, sl_x: 0, sl_y: 400, uk_x: 100, uk_y: 400, vi_x: 200, vi_y: 400, sq_x: 300, sq_y: 400, et_x: 400, et_y: 400, gl_x: 500, gl_y: 400, hu_x: 600, hu_y: 400, mt_x: 700, mt_y: 400, th_x: 0, th_y: 500, tr_x: 100, tr_y: 500, fa_x: 200, fa_y: 500, af_x: 300, af_y: 500, ms_x: 400, ms_y: 500, sw_x: 500, sw_y: 500, ga_x: 600, ga_y: 500, cy_x: 700, cy_y: 500, be_x: 0, be_y: 600, is_x: 100, is_y: 600, mk_x: 200, mk_y: 600, yi_x: 300, yi_y: 600, hy_x: 400, hy_y: 600, az_x: 500, az_y: 600, eu_x: 600, eu_y: 600, ka_x: 700, ka_y: 600, ht_x: 0, ht_y: 700, ur_x: 100, ur_y: 700};

function RefreshDoWidgetCode() {
    var new_line = "\\n";
    var widget_preview = '<!-- googletranslate: http://google.com -->'+new_line;
    var widget_code = '';
    var translation_method = jQuery('#translation_method').val();
    var default_language = jQuery('#default_language').val();
    var flag_size = jQuery('#flag_size').val();
    var pro_version = jQuery('#pro_version:checked').length > 0 ? true : false;
    var new_window = jQuery('#new_window:checked').length > 0 ? true : false;
    var analytics = jQuery('#analytics:checked').length > 0 ? true : false;

    if(translation_method == 'google_default') {
        included_languages = '';
        jQuery.each(languages, function(i, val) {
            lang = language_codes[i];
            if(jQuery('#incl_langs'+lang+':checked').length) {
                lang_name = val;
                included_languages += ','+lang;
            }
        });

        widget_preview += '<div id="google_translate_element"></div>'+new_line;
        widget_preview += '<script type="text/javascript">'+new_line;
        widget_preview += 'function googleTranslateElementInit() {new google.translate.TranslateElement({pageLanguage: \'';
        widget_preview += default_language;
        widget_preview += '\', includedLanguages: \'';
        widget_preview += included_languages;
        widget_preview += "'}, 'google_translate_element');}"+new_line;
        widget_preview += '<\/script>';
        widget_preview += '<script type="text/javascript" src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"><\/script>'+new_line;
    } else if(translation_method == 'on_fly' || translation_method == 'redirect') {
        // Adding flags
        if(jQuery('#show_flags:checked').length) {
            jQuery.each(languages, function(i, val) {
                lang = language_codes[i];
                if(jQuery('#fincl_langs'+lang+':checked').length) {
                    lang_name = val;
                    flag_x = languages_map[lang.replace('-', '')+'_x'];
                    flag_y = languages_map[lang.replace('-', '')+'_y'];
                    widget_preview += '<a href="javascript:dogoogletranslate(\''+default_language+'|'+lang+'\')" title="'+lang_name+'" class="gflag" style="background-position:-'+flag_x+'px -'+flag_y+'px;"><img src="{$site_url}/wp-content/plugins/googletranslate/blank.png" height="'+flag_size+'" width="'+flag_size+'" alt="'+lang_name+'" /></a>';
                }
            });
        }

        // Adding dropdown
        if(jQuery('#show_dropdown:checked').length) {
            if(jQuery('#show_flags:checked').length && jQuery('#add_new_line:checked').length)
                widget_preview += '<br />';
            else
                widget_preview += ' ';
            widget_preview += '<select onchange="dogoogletranslate(this);">';
            widget_preview += '<option value="">Select Language</option>';
            jQuery.each(languages, function(i, val) {
                lang = language_codes[i];
                if(jQuery('#incl_langs'+lang+':checked').length) {
                    lang_name = val;
                    widget_preview += '<option value="'+default_language+'|'+lang+'">'+lang_name+'</option>';
                }
            });
            widget_preview += '</select>';
        }

        // Adding javascript
        widget_code += new_line+new_line;
        widget_code += '<script type="text/javascript">'+new_line;
        widget_code += '//<![CDATA['+new_line;
        if(pro_version && translation_method == 'redirect' && new_window) {
            widget_code += "function openTab(url) {var form=document.createElement('form');form.method='post';form.action=url;form.target='_blank';document.body.appendChild(form);form.submit();}"+new_line;
            if(analytics)
                widget_code += "function dogoogletranslate(lang_pair) {if(lang_pair.value)lang_pair=lang_pair.value;if(lang_pair=='')return;var lang=lang_pair.split('|')[1];_gaq.push(['_trackEvent', 'googletranslate', lang, location.pathname+location.search]);var plang=location.pathname.split('/')[1];if(plang.length !=2 && plang != 'zh-CN' && plang != 'zh-TW')plang='"+default_language+"';if(lang == '"+default_language+"')openTab(location.protocol+'//'+location.host+location.pathname.replace('/'+plang+'/', '/')+location.search);else openTab(location.protocol+'//'+location.host+'/'+lang+location.pathname.replace('/'+plang+'/', '/')+location.search);}"+new_line;
            else
                widget_code += "function dogoogletranslate(lang_pair) {if(lang_pair.value)lang_pair=lang_pair.value;if(lang_pair=='')return;var lang=lang_pair.split('|')[1];var plang=location.pathname.split('/')[1];if(plang.length !=2 && plang != 'zh-CN' && plang != 'zh-TW')plang='"+default_language+"';if(lang == '"+default_language+"')openTab(location.protocol+'//'+location.host+location.pathname.replace('/'+plang+'/', '/')+location.search);else openTab(location.protocol+'//'+location.host+'/'+lang+location.pathname.replace('/'+plang+'/', '/')+location.search);}"+new_line;
        } else if(pro_version && translation_method == 'redirect') {
            if(analytics)
                widget_code += "function dogoogletranslate(lang_pair) {if(lang_pair.value)lang_pair=lang_pair.value;if(lang_pair=='')return;var lang=lang_pair.split('|')[1];_gaq.push(['_trackEvent', 'googletranslate', lang, location.pathname+location.search]);var plang=location.pathname.split('/')[1];if(plang.length !=2 && plang != 'zh-CN' && plang != 'zh-TW')plang='"+default_language+"';if(lang == '"+default_language+"')location.href=location.protocol+'//'+location.host+location.pathname.replace('/'+plang+'/', '/')+location.search;else location.href=location.protocol+'//'+location.host+'/'+lang+location.pathname.replace('/'+plang+'/', '/')+location.search;}"+new_line;
            else
                widget_code += "function dogoogletranslate(lang_pair) {if(lang_pair.value)lang_pair=lang_pair.value;if(lang_pair=='')return;var lang=lang_pair.split('|')[1];var plang=location.pathname.split('/')[1];if(plang.length !=2 && plang != 'zh-CN' && plang != 'zh-TW')plang='"+default_language+"';if(lang == '"+default_language+"')location.href=location.protocol+'//'+location.host+location.pathname.replace('/'+plang+'/', '/')+location.search;else location.href=location.protocol+'//'+location.host+'/'+lang+location.pathname.replace('/'+plang+'/', '/')+location.search;}"+new_line;
        } else if(translation_method == 'redirect' && new_window) {
            widget_code += 'if(top.location!=self.location)top.location=self.location;'+new_line;
            widget_code += "window['_tipoff']=function(){};window['_tipon']=function(a){};"+new_line;
            if(analytics)
                widget_code += "function dogoogletranslate(lang_pair) {if(lang_pair.value)lang_pair=lang_pair.value;if(lang_pair=='')return;if(location.hostname!='translate.googleusercontent.com' && lang_pair=='"+default_language+"|"+default_language+"')return;var lang=lang_pair.split('|')[1];_gaq.push(['_trackEvent', 'googletranslate', lang, location.pathname+location.search]);if(location.hostname=='translate.googleusercontent.com' && lang_pair=='"+default_language+"|"+default_language+"')openTab(unescape(gfg('u')));else if(location.hostname!='translate.googleusercontent.com' && lang_pair!='"+default_language+"|"+default_language+"')openTab('http://translate.google.com/translate?client=tmpg&hl=en&langpair='+lang_pair+'&u='+escape(location.href));else openTab('http://translate.google.com/translate?client=tmpg&hl=en&langpair='+lang_pair+'&u='+unescape(gfg('u')));}"+new_line;
            else
                widget_code += "function dogoogletranslate(lang_pair) {if(lang_pair.value)lang_pair=lang_pair.value;if(location.hostname!='translate.googleusercontent.com' && lang_pair=='"+default_language+"|"+default_language+"')return;else if(location.hostname=='translate.googleusercontent.com' && lang_pair=='"+default_language+"|"+default_language+"')openTab(unescape(gfg('u')));else if(location.hostname!='translate.googleusercontent.com' && lang_pair!='"+default_language+"|"+default_language+"')openTab('http://translate.google.com/translate?client=tmpg&hl=en&langpair='+lang_pair+'&u='+escape(location.href));else openTab('http://translate.google.com/translate?client=tmpg&hl=en&langpair='+lang_pair+'&u='+unescape(gfg('u')));}"+new_line;
            widget_code += 'function gfg(name) {name=name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");var regexS="[\\?&]"+name+"=([^&#]*)";var regex=new RegExp(regexS);var results=regex.exec(location.href);if(results==null)return "";return results[1];}'+new_line;
            widget_code += "function openTab(url) {var form=document.createElement('form');form.method='post';form.action=url;form.target='_blank';document.body.appendChild(form);form.submit();}"+new_line;
        } else if(translation_method == 'redirect') {
            widget_code += 'if(top.location!=self.location)top.location=self.location;'+new_line;
            widget_code += "window['_tipoff']=function(){};window['_tipon']=function(a){};"+new_line;
            if(analytics)
                widget_code += "function dogoogletranslate(lang_pair) {if(lang_pair.value)lang_pair=lang_pair.value;if(lang_pair=='')return;if(location.hostname!='translate.googleusercontent.com' && lang_pair=='"+default_language+"|"+default_language+"')return;var lang=lang_pair.split('|')[1];_gaq.push(['_trackEvent', 'googletranslate', lang, location.pathname+location.search]);if(location.hostname=='translate.googleusercontent.com' && lang_pair=='"+default_language+"|"+default_language+"')location.href=unescape(gfg('u'));else if(location.hostname!='translate.googleusercontent.com' && lang_pair!='"+default_language+"|"+default_language+"')location.href='http://translate.google.com/translate?client=tmpg&hl=en&langpair='+lang_pair+'&u='+escape(location.href);else location.href='http://translate.google.com/translate?client=tmpg&hl=en&langpair='+lang_pair+'&u='+unescape(gfg('u'));}"+new_line;
            else
                widget_code += "function dogoogletranslate(lang_pair) {if(lang_pair.value)lang_pair=lang_pair.value;if(location.hostname!='translate.googleusercontent.com' && lang_pair=='"+default_language+"|"+default_language+"')return;else if(location.hostname=='translate.googleusercontent.com' && lang_pair=='"+default_language+"|"+default_language+"')location.href=unescape(gfg('u'));else if(location.hostname!='translate.googleusercontent.com' && lang_pair!='"+default_language+"|"+default_language+"')location.href='http://translate.google.com/translate?client=tmpg&hl=en&langpair='+lang_pair+'&u='+escape(location.href);else location.href='http://translate.google.com/translate?client=tmpg&hl=en&langpair='+lang_pair+'&u='+unescape(gfg('u'));}"+new_line;
            widget_code += 'function gfg(name) {name=name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");var regexS="[\\?&]"+name+"=([^&#]*)";var regex=new RegExp(regexS);var results=regex.exec(location.href);if(results==null)return "";return results[1];}'+new_line;
        } else if(translation_method == 'on_fly') {
            widget_code += "if(jQuery.cookie('glang') && jQuery.cookie('glang') != '"+default_language+"') jQuery(function(\$){\$('body').translate('"+default_language+"', \$.cookie('glang'), {toggle:true, not:'.notranslate'});});"+new_line;
            if(analytics)
                widget_code += "function dogoogletranslate(lang_pair) {if(lang_pair.value)lang_pair=lang_pair.value;var lang=lang_pair.split('|')[1];_gaq.push(['_trackEvent', 'googletranslate', lang, location.pathname+location.search]);jQuery.cookie('glang', lang, {path: '/'});jQuery(function(\$){\$('body').translate('"+default_language+"', lang, {toggle:true, not:'.notranslate'});});}"+new_line;
            else
                widget_code += "function dogoogletranslate(lang_pair) {if(lang_pair.value)lang_pair=lang_pair.value;var lang=lang_pair.split('|')[1];jQuery.cookie('glang', lang, {path: '/'});jQuery(function(\$){\$('body').translate('"+default_language+"', lang, {toggle:true, not:'.notranslate'});});}"+new_line;
        }

        widget_code += '//]]>'+new_line;
        widget_code += '<\/script>'+new_line;

    }

    widget_code = widget_preview + widget_code;

    jQuery('#widget_code').val(widget_code);

    ShowWidgetPreview(widget_preview);

}

function ShowWidgetPreview(widget_preview) {
    widget_preview = widget_preview.replace(/javascript:dogoogletranslate/g, 'javascript:void')
    widget_preview = widget_preview.replace('onchange="dogoogletranslate(this);"', '');
    widget_preview = widget_preview.replace('if(jQuery.cookie', 'if(false && jQuery.cookie');
    jQuery('#widget_preview').html(widget_preview);
}

jQuery('#pro_version').attr('checked', '$pro_version'.length > 0);
jQuery('#new_window').attr('checked', '$new_window'.length > 0);
jQuery('#analytics').attr('checked', '$analytics'.length > 0);
jQuery('#load_jquery').attr('checked', '$load_jquery'.length > 0);
jQuery('#add_new_line').attr('checked', '$add_new_line'.length > 0);
jQuery('#show_dropdown').attr('checked', '$show_dropdown'.length > 0);
jQuery('#show_flags').attr('checked', '$show_flags'.length > 0);

jQuery('#default_language').val('$default_language');
jQuery('#translation_method').val('$translation_method');
jQuery('#flag_size').val('$flag_size');

if(jQuery('#widget_code').val() == '')
    RefreshDoWidgetCode();
else
    ShowWidgetPreview(jQuery('#widget_code').val());
EOT;

// selected languages
if(count($incl_langs) > 0)
    $script .= "jQuery.each(languages, function(i, val) {jQuery('#incl_langs'+language_codes[i]).attr('checked', false);});\n";
if(count($fincl_langs) > 0)
    $script .= "jQuery.each(languages, function(i, val) {jQuery('#fincl_langs'+language_codes[i]).attr('checked', false);});\n";
foreach($incl_langs as $lang)
    $script .= "jQuery('#incl_langs$lang').attr('checked', true);\n";
foreach($fincl_langs as $lang)
    $script .= "jQuery('#fincl_langs$lang').attr('checked', true);\n";
?>
        <form id="googletranslate" name="form1" method="post" action="<?php echo admin_url() . '/options-general.php?page=googletranslate_options' ?>">
        <div style="float:left;width:270px;">
            <h4>Google Translate options</h4>
            <table style="font-size:11px;">
            <tr>
                <td class="option_name">Method:</td>
                <td>
                    <select id="translation_method" name="translation_method" onChange="RefreshDoWidgetCode()">
                        <option value="google_default">Google Default</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="option_name">Default language:</td>
                <td>
                    <select id="default_language" name="default_language" onChange="RefreshDoWidgetCode()">
                        <option value="af">Afrikaans</option>
                        <option value="sq">Albanian</option>
                        <option value="ar">Arabic</option>
                        <option value="hy">Armenian</option>
                        <option value="az">Azerbaijani</option>
                        <option value="eu">Basque</option>
                        <option value="be">Belarusian</option>
                        <option value="bg">Bulgarian</option>
                        <option value="ca">Catalan</option>
                        <option value="zh-CN">Chinese (Simplified)</option>
                        <option value="zh-TW">Chinese (Traditional)</option>
                        <option value="hr">Croatian</option>
                        <option value="cs">Czech</option>
                        <option value="da">Danish</option>
                        <option value="nl">Dutch</option>
                        <option value="en" selected>English</option>
                        <option value="et">Estonian</option>
                        <option value="tl">Filipino</option>
                        <option value="fi">Finnish</option>
                        <option value="fr">French</option>
                        <option value="gl">Galician</option>
                        <option value="ka">Georgian</option>
                        <option value="de">German</option>
                        <option value="el">Greek</option>
                        <option value="ht">Haitian Creole</option>
                        <option value="iw">Hebrew</option>
                        <option value="hi">Hindi</option>
                        <option value="hu">Hungarian</option>
                        <option value="is">Icelandic</option>
                        <option value="id">Indonesian</option>
                        <option value="ga">Irish</option>
                        <option value="it">Italian</option>
                        <option value="ja">Japanese</option>
                        <option value="ko">Korean</option>
                        <option value="lv">Latvian</option>
                        <option value="lt">Lithuanian</option>
                        <option value="mk">Macedonian</option>
                        <option value="ms">Malay</option>
                        <option value="mt">Maltese</option>
                        <option value="no">Norwegian</option>
                        <option value="fa">Persian</option>
                        <option value="pl">Polish</option>
                        <option value="pt">Portuguese</option>
                        <option value="ro">Romanian</option>
                        <option value="ru">Russian</option>
                        <option value="sr">Serbian</option>
                        <option value="sk">Slovak</option>
                        <option value="sl">Slovenian</option>
                        <option value="es">Spanish</option>
                        <option value="sw">Swahili</option>
                        <option value="sv">Swedish</option>
                        <option value="th">Thai</option>
                        <option value="tr">Turkish</option>
                        <option value="uk">Ukrainian</option>
                        <option value="ur">Urdu</option>
                        <option value="vi">Vietnamese</option>
                        <option value="cy">Welsh</option>
                        <option value="yi">Yiddish</option>
                    </select>
                </td>
            </tr>
            </table>
        </div>

        <div style="float:left;width:232px;padding-left:50px;">
            <h4>Google Translate preview</h4>
            <div id="widget_preview"></div>
        </div>

        <div style="clear:both;"></div>
<br/>
<div align="left"><small class="black"><strong><font color="Red">Note:</font></strong> Visit -> <strong>Appearance</strong> -> <strong>Widgets</strong> and drag the Google Translate button in your<br/> sidebar or footer in order to display it on your website.</small></div>
<br/>
            <?php wp_nonce_field('googletranslate-save'); ?>
            <p class="submit"><input type="submit" class="button-primary" name="save" value="<?php _e('Save Changes'); ?>" /></p>
        <div style="margin-top:20px;">
<textarea id="widget_code" name="widget_code" style="visibility: hidden" onchange="ShowWidgetPreview(this.value)" style="font-family:Monospace;font-size:1px;height:0px;width:0px;"><?php echo $widget_code; ?></textarea>
        </div>
        </form>
        </div>
        <script type="text/javascript"><?php echo $script; ?></script>
        <?php
    }

    function control_options() {
        check_admin_referer('googletranslate-save');

        $data = get_option('googletranslate');

        $data['pro_version'] = isset($_POST['pro_version']) ? $_POST['pro_version'] : '';
        $data['new_window'] = isset($_POST['new_window']) ? $_POST['new_window'] : '';
        $data['analytics'] = isset($_POST['analytics']) ? $_POST['analytics'] : '';
        $data['load_jquery'] = isset($_POST['load_jquery']) ? $_POST['load_jquery'] : '';
        $data['add_new_line'] = isset($_POST['add_new_line']) ? $_POST['add_new_line'] : '';
        $data['show_dropdown'] = isset($_POST['show_dropdown']) ? $_POST['show_dropdown'] : '';
        $data['show_flags'] = isset($_POST['show_flags']) ? $_POST['show_flags'] : '';
        $data['default_language'] = $_POST['default_language'];
        $data['translation_method'] = $_POST['translation_method'];
        $data['flag_size'] = $_POST['flag_size'];
        $data['widget_code'] = stripslashes($_POST['widget_code']);
        $data['incl_langs'] = $_POST['incl_langs'];
        $data['fincl_langs'] = $_POST['fincl_langs'];

        echo '<p style="color:red;">Plug-in Activated.</p>';
        update_option('googletranslate', $data);
    }

    function load_defaults(& $data) {
        $data['pro_version'] = isset($data['pro_version']) ? $data['pro_version'] : '';
        $data['new_window'] = isset($data['new_window']) ? $data['new_window'] : '';
        $data['analytics'] = isset($data['analytics']) ? $data['analytics'] : '';
        $data['load_jquery'] = isset($data['load_jquery']) ? $data['load_jquery'] : '1';
        $data['add_new_line'] = isset($data['add_new_line']) ? $data['add_new_line'] : '1';
        $data['show_dropdown'] = isset($data['show_dropdown']) ? $data['show_dropdown'] : '1';
        $data['show_flags'] = isset($data['show_flags']) ? $data['show_flags'] : '1';
        $data['default_language'] = isset($data['default_language']) ? $data['default_language'] : 'en';
        $data['translation_method'] = isset($data['translation_method']) ? $data['translation_method'] : 'on_fly';
        $data['flag_size'] = isset($data['flag_size']) ? $data['flag_size'] : '16';
        $data['widget_code'] = isset($data['widget_code']) ? $data['widget_code'] : '';
        $data['incl_langs'] = isset($data['incl_langs']) ? $data['incl_langs'] : array();
        $data['fincl_langs'] = isset($data['fincl_langs']) ? $data['fincl_langs'] : array();
    }
}