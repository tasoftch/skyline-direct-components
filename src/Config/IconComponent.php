<?php
/**
 * BSD 3-Clause License
 *
 * Copyright (c) 2019, TASoft Applications
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *  Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 *
 *  Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 *  Neither the name of the copyright holder nor the names of its
 *   contributors may be used to endorse or promote products derived from
 *   this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

namespace Skyline\Component\Config;

use Skyline\HTML\Head\RemoteSourceLink;
use TASoft\Config\Config;

class IconComponent extends AbstractLinkedComponent
{
    const ICON_TYPE_JPG = 'image/jpeg';
    const ICON_TYPE_MICROSOFT = 'image/vnd.microsoft.icon'; // Official mime type by IANA. Also allowed is image/x-icon
    const ICON_TYPE_PNG = 'image/png';

    public function __construct($link, string $contentType = NULL, string $integrity = NULL, string $crossOrigin = NULL, string $targetFileName = NULL)
    {
        parent::__construct($link, $integrity, $crossOrigin, $targetFileName);
        $this->getConfig()["ctt"] = $contentType;
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
            $config["ctt"] ?? $this->getMIMETypeForLink($config["l"]),
            $config["i"],
            $config["co"],
            'file' => $config["f"]
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