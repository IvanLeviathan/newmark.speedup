<?
namespace Newmark\Speedup;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\IO\File;

/**
 * Class Main
 * @package Newmark\Speedup
 */
class Main{
    private static $allOptions;
    private static $preview;
    private static $userAgent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36';

    /**
     * @return mixed
     */
    private static function getModuleId()
    {
        return pathinfo(__DIR__)["basename"];
    }

    /**
     * @param $excludePages
     * @return bool
     */
    private static function checkPagePermission($excludePages){
        $curPage = $GLOBALS['APPLICATION']->GetCurPage();
        $pages  = preg_split("/\r\n|\n|\r/", $excludePages);

        foreach ($pages as $key => $page) {
            if(substr($page,-1) == "*"){
                $pageNoMask = substr($page, 0, -1);
                if($curPage != $pageNoMask && strpos($curPage, $pageNoMask) !== false)
                    return false;
            }

            if($curPage == $page)
                    return false;
        }

        return true;
    }
    /**
     * @param $module_id
     * @return array
     */
    private static function getOptions(){
        if(!empty(self::$allOptions))
            return self::$allOptions;
        $optionsArr = array(
            "switch_on_lazy" 	=> Option::get(self::getModuleId(), "switch_on_lazy", "Y"),
            "include_jquery"     	=> Option::get(self::getModuleId(), "include_jquery", "N"),
            "selector"    	=> Option::get(self::getModuleId(), "selector", ""),
            "exclude_lazy"    	=> Option::get(self::getModuleId(), "exclude_lazy", ""),
            "animation"     	=> Option::get(self::getModuleId(), "animation", "Y"),
            "switch_on_cssinliner" 	=> Option::get(self::getModuleId(), "switch_on_cssinliner", "Y"),
            "max_file_size" 	=> Option::get(self::getModuleId(), "max_file_size", "512"),
            "inline_google_fonts" 	=> Option::get(self::getModuleId(), "inline_google_fonts", "N"),
            "external_inline" 	=> Option::get(self::getModuleId(), "external_inline", "N"),
            "minify_css" 	=> Option::get(self::getModuleId(), "minify_css", "Y"),
            "exclude_cssinliner" 	=> Option::get(self::getModuleId(), "exclude_cssinliner", ""),
        );
        self::$allOptions = $optionsArr;
        return $optionsArr;
    }
    /**
     * @param $css
     * @return mixed
     */
    private static function minimizeCSS($css){
        $css = preg_replace('/\/\*((?!\*\/).)*\*\//','',$css); // negative look ahead
        $css = preg_replace('/\s{2,}/',' ',$css);
        $css = preg_replace('/\s*([:;{}])\s*/','$1',$css);
        $css = preg_replace('/;}/','}',$css);
        return $css;
    }
    /**
     * @param $url
     * @return array
     */
    private static function getExternalContent($url){

        $curlOptions = array(
            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_USERAGENT	   => self::$userAgent
        );

        $ch = curl_init($url);
        curl_setopt_array( $ch, $curlOptions);
        $content = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        return array(
            'content' => $content,
            'info' => $info
        );
    }
    /**
     * @param $bytes
     * @return float|int
     */
    private static function formatFileSize($bytes){
        return $bytes / 1024;
    }

    /**
     * @param $path
     * @param $maxFileSize
     * @param bool $external
     * @return bool|mixed|string
     */
    private static function checkFileSize($path, $maxFileSize, $external = false){
        if($external){
            $extFileContent = self::getExternalContent($path);

            if(!$extFileContent['content'] || self::formatFileSize($extFileContent['info']['size_download']) > $maxFileSize) //check external file size
                return false;

            return $extFileContent['content'];
        }else{
            if(!File::isFileExists($path)) //check local file exists
                return false;

            $file = new File($path);
            if(self::formatFileSize($file->getSize()) > $maxFileSize) //check local file size
                return false;

            // return content of local file
            return File::getFileContents($path);
        }
    }
    /**
     * @param $styleUrl
     * @param $options
     * @return bool|mixed|string
     */
    private static function getCssLikeString($styleUrl, $options){
        $maxFileSize = $options['max_file_size'] ? $options['max_file_size'] : 512;
        $inlineGoogle = $options['inline_google_fonts'] == 'Y';
        $externalInline = $options['external_inline'] == 'Y';

        if (strpos($styleUrl, 'http') === 0){
            if(!$externalInline || (strpos($styleUrl, 'fonts.googleapis') !== false && !$inlineGoogle))
                return false;
            if($css = self::checkFileSize($styleUrl, $maxFileSize, true))
                return $css;
        }else{
            $styleUrl = preg_replace('/\?\w+$/', '', $styleUrl);
            if($css = self::checkFileSize($_SERVER['DOCUMENT_ROOT'].$styleUrl, $maxFileSize))
                return $css;
        }

        return false;
    }
    /**
     * @return bool
     */
    public static function speedAddScripts(){
        $options = self::getOptions();

        if(defined("ADMIN_SECTION")
            || !self::checkPagePermission($options['exclude_lazy'])
        ) {
            return false;
        }

        if($options['switch_on_lazy'] == 'Y') {
            if ($options['include_jquery'] == 'Y')
                Asset::getInstance()->addJs("/bitrix/js/" . self::getModuleId() . "/newmark.lazyload.min.js");
            else
                Asset::getInstance()->addJs("/bitrix/js/" . self::getModuleId() . "/newmark.lazyload.nojq.min.js");

            Asset::getInstance()->addCss("/bitrix/css/" . self::getModuleId() . "/newmark.lazyload.min.css");

            Asset::getInstance()->addString(
                "<script id=\"newmark_lazyload-params\" data-params='" . json_encode(self::getOptions()) . "'></script>",
                true
            );
        }

        return false;
    }
    /**
     * @param string $content
     * @return bool
     */
    public static function speedActions(&$content = ''){
        if(!$content || defined("ADMIN_SECTION"))
            return false;

        $options = self::getOptions();

        //start lazy?
        if($options['switch_on_lazy'] == 'Y' && self::checkPagePermission($options['exclude_lazy']))
            self::lazyActions($content, $options);

        //start cssinliner?
        global $USER;
        if(!$USER->IsAdmin() && $options['switch_on_cssinliner'] == 'Y' && self::checkPagePermission($options['exclude_cssinliner']))
            self::cssinlinerActions($content, $options);


        return false;
    }

    /**
     * @param $content
     * @param $options
     */
    private static function lazyActions(&$content, $options){

        //vars
        self::$preview = '/bitrix/images/'.self::getModuleId().'/newmark_lazy_load.gif'; //make preview url
        $content = preg_replace_callback_array(
            array(
                "/<img[^>]+>/" => function($matches){
                    $img = $matches[0];
                    preg_match_all('/(\w+)=("[^"]*")/i',$img, $attrs);
                    $imgStr = '<img ';

                    foreach ($attrs[0] as $attr){
                        $attrArr = explode('=', $attr);

                        if($attrArr[0] == 'data-src')
                            continue;

                        if($attrArr[0] == 'src'){
                            $imgStr .= 'src="'.self::$preview.'" ';
                            $imgStr .= 'data-src='.$attrArr[1].' ';
                            continue;
                        }

                        if($attrArr[0] == 'srcset'){
                            $imgStr .= 'srcset="'.self::$preview.'" ';
                            $imgStr .= 'data-srcset='.$attrArr[1].' ';
                            continue;
                        }

                        $imgStr .= $attr.' ';

                    }

                    $imgStr .= '/>';

                    return $imgStr;
                }
            ),
            $content
        );
    }

    /**
     * @param $content
     * @param $options
     */
    private static function cssinlinerActions(&$content, $options){
        $content = preg_replace_callback_array(
            array(
                "/<link[^>]+>/" => function($matches){
                    $link = $matches[0];

                    if(strpos($link, 'rel="stylesheet"') === false) //if its not stylesheet
                        return $link;
                    preg_match_all('/(\w+)=("[^"]*")/i',$link, $attrs); //split attrs
                    $styleUrl = false;
                    foreach ($attrs[0] as $attr){ //find href
                        $attrArr = explode('=', $attr);
                        if($attrArr[0] == 'href'){
                            unset($attrArr[0]);
                            $styleUrl = str_replace('"', '', implode('=',$attrArr));
                            break;
                        }
                    }

                    if(!$styleUrl)
                        return $link;

                    $styleContent = self::getCssLikeString($styleUrl, self::getOptions());

                    if(!$styleContent)
                        return $link;

                    $options = self::getOptions();
                    if($options['minify_css'] == 'Y')
                        $styleContent = self::minimizeCSS($styleContent);


                    return '<style type="text/css">'.$styleContent.'</style>';
                }
            ),
            $content
        );
    }
}

?>