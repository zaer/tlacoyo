<?php

    class GetLog
    {
        private $browser;
        public function __construct()
        {
            $this->browser = new BrowserDetection();
        }

        public function getDatos()
        {
            $userAgent       = $this->browser->getUserAgent();               //string
            $browserName     = $this->browser->getName();                    //string
            $browserVer      = $this->browser->getVersion();                 //string
            $platformFamily  = $this->browser->getPlatform();                //string
            $platformVer     = $this->browser->getPlatformVersion(true);     //string
            $platformName    = $this->browser->getPlatformVersion();         //string
            $platformIs64bit = $this->browser->is64bitPlatform();            //boolean
            $isMobile        = $this->browser->isMobile();                   //boolean
            $isRobot         = $this->browser->isRobot();                    //boolean
            $isInIECompat    = $this->browser->isInIECompatibilityView();    //boolean
            $strEmulatedIE   = $this->browser->getIECompatibilityView();     //string
            $arrayEmulatedIE = $this->browser->getIECompatibilityView(true); //array('browser' => '', 'version' => '')
            $isChromeFrame   = $this->browser->isChromeFrame();              //boolean
            $isAol           = $this->browser->isAol();                      //boolean
            $aolVer          = $this->browser->getAolVersion();              //string

            //Test if the user uses Microsoft Edge
            if ($this->browser->getName() == BrowserDetection::BROWSER_EDGE)
            {
                return 'You are using Edge!';
            }

            //Test if the user uses specific versions of Internet Explorer
            if ($this->browser->getName() == BrowserDetection::BROWSER_IE)
            {
                //As you can see you can compare major and minor versions under a string format '#.#.#' (no limit in depth)
                if ($this->browser->compareVersions($this->browser->getVersion(), '11.0.0.0') < 0)
                {
                    return 'You are using IE < 11.';
                }
                if ($this->browser->compareVersions($this->browser->getVersion(), '11.0.0') == 0)
                {
                    return 'You are using IE 11.';
                }
                if ($this->browser->compareVersions($this->browser->getVersion(), '11.0') > 0)
                {
                    return 'You are using IE > 11.';
                }
                if ($this->browser->compareVersions($this->browser->getVersion(), '11') >= 0)
                {
                    return 'You are using IE 11 or greater.';
                }
            }
            //Test a new user agent and output the instance of BrowserDetection as a string
            #$this->browser->setUserAgent('Mozilla/5.0 (Windows NT 6.3; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0');
            return $this->browser;
        }
    }
?>
