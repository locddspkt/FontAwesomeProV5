<?php

namespace FontAwesomeProV5;

/***
 * Class FontAwesomeProV5_Service
 * change: save cache icon for each session
 * icons link: https://fontawesome.com/v5.15/icons?d=gallery&p=2
 * @package FontAwesomeProV5
 */
class FontAwesomeProV5_Service {
    const LICENSE_MENTION = "<!-- For the best support, please buy the pro license at https://fontawesome.com/ -->";
    const PREVENT_LICENSE = '<circle cx="0" cy="0" r="0" fill="transparent"/> <!-- prevent license -->';


    /***
     * @var FontAwesomeProV5_Service
     */
    private static $instance;

    /***
     * @var array|false|mixed
     */
    private $options;
    private $defaultIconsFolder = false; //if not set, use the folder of this project

    /***
     * singleton, to prevent get instance by New
     */
    private function __construct($options = false)
    {
        $this->options = $options;
    }

    /***
     * @param $options bool|array
     * @return FontAwesomeProV5_Service
     */
    public static function GetInstance($options = false)
    {
        if (empty(self::$instance)) {
            self::$instance = new self($options);
            self::$instance->init();
        }

        return self::$instance;
    }

    /***
     * key = icon name
     * value = [
     *      path
     *      content = content of that path
     * ]
     *
     * more than one key can have the same path
     *
     * @var array
     */
    private $loadedIcons = array(); //use for old version of php

    /***
     * set the default folder and some options for the project
     */
    private function init() {
        $options = $this->options;
        if ($options === false) $options = array();
        if (isset($options['defaultFolder'])) {
            $this->defaultIconsFolder = $options['defaultFolder'];
        }
    }

    public function SetIconsFolder($folder) {
        $this->options['defaultFolder'] = $folder;
        $this->init();
    }

    /***
     * load the icon in the file (dir include)
     *  the icon can be included the extension (.svg) or not. The order to load =
     *      1. the exact file name
     *      2. file name with svg
     *      3. file name that is removed the svg
     * @param $iconName string can include .svg and can not. load
     * @param $externalClass string add class for svg
     * @return string content of the file
     */
    public function Load($iconName, $externalClass = false) {
        //try on session first
        //1. via key
        if (isset($this->loadedIcons[$iconName])) {
            return $this->applyClassForContent($this->loadedIcons[$iconName]['content'], $externalClass);
        }

        //2. via path

        //try with the icon name first
        if (file_exists($iconName)) {
            return $this->applyClassForContent($this->checkAndGetContentFromPath($iconName, $iconName), $externalClass);
        }

        $defaultFolder = $this->defaultIconsFolder;
        if ($defaultFolder === false) $defaultFolder = __DIR__ . '/icons/';

        $iconPathAndFileName = $defaultFolder . '/' . $iconName;
        //check existed first
        if (file_exists($iconPathAndFileName)) {
            return $this->applyClassForContent($this->checkAndGetContentFromPath($iconName, $iconPathAndFileName), $externalClass);
        }

        //then add svg if not exist
        $extension = pathinfo($iconPathAndFileName, PATHINFO_EXTENSION);
        if (strtolower($extension) != 'svg') {
            //try it
            $iconPathAndFileName .= '.svg';
            return $this->applyClassForContent($this->checkAndGetContentFromPath($iconName, $iconPathAndFileName), $externalClass);
        }
        else {
            //have svg but not have file --> try to remove it
            $pathinfo = pathinfo($iconPathAndFileName);
            $iconPathAndFileName = $pathinfo['dirname'] . '/' . $pathinfo['filename'];
            return $this->applyClassForContent($this->checkAndGetContentFromPath($iconName, $iconPathAndFileName), $externalClass);
        }

        //no one existed, return false;

        return false;
    }

    /***
     * get the content in the path, if not existed, get content then get (do not check path now)
     * @param $iconName
     * @param $path
     * @return string content of the path
     */
    private function checkAndGetContentFromPath($iconName, $path) {
        foreach ($this->loadedIcons as $key => $icon) {
            if ($icon['path'] == $path) {
                //check if the content has value or not, if yes, add it, otherwise, get content then add
                if (!empty($icon['content'])) return $icon['content'];

                //last time wasn't success
                if (file_exists($path)) {
                    $icon['content'] = $this->preventLicenseForContent(file_get_contents($path));
                }
                return $icon['content'];
            }
        }

        //do not have, add then return
        if (file_exists($path)) {
            $this->loadedIcons[$iconName] = array(
                'path' => $path,
                'content' => $this->preventLicenseForContent(file_get_contents($path))
            );
        }
        else {
            $this->loadedIcons[$iconName] = array(
                'path' => $path,
                'content' => false
            );
        }

        return $this->loadedIcons[$iconName]['content'];
    }

    private function preventLicenseForContent($content) {

        //replace the last </svg> add transparent circle

        //svg last
        $svgLastPos = strrpos(strtolower($content), '</svg>');
        if ($svgLastPos === false) {
            return $content . self::LICENSE_MENTION;
        }

        $content = substr($content, 0, $svgLastPos - 1);
        $content .= self::PREVENT_LICENSE;
        $content .= self::LICENSE_MENTION;
        $content .= '</svg>';
        //add the note to buy the pro license
        return $content;
    }


    private function applyClassForContent($content, $externalClass = false) {
        if ($externalClass === false) return $content;
        $currentClass = $this->getContentBetween2Patterns($content, 'class="', '"');

        $content = str_replace('class="' . $currentClass, 'class="' . $currentClass . ' ' . $externalClass, $content);
        return $content;
    }

    public function LoadPreCss() {
        $content = file_get_contents(__DIR__ . '/css/pre-load.css');
        $content = "\n<style>\n{$content}\n</style>\n";

        echo $content;
    }

    /***
     *
     * <begin something>content here<end something> --> return content here (index = 0)
     *
     * @param $content
     * @param $begin
     * @param $end
     * @param $index = false --> return array
     * @return string|array content between
     */
    private function getContentBetween2Patterns($content, $begin, $end, $index = 0)
    {
        $items = array();

        if ($begin === '') {
            $resultElements = explode($end, $content);
            $start = 0;
        } else {
            $separator = $begin;
            $resultElements = explode($separator, $content);
            $start = 1;
        }

        for ($i = $start; $i < count($resultElements); $i++) {
            $separator = $end;
            if ($end === '') {
                $items[] = $resultElements[$i];
            } else {
                $subItems = explode($separator, $resultElements[$i]);
                $subItem = $subItems[0];
                $items[] = $subItem;
            }
        }

        if ($index !== false) {
            if (isset($items[$index])) {
                return $items[$index];
            } else {
                return false;
            }
        } else {
            return $items;
        }
    }

}