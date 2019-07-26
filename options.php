<?
use Bitrix\Main\Localization\Loc;
use	Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

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
                "selector",
                Loc::getMessage("NEWMARK_LAZYLOAD_OPTIONS_TAB_SELECTOR").' - <b style="color:red;">В РАЗРАБОТКЕ</b>',
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

                if($arOption[0] == "switch_on_lazy" || $arOption[0] == "switch_on_cssinliner" || $arOption[0] == "include_jquery" || $arOption[0] == "animation"|| $arOption[0] == "inline_google_fonts" || $arOption[0] == "external_inline" || $arOption[0] == "minify_css"){

                    if($optionValue == ""){

                        $optionValue = "N";
                    }
                }

                Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
            }elseif($request["default"]){
                Option::set($module_id, $arOption[0], $arOption[2]);
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

        if($aTab["OPTIONS"]){

            $tabControl->BeginNextTab();

            __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
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
