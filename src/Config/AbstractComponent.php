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


use Skyline\Compiler\CompilerContext;
use TASoft\Config\Compiler\Factory\AbstractConfigFactory;
use TASoft\Config\Config;

abstract class AbstractComponent extends AbstractConfigFactory
{
    const COMP_ELEMENT_CLASS = 'class';
    const COMP_ELEMENT_ARGUMENTS = 'arguments';
    const COMP_REQUIREMENTS = "@require";

    protected function makeConfiguration(Config $configuration): Config
    {
        $cfg = new Config();
        $cfg[ static::COMP_ELEMENT_CLASS ] = $this->getComponentElementClassName();
        $args = $this->getComponentElementArguments($configuration);
        if($args)
            $cfg[ static::COMP_ELEMENT_ARGUMENTS ] = $args;
        return $cfg;
    }

    /**
     * Returns the arguments that should be passed to the element's constructor
     *
     * @param Config $config
     * @return array|null
     */
    protected function getComponentElementArguments(Config $config): ?array {
        return NULL;
    }

    /**
     * This method must return a valid class name for the component element (see package skyline/html-elements and Head elements)
     *
     * @return string
     */
    abstract protected function getComponentElementClassName(): string;

	/**
	 * Use this method during compilation to generate the right argument list for local components.
	 *
	 * @param string $link
	 * @param $filename
	 * @param string $integrity
	 * @param null $crossOrigin
	 * @param null $media
	 * @return string[]
	 */
	public static function makeLocalFileComponentArguments(string $link, $filename, string $integrity='sha384', $crossOrigin = NULL, $media = NULL) {
		$args = [$link];
		if($media)
			$args[] = $media;
		$args[] = sprintf("%s-%s", $integrity, base64_encode( hash_file($integrity, $filename, true) ));
		$args[] = $crossOrigin;
		$args[] = CompilerContext::getCurrentCompiler()->getRelativeProjectPath( $filename );
		return $args;
	}
}