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


use TASoft\Config\Compiler\Factory\AbstractConfigFactory;
use TASoft\Config\Config;

abstract class AbstractComponent extends AbstractConfigFactory
{
    const COMP_ELEMENT_CLASS = 'class';
    const COMP_ELEMENT_ARGUMENTS = 'arguments';

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
}