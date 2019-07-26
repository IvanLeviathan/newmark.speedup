<?

$MESS["NEWMARK_SPEEDUP_OPTIONS_INPUT_APPLY"]          = "Применить";
$MESS["NEWMARK_SPEEDUP_OPTIONS_INPUT_DEFAULT"]        = "По умолчанию";

//LazyLoad
$MESS["NEWMARK_LAZYLOAD_OPTIONS_TAB_NAME"] 		 	   = "LazyLoad";
$MESS["NEWMARK_LAZYLOAD_OPTIONS_TAB_TITLE"] 		 	   = "Настройки LazyLoad";
$MESS["NEWMARK_LAZYLOAD_OPTIONS_TAB_COMMON"] 	 	   = "Общие";
$MESS["NEWMARK_LAZYLOAD_OPTIONS_TAB_SWITCH_ON"]  	   = "Включить:";
$MESS["NEWMARK_LAZYLOAD_OPTIONS_TAB_ACTION"] 	   = "Поведение";
$MESS['NEWMARK_LAZYLOAD_OPTIONS_TAB_INCLUDE_JQUERY'] = "Подключить jQuery";
$MESS["NEWMARK_LAZYLOAD_OPTIONS_TAB_SELECTOR"]  	   = "К каким картинкам применять:<br/>(Укажите в формате css селекторов, через запятую)";
$MESS['NEWMARK_LAZYLOAD_OPTIONS_TAB_EXCLUDE'] = "Страницы исключения:<br/>(без доменного имени, каждый url в новой строке)";
$MESS["NEWMARK_LAZYLOAD_OPTIONS_TAB_VIEW"] = "Внешний вид";
$MESS['NEWMARK_LAZYLOAD_OPTIONS_TAB_ANIMATION'] = 'Анимация появления';

$MESS["NEWMARK_LAZYLOAD_OPTIONS_BOTTOM_NOTE"] = "
    <b style=\"color: #000; font-size:16px;\">Справка по настройкам</b>
<div style=\"color:#333; font-weight: normal; text-align: left;\">
<p><b>Подключить jQuery</b> - для работы модуля необходима библиотека jQuery, если у вас на сайте не подключена данная библиотека, то включите настройку.</p>
<p><b>К каким картинкам применять</b> - в этой настройке Вам нужно ввести css селекторы картинок, к которым нужно применить отложенную загрузку.<br>Например, если вы хотите применить отложенную загрузку ко всем элементам внутри блока с классом class=\"bx-content\" нужно указать такой селектор: <b>.bx-content img</b>. По умолчанию селектор  - <b>img</b></p>
<p><b>Страницы исключения</b> - Необязательная настройка. В этой настройке можно указать страницы, на которых Не нужно применять отложенную загрузку. Указывать страницы нужно без доменного имени и каждый url в новой строке, например - <b>/example/</b>
<br>Параметр поддерживает указание масок со *, к примеру указание в опции <b>/personal/*</b> отключит работу модуля на всех страницах, url которых начинается с /personal/</p>
</div>
";


//CSS INLINER
$MESS['NEWMARK_CSSINLINER_OPTIONS_NO_FOPEN'] = 'Отключен параметр PHP allow_url_fopen. Для работы модуля с внешними стилями и Google Fonts требуется установить параметр PHP allow_url_fopen = On. Без включенного параметра возможна некорректная работа модуля';
$MESS["NEWMARK_CSSINLINER_OPTIONS_TAB_NAME"] 		 	   = "CSS Inliner";
$MESS["NEWMARK_CSSINLINER_OPTIONS_TAB_TITLE"] 		 	   = "Настройки CSS Inliner";
$MESS["NEWMARK_CSSINLINER_OPTIONS_TAB_COMMON"] 	 	   = "Общие";
$MESS["NEWMARK_CSSINLINER_OPTIONS_TAB_SWITCH_ON"]  	   = "Включить:";
$MESS["NEWMARK_CSSINLINER_OPTIONS_TAB_ACTION"] 	   = "Поведение";
$MESS["NEWMARK_CSSINLINER_OPTIONS_TAB_VIEW"] = "Внешний вид";

$MESS["NEWMARK_CSSINLINER_OPTIONS_TAB_MAX_FILE_SIZE"]  	   = "Не подключать inline css для файлов весом более:<br/>(в Килобайтах, по умолчанию - 512)";
$MESS['NEWMARK_CSSINLINER_OPTIONS_TAB_INLINE_GOOGLE_FONTS'] = "Подключать Google Fonts инлайн";
$MESS['NEWMARK_CSSINLINER_OPTIONS_TAB_EXTERNAL_INLINE'] = "Подключать внешние стили инлайн";
$MESS['NEWMARK_CSSINLINER_OPTIONS_TAB_MINIFY'] = 'Минимизировать CSS';
$MESS['NEWMARK_CSSINLINER_OPTIONS_TAB_EXCLUDE'] = "Страницы исключения:<br/>(без доменного имени, каждый url в новой строке)";

$MESS["NEWMARK_CSSINLINER_OPTIONS_BOTTOM_NOTE"] = "
<div style=\"color:#333; font-weight: normal; text-align: left;\">
<b>Внимание:</b>
<br>1. Для удобства и безопасности замена в режиме администратора не производится.
<br>2. После первоначальной и других изменений настроек необходимо <a target=\"_blank\" href=\"/bitrix/admin/cache.php?lang=ru\">сбросить кеш</a> - вкладка \"Очистка файлов кеша\" - \"Все\" - Начать
<br><b>Страницы исключения</b> - Необязательная настройка. В этой настройке можно указать страницы, на которых Не нужно применять отложенную загрузку. Указывать страницы нужно без доменного имени и каждый url в новой строке, например - <b>/example/</b>
</div>
";


?>