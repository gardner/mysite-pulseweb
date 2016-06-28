<?php

global $project;
$project = 'mysite';

global $databaseConfig;
$databaseConfig = array(
	"type" => 'MySQLDatabase',
	"server" => 'localhost',
	"username" => 'ss',
	"password" => '1aClarence',
	"database" => 'ss',
	"path" => '',
);

// Set the site locale
i18n::set_locale('en_GB');

//$plugs = HtmlEditorConfig::get('cms')->getPlugins();
//$plugs[] = 'lists';
//HtmlEditorConfig::get('cms')->enablePlugins($plugs);

$eve = HtmlEditorConfig::get('cms')->getOption('extended_valid_elements');
//$eve .= ',@[id|class|title],div[id|class|title|form],svg[*]';
$eve .= ',@[*],div,*[*],ol[type]';
HtmlEditorConfig::get('cms')->setOption('extended_valid_elements', $eve);
HtmlEditorConfig::get('cms')->insertButtonsAfter('formatselect', 'forecolor');
HtmlEditorConfig::get('cms')->insertButtonsAfter('formatselect', 'fontsizeselect');
HtmlEditorConfig::get('cms')->setOption('style_formats' , array(
   // array('title' =>'Red Text','inline' => 'span', 'classes'=> 'red'),
   // array('title' =>'Yellow Text','inline' => 'span', 'classes'=> 'yellow'),
   // array('title' =>'Green Text','inline' => 'span', 'classes'=> 'green'),
   // array('title' =>'Blue Text','inline' => 'span', 'classes'=> 'blue'),
   // array('title' =>'White Text','inline' => 'span', 'classes'=> 'white'),
    array('title' =>'12 Columns','inline' => 'div', 'classes'=> 'large-12 columns'),
    array('title' =>'6 Columns','inline' => 'div', 'classes'=> 'large-6 columns'),
    array('title' =>'4 Columns','inline' => 'div', 'classes'=> 'large-4 columns'),
    array('title' =>'3 Columns','inline' => 'div', 'classes'=> 'large-3 columns'),
    array('title' =>'2 Columns','block' => 'div', 'classes'=> 'large-2 columns'),
    array('title' =>'Row','block' => 'div', 'classes'=> 'row'),
    array('title' =>'Hanging Indent','block' => 'ul', 'classes'=> 'hanging'),


));

HtmlEditorConfig::get('cms')->setOption('formats', array('bold'=> array('inline' => 'span', 'classes' => 'bold'),'bold'=> array('inline' => 'span', 'classes' => 'bold')));
//Image::set_backend('ImagickBackend');
//SS_Log::add_writer(new SS_LogFileWriter('/Library/WebServer/Documents/Silverstripe/mysite/errors.log'), SS_Log::ERR);
SS_Log::add_writer(new SS_LogFileWriter('/home/ss/public_html/mysite/errors.log'), SS_Log::ERR);
SS_Log::add_writer(new SS_LogEmailWriter('julian@bitstream.co.nz'), SS_Log::WARN, '<=');

//ini_set("log_errors", "On");
//ini_set("error_log", "/mysite/logfile");

//Email::send_all_emails_to('julian@bitstream.co.nz');
//Force enviroment to Dev ** REMOVE FOR LIVE SITES **

//
//print_r(TEMP_FOLDER);
SS_Cache::add_backend('two-level', 'TwoLevels', array(
        'slow_backend' => 'File',
        'fast_backend' => 'Apc',
        'slow_backend_options' => array(
                'cache_dir' => TEMP_FOLDER . DIRECTORY_SEPARATOR . 'cache'
)
));

// No need for special backend for aggregate - TwoLevels with a File slow
// backend supports tags
SS_Cache::pick_backend('two-level', 'Two-Levels', 10);

//Force cache to flush on page load if in Dev mode (prevents needing ?flush=1 on the end of a URL)
if (Director::isDev()) {
    SS_Cache::set_cache_lifetime('any', -1, 100);
}
else{

}

////SpamProtectorManager::set_spam_protector('SimpleSpamProtector');
//CommentingController::add_extension('CommentSpamProtection');
//if(class_exists('BlogEntry') && !Commenting::has_commenting('BlogEntry')) {
//        Commenting::add('BlsogEntry', array(
//            'require_moderation' => true,
//            'require_login' => false
//        ));
//}

//Email::set_mailer(new SmtpMailer());

ShortcodeParser::get('default')->register(
    'About', array('CustomSiteConfig', 'About')
);

