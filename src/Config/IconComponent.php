<?php
/**
 * Copyright (c) 2019 TASoft Applications, Th. Abplanalp <info@tasoft.ch>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Skyline\Component\Config;

use Skyline\HTML\Head\RemoteSourceLink;
use TASoft\Config\Config;

class IconComponent extends AbstractLinkedComponent
{
    const ICON_TYPE_JPG = 'image/jpeg';
    const ICON_TYPE_MICROSOFT = 'image/vnd.microsoft.icon'; // Official mime type by IANA. Also allowed is image/x-icon
    const ICON_TYPE_PNG = 'image/png';

    public function __construct($link, string $contentType = NULL, string $integrity = NULL, string $crossOrigin = NULL)
    {
        parent::__construct($link, $integrity, $crossOrigin);
    }

    protected function getComponentElementClassName(): string
    {
        return RemoteSourceLink::class;
    }

    protected function getComponentElementArguments(Config $config): ?array
    {
        return [
            $config["l"],
            RemoteSourceLink::REL_ICON . " " . RemoteSourceLink::REL_SHORTCUT,
            $this->getMIMETypeForLink($config["l"]),
            $config["i"],
            $config["co"],
        ];
    }

    protected function getMIMETypeForLink(string $link): ?string {
        if(preg_match("/\.(jpe?g|png|ico)$/i", $link, $ms)) {
            switch (strtolower($ms[1])) {
                case 'jpeg':
                case 'jpg':
                    return static::ICON_TYPE_JPG;
                case 'png':
                    return static::ICON_TYPE_PNG;
                case 'ico':
                case 'icon':
                    return static::ICON_TYPE_MICROSOFT;
                default:
            }
        }
        return NULL;
    }
}