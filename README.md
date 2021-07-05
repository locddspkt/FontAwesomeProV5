# Load SVG icons

Easy to load the SVG icons in the specific folder with only one command

## Getting Started

### Installing

Clone the source into the project

```
git clone https://github.com/locddspkt/FontAwesomeProV5.git
```

Include the class

```
include_once '/path/to/the/file/FontAwesomeProV5_Service.php';
```

Get the singleton instance and set the folder (if do not use the default folder)

```
$instance = FontAwesomeProV5\FontAwesomeProV5_Service::GetInstance(
    array(
        'defaultFolder' => __DIR__ . 'path/to/the/icon/folder/'
    )
);
```

Load the icon with this command

```
$instance->Load('icon-name');
```

## Running the tests

The test file is test/test.php

## Running the demo page

[webroot/demo.php](https://fontawesomeprov5.hptsoft.com/demo.php)

## Download icons

[From Youtube](https://youtu.be/MYsSlWRFpG8)


## License

This project is licensed under the MIT License
