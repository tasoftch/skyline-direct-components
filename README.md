# Skyline Direct Components
This package allows your application to deliver components.  

#### Installation
`````bin
$ composer require skyline/direct-components
`````

#### Usage
With this package you get a directory `````Components````` in the SkylineAppData directory.

Files in this directory are linkable from your website under the following condition:
- Request URI MUST begin with ````/Public```` (changeable in configuration)

Please note that this package delivers the sources without any checks.  
To enable checks, see further packages available:

- 
