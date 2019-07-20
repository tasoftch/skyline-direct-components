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

namespace Skyline\Component\Plugin;


use Skyline\Component\Event\DeliverEvent;
use Skyline\Kernel\Config\PluginConfig;
use Skyline\Kernel\Event\BootstrapEvent;
use Symfony\Component\HttpFoundation\Request;
use TASoft\EventManager\SectionEventManager;

class InitializeDeliveryPlugin
{
    private $resourceRoot = "/";

    public function __construct($resourceRoot = '/')
    {
        $this->resourceRoot = $resourceRoot;
    }

    public function initializeResourceDelivery(string $eventName, BootstrapEvent $event, SectionEventManager $eventManager, ...$arguments)
    {
        if($eventName == SKY_EVENT_BOOTSTRAP) {
            if(strcasecmp(substr($_SERVER["REQUEST_URI"], 0, strlen($this->getResourceRoot())), $this->getResourceRoot()) == 0) {
                $request = Request::createFromGlobals();
                $event = new DeliverEvent($request, $event->getConfiguration());

                $eventManager->triggerSection( PluginConfig::EVENT_SECTION_BOOTSTRAP, SKY_EVENT_DC_DELIVER, $event);
                $eventManager->trigger(SKY_EVENT_TEAR_DOWN);
                exit();
            }
        }
    }

    /**
     * @return string
     */
    public function getResourceRoot(): string
    {
        return $this->resourceRoot;
    }
}