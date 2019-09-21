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
use Skyline\HTMLRender\HTMLRenderController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use TASoft\Service\ServiceManager;
use TASoft\Util\Mime;

class DeliverResourcePlugin
{
    private $resourceDir;

    public function __construct($resourceDir)
    {
        $this->resourceDir = $resourceDir;
    }

    public function acceptContentType($type, $types): bool {
        foreach($types as $_) {
            if(fnmatch($_, $type))
                return true;
        }

        if(Mime::sharedMime()->getExtensionForMime( $type ) == 'php')
            return true;

        return false;
    }

    /**
     * Event listener
     *
     * Checks if a file exists and create response
     *
     * @param string $eventName
     * @param DeliverEvent $event
     * @param $eventManager
     * @param mixed ...$arguments
     */
    public function makeDeliverResponse(string $eventName, DeliverEvent $event, $eventManager, ...$arguments)
    {
        if($eventName == SKY_EVENT_DC_DELIVER) {
            $file = $event->getRequestedFile();
            $path = $this->getResourceDir() . DIRECTORY_SEPARATOR . $file;

            repeat:

            if(($p = realpath($path)) && ( ($response = $event->getResponse()) && $response->getStatusCode() < 300 || !$response )) {
                $ext = explode(".", $path);
                $ext = array_pop($ext);

                $type = Mime::sharedMime()->getMimeForExtension($ext);
                if (!$this->acceptContentType($type, $event->getRequest()->getAcceptableContentTypes())) {
                    $response = $event->getResponse();
                    if (!$response)
                        $event->setResponse($response = new Response());
                    $response->setStatusCode(404, "Resource Not Found");
                } else {
                    $event->setContentType($type);
                    $event->setRequestedFile($p);
                    $event->setResponse($response = new BinaryFileResponse($p));
                    $response->headers->set("Content-Type", $type);
                }
            } else {
                if(!isset($_repetition)) {
                    $_repetition = 1;

                    /** @var HTMLRenderController $rc */
                    $rc = ServiceManager::generalServiceManager()->get("renderController");
                    if ($rc instanceof HTMLRenderController) {
                        if($path = $rc->getMappedLocalFilename($file, true)) {
                            goto repeat;
                        }
                    }
                }

                $response = $event->getResponse();
                if(!$response)
                    $event->setResponse($response = new Response());
                $response->setStatusCode(404, "Resource Not Found");
            }
        }
    }

    /**
     * @return string
     */
    public function getResourceDir()
    {
        return $this->resourceDir;
    }
}