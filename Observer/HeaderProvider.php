<?php

/**
 * Copyright 2023 Sanjeev Kumar
 * NOTICE OF LICENSE
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0

 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category  Sanjeev
 * @package   Sanjeev_HttpHeaders
 * @copyright Copyright (c) 2023
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Sanjeev\HttpHeaders\Observer;



class HeaderProvider implements \Magento\Framework\Event\ObserverInterface
{
    /**
     *
     * @var string
     */
    const XML_PATH_ENABLED = "web/httpheaders/active";
    
    /**
     *
     * @var string
     */
    protected $headerDir = 'headers';

    /**
     *
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $file;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * CustomHeader constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Filesystem\Io\File $file
        )
    {
        $this->scopeConfig = $scopeConfig;
        $this->file = $file;
    }

    /**
     * @inheritdoc
     */
    public function isEnabled()
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLED);
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if(!$this->isEnabled()){
            return false;
        }

        
        $dirPath = BP."/app/etc";
        
        if(!is_dir($dirPath))
        {
            return false;
        }

        $filename = $dirPath."/headers.json";
        if(!is_file($filename)){
            return false;
        }
        
        $event = $observer->getEvent();
        $response = $event->getResponse();

        $data = $this->file->read($filename);
        $headers = json_decode($data, true);
        if ($headers === null
            && json_last_error() !== JSON_ERROR_NONE) {
                return false;
        }

        if(is_array($headers))
        {
            $scp = null ;
            
            if(isset($headers["Content-Security-Policy"]) && !empty($headers["Content-Security-Policy"])

            && is_array($headers["Content-Security-Policy"]))
            {
                foreach($headers["Content-Security-Policy"] as $key => $val){
                    $scp .= $key." ".$val;
                }
            }

            if(isset($headers["Content-Security-Policy"]))
            {
                unset($headers["Content-Security-Policy"]);
            }

            if(!empty($scp))
            {
                $response->setHeader("Content-Security-Policy", $scp);
            }

            foreach($headers as $key => $val){
                $response->setHeader($key, $val);
            }
        }
    }
}
