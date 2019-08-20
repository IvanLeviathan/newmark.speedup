<?
use Bitrix\Main\Localization\Loc;
use	Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Newmark\Speedup\ImageCompress;

Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()->getContext()->getRequest();

$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);

Loader::includeModule($module_id);


$aTabs = array(
    array(
        "DIV" 	  => "edit1",
        "TAB" 	  => Loc::getMessage("NEWMARK_LAZYLOAD_OPTIONS_TAB_NAME"),
        "TITLE"   => Loc::getMessage("NEWMARK_LAZYLOAD_OPTIONS_TAB_TITLE"),
        "OPTIONS" => array(
            Loc::getMessage("NEWMARK_LAZYLOAD_OPTIONS_TAB_COMMON"),
            array(
                "switch_on_lazy",
                Loc::getMessage("NEWMARK_LAZYLOAD_OPTIONS_TAB_SWITCH_ON"),
                "Y",
                array("checkbox")
            ),
            array(
                "include_jquery",
                Loc::getMessage("NEWMARK_LAZYLOAD_OPTIONS_TAB_INCLUDE_JQUERY"),
                "N",
                array("checkbox")
            ),
            Loc::getMessage("NEWMARK_LAZYLOAD_OPTIONS_TAB_ACTION"),
            array(
                "enable_desktop_lazy",
                Loc::getMessage("NEWMARK_SPEEDUP_OPTIONS_ENABLE"),
                "normal",
                array("selectbox", array(
                    "normal" => Loc::getMessage("NEWMARK_SPEEDUP_OPTIONS_ENABLE_NORMAL"),
                    "desktop"   => Loc::getMessage("NEWMARK_SPEEDUP_OPTIONS_ENABLE_DESKTOP"),
                    "mobile"   => Loc::getMessage("NEWMARK_SPEEDUP_OPTIONS_ENABLE_MOBILE")
                ))
            ),
            array(
                "selector",
                Loc::getMessage("NEWMARK_LAZYLOAD_OPTIONS_TAB_SELECTOR"),
                "",
                array("textarea", 5, 40)
            ),
            array(
                "exclude_lazy",
                Loc::getMessage("NEWMARK_LAZYLOAD_OPTIONS_TAB_EXCLUDE"),
                "",
                array("textarea", 10, 40)
            ),
            Loc::getMessage("NEWMARK_LAZYLOAD_OPTIONS_TAB_VIEW"),
            array(
                "animation",
                Loc::getMessage("NEWMARK_LAZYLOAD_OPTIONS_TAB_ANIMATION"),
                "Y",
                array("checkbox")
            ),
            Loc::getMessage("NEWMARK_LAZYLOAD_OPTIONS_BOTTOM_NOTE"),
        )
    ),
    array(
        "DIV" 	  => "edit2",
        "TAB" 	  => Loc::getMessage("NEWMARK_CSSINLINER_OPTIONS_TAB_NAME"),
        "TITLE"   => Loc::getMessage("NEWMARK_CSSINLINER_OPTIONS_TAB_TITLE"),
        "OPTIONS" => array(
            Loc::getMessage(!ini_get('allow_url_fopen') ? "NEWMARK_CSSINLINER_OPTIONS_NO_FOPEN" : ""),
            Loc::getMessage("NEWMARK_CSSINLINER_OPTIONS_TAB_COMMON"),
            array(
                "switch_on_cssinliner",
                Loc::getMessage("NEWMARK_CSSINLINER_OPTIONS_TAB_SWITCH_ON"),
                "Y",
                array("checkbox")
            ),
            Loc::getMessage("NEWMARK_CSSINLINER_OPTIONS_TAB_ACTION"),
            array(
                "enable_desktop_cssinliner",
                Loc::getMessage("NEWMARK_SPEEDUP_OPTIONS_ENABLE"),
                "normal",
                array("selectbox", array(
                    "normal" => Loc::getMessage("NEWMARK_SPEEDUP_OPTIONS_ENABLE_NORMAL"),
                    "desktop"   => Loc::getMessage("NEWMARK_SPEEDUP_OPTIONS_ENABLE_DESKTOP"),
                    "mobile"   => Loc::getMessage("NEWMARK_SPEEDUP_OPTIONS_ENABLE_MOBILE")
                ))
            ),
            array(
                "exclude_cssinliner",
                Loc::getMessage("NEWMARK_CSSINLINER_OPTIONS_TAB_EXCLUDE"),
                "",
                array("textarea", 10, 40)
            ),
            array(
                "max_file_size",
                Loc::getMessage("NEWMARK_CSSINLINER_OPTIONS_TAB_MAX_FILE_SIZE"),
                "512",
                array("text", 5)
            ),
            array(
                "cssinliner_cache_time",
                Loc::getMessage("NEWMARK_CSSINLINER_OPTIONS_TAB_CACHE_TIME"),
                "3600",
                array("text", 5)
            ),
            array(
                "external_inline",
                Loc::getMessage("NEWMARK_CSSINLINER_OPTIONS_TAB_EXTERNAL_INLINE"),
                "N",
                array("checkbox")
            ),
            array(
                "inline_google_fonts",
                Loc::getMessage("NEWMARK_CSSINLINER_OPTIONS_TAB_INLINE_GOOGLE_FONTS"),
                "N",
                array("checkbox")
            ),
            Loc::getMessage("NEWMARK_CSSINLINER_OPTIONS_TAB_VIEW"),
            array(
                "minify_css",
                Loc::getMessage("NEWMARK_CSSINLINER_OPTIONS_TAB_MINIFY"),
                "Y",
                array("checkbox")
            ),
            Loc::getMessage("NEWMARK_CSSINLINER_OPTIONS_BOTTOM_NOTE"),
        )
    ),
    array(
        "DIV" 	  => "edit3",
        "TAB" 	  => Loc::getMessage("NEWMARK_HTMLMINIFIER_OPTIONS_TAB_NAME"),
        "TITLE"   => Loc::getMessage("NEWMARK_HTMLMINIFIER_OPTIONS_TAB_TITLE"),
        "OPTIONS" => array(
            Loc::getMessage("NEWMARK_HTMLMINIFIER_OPTIONS_TAB_COMMON"),
            array(
                "switch_on_htmlminifier",
                Loc::getMessage("NEWMARK_HTMLMINIFIER_OPTIONS_TAB_SWITCH_ON"),
                "Y",
                array("checkbox")
            ),
            Loc::getMessage("NEWMARK_HTMLMINIFIER_OPTIONS_TAB_ACTION"),
            array(
                "enable_desktop_htmlminifier",
                Loc::getMessage("NEWMARK_SPEEDUP_OPTIONS_ENABLE"),
                "normal",
                array("selectbox", array(
                    "normal" => Loc::getMessage("NEWMARK_SPEEDUP_OPTIONS_ENABLE_NORMAL"),
                    "desktop"   => Loc::getMessage("NEWMARK_SPEEDUP_OPTIONS_ENABLE_DESKTOP"),
                    "mobile"   => Loc::getMessage("NEWMARK_SPEEDUP_OPTIONS_ENABLE_MOBILE")
                ))
            ),
            array(
                "exclude_htmlminifier",
                Loc::getMessage("NEWMARK_CSSINLINER_OPTIONS_TAB_EXCLUDE"),
                "",
                array("textarea", 10, 40)
            ),
        )
    ),
    array(
        "DIV" 	  => "image_compress",
        "TAB" 	  => Loc::getMessage("NEWMARK_IMGCOMPRESS_OPTIONS_TAB_NAME"),
        "TITLE"   => Loc::getMessage("NEWMARK_IMGCOMPRESS_OPTIONS_TAB_TITLE"),
    )
);

if($request->isPost() && check_bitrix_sessid()){

    foreach($aTabs as $aTab){

        foreach($aTab["OPTIONS"] as $arOption){

            if(!is_array($arOption)){

                continue;
            }

            if($arOption["note"]){

                continue;
            }

            if($request["apply"]){
                $optionValue = $request->getPost($arOption[0]);

                if(
                    $arOption[0] == "switch_on_lazy"
                    ||
                    $arOption[0] == "switch_on_cssinliner"
                    ||
                    $arOption[0] == "include_jquery"
                    ||
                    $arOption[0] == "animation"
                    ||
                    $arOption[0] == "inline_google_fonts"
                    ||
                    $arOption[0] == "external_inline"
                    ||
                    $arOption[0] == "minify_css"
                    ||
                    $arOption[0] == 'switch_on_htmlminifier'
                )
                {

                    if($optionValue == ""){

                        $optionValue = "N";
                    }
                }

                Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
            }elseif($request["default"]){
                Option::set($module_id, $arOption[0], $arOption[2]);
            }

            if($request['image_compress_start']){
                ImageCompress::compressAll();
            }
            if($request['image_compress_one']){
                ImageCompress::compressOne($request['image_compress_one']);
            }
            if($request['image_return_one']){
                ImageCompress::returnOne($request['image_return_one']);
            }
        }
    }

    LocalRedirect($APPLICATION->GetCurPage()."?mid=".$module_id."&lang=".LANG."&mid_menu=1");
}

$tabControl = new CAdminTabControl(
    "tabControl",
    $aTabs
);

$tabControl->Begin();

?>

<form action="<? echo($APPLICATION->GetCurPage()); ?>?mid=<? echo($module_id); ?>&lang=<? echo(LANG); ?>" method="post">

    <?
    foreach($aTabs as $aTab){
        if($aTab['DIV'] == 'image_compress'){?>
            <script src="/bitrix/js/<?=$module_id?>/newmark.jquery.min.js"></script>
            <script src="/bitrix/js/<?=$module_id?>/datatables.min.js"></script>
            <link href="/bitrix/css/<?=$module_id?>/datatables.min.css" rel="stylesheet"/>

            <?$tabControl->BeginNextTab();
            ImageCompress::draw();?>
                <script>
                    $(function(){

                        $table = $('#image_compress_edit_table');
                        $table.find("tbody").eq(0).remove();
                        $table.addClass('display cell-border');
                        $table.DataTable();

                    })
                </script>
                <style>
                    .dataTables_wrapper{
                        margin-top: 30px;
                    }
                </style>
        <?}else{
            if($aTab["OPTIONS"]){

                $tabControl->BeginNextTab();

                __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
            }
        }


    }

    $tabControl->Buttons();
    ?>

    <input type="submit" name="apply" value="<? echo(Loc::GetMessage("NEWMARK_SPEEDUP_OPTIONS_INPUT_APPLY")); ?>" class="adm-btn-save" />
    <input type="submit" name="default" value="<? echo(Loc::GetMessage("NEWMARK_SPEEDUP_OPTIONS_INPUT_DEFAULT")); ?>" />
    <div style="text-align:right;">
        <a href="https://nmark.ru/" target="_blank" style="display:inline-block;">
            <img src="/bitrix/images/<?=$module_id?>/nmlogo.png"/>
        </a>
    </div>

    <?
    echo(bitrix_sessid_post());
    ?>

</form>
<?$tabControl->End();?>
