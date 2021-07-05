<?php
include_once __DIR__ . '/../src/FontAwesomeProV5_Service.php';

use FontAwesomeProV5\FontAwesomeProV5_Service;

$instance = FontAwesomeProV5_Service::GetInstance();
$instance->LoadPreCss();

$iconName = __DIR__ . '/test-icons/icon-name-test.svg';

$content = $instance->Load($iconName, 'class');
if ($content === false) {
    echo 'Error with icon file name = ' . $iconName;
}
else {
    echo "Icon content in file name " . $iconName . "\n";
    echo $content;
    echo "\n";
    echo "\n<br/>";
}

$content = $instance->Load($iconName = 'icon-name.svg');
if ($content === false) {
    echo 'Error with icon name = ' . $iconName;
}
else {
    echo "Icon content of icon name " . $iconName . "\n";
    echo $content;
    echo "\n";
    echo "\n<br/>";
}

$content = $instance->Load($iconName = 'icon-name');
if ($content === false) {
    echo 'Error with icon name = ' . $iconName;
}
else {
    echo "Icon content of icon name " . $iconName . "\n";
    echo $content;
    echo "\n";
    echo "\n<br/>";
}

$content = $instance->Load($iconName = 'icon-name-without');
if ($content === false) {
    echo 'Error with icon name = ' . $iconName;
}
else {
    echo "Icon content of icon name " . $iconName . "\n";
    echo $content;
    echo "\n";
    echo "\n<br/>";
}



$content = $instance->Load($iconName = 'icon-name-try-remove.svg');
if ($content === false) {
    echo 'Error with icon name = ' . $iconName;
}
else {
    echo "Icon content of icon name " . $iconName . "\n";
    echo $content;
    echo "\n";
    echo "\n<br/>";
}

$instance->SetIconsFolder(__DIR__ . '/test-icons/');
$content = $instance->Load($iconName = 'icon-name-test.svg');
if ($content === false) {
    echo 'Error with icon name = ' . $iconName;
}
else {
    echo "Icon content of icon name " . $iconName . "\n";
    echo $content;
    echo "\n";
    echo "\n<br/>";
}

?>
<style>
    .svg-inline {
        height: 30px;
    }
</style>
