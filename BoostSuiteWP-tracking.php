<?php
   /*
   Plugin Name: BoostSuite Tracking
   Plugin URI: http://boostsuite.com
   Description: This plugin will insert the BoostSuite tracking code on all non-admin pages.
   Version: 1.7
   Author: BoostSuite
   Author URI: http://boostsuite.com
   */

  function boostsuitewp_tracking() {
    ?>

    <script type="text/javascript">
    /*
     * Boostsuite Tracker JS widget
     * CloudFront URL: http://d2so4705rl485y.cloudfront.net/widgets/tracker/tracker.js
     * @version 0.5
    */

    /* Measure Audience Characteristics for BoostSuite Article Exchange Comarketing Feature */

    (function(){
        /**
         * Boost
         *
         * Helper object to keep onload events and other necessary functions centralized
         **/
        Boost = function(){
            var onReadyEvents = [],
                baseUrl = null,
                isReady = false;

            /**
             * Boost.onDocumentReady(callback)
             * - callback (Function): The function to call once the document is ready
             *
             * Adds a function that will be called once the document is ready. If the document
             * is already loaded when this method is called, the callback will be called
             * immediately.
             **/
            this.onDocumentReady = function(callback) {
                if (isReady) {
                    callback();
                } else {
                    onReadyEvents.push(callback);
                }
            };

            /**
             * Boost.ready()
             *
             * Should be called once the document is ready for manipulation.
             **/
            this.ready = function() {
                if (isReady) {
                    return;
                }

                isReady = true;
                for (var x in onReadyEvents) {
                    try {
                        onReadyEvents[x]();
                    } catch (e) {
                        /* Silently fail */
                    }
                }
                onReadyEvents = [];
            };

            /**
             * Boost.getBaseUrl() -> String
             *
             * Get the base url of the boostsuite service.  No trailing slash included
             **/
            this.getBaseUrl = function() {
                return document.location.protocol + "//" + (typeof document.boostsuite !== "undefined" && document.boostsuite.domain ? document.boostsuite.domain : "image.poweredbyeden.com");
            };

            /**
             * Boost.replaceContent(content)
             * - content (Array|Object): A hash of "tag" and "text"
             *
             * Replaces the first instance of "tag" with the content in "text".  Safe to call before
             * the document is ready.
             **/
            this.replaceContent = function(content) {
                if (isReady) {
                    _replaceContent(content);
                } else {
                    this.onDocumentReady(function() {
                        _replaceContent(content);
                    });
                }
            };


            /** private
             * Boost._replaceContent(content)
             * - content (Array|Object): A hash of "tag" and "text"
             *
             * Helper method that actually tries to manipulate the DOM.
             **/
            var _replaceContent = function(content) {
                matches = document.getElementsByTagName(content['tag']);
                if (matches.length) {
                    for(var x=0; x < matches.length; x++) {
                        var currentContent = matches[x].textContent || matches[x].innerText;
                        if (!content['originalContent'] || currentContent == content['originalContent']) {
                            matches[x].innerHTML = content['text'];
                            return true;
                        }
                    }
                }
                return false;
            };
        };

        var boost = new Boost();
        window.boost = boost;
        document.boost = boost;

        if (document.readyState && (document.readyState == "complete" || document.readyState == "interactive")) {
            /* Here the script has been loaded after the DOM is already completely loaded, so we need to
               let boost know that everything is ready for execution */
            boost.ready();
        } else {
            /* Mozilla, Opera and webkit nightlies currently support this event */
            if ( document.addEventListener ) {
                /* Use the handy event callback */
                document.addEventListener( "DOMContentLoaded", function(){
                    document.removeEventListener( "DOMContentLoaded", arguments.callee, false );
                    boost.ready();
                }, false );

            /* If IE event model is used */
            } else if ( document.attachEvent ) {
                /* ensure firing before onload,
                   maybe late but safe also for iframes */
                document.attachEvent("onreadystatechange", function(){
                    if ( document.readyState === "complete" ) {
                        document.detachEvent( "onreadystatechange", arguments.callee );
                        boost.ready();
                    }
                });

                /* If IE and not an iframe
                  continually check to see if the document is ready */
                if ( document.documentElement.doScroll && window == window.top ) (function(){
                    try {
                        /* If IE is used, use the trick by Diego Perini
                           http://javascript.nwbox.com/IEContentLoaded/ */
                        document.documentElement.doScroll("left");
                    } catch( error ) {
                        setTimeout( arguments.callee, 0 );
                        return;
                    }

                    boost.ready();
                })();
            }
        }

        /* Get any heading tests for this page */
        boost.onDocumentReady(function(){
            if (typeof _bsc == "undefined") {
                 _bsc = {};
            }
            headingScript = document.createElement("script");
            headingScript.src = boost.getBaseUrl() + "/widget/headings/get?url="+escape(window.location.toString())+"&referrer="+escape(document.referrer) + (_bsc.suffix ? "&"+_bsc.suffix : "");
            document.getElementsByTagName("head")[0].appendChild(headingScript);

            /* Import Google Remarketing Pixel for BoostSuite Ads Comarketing Feature */

            googleScript = document.createElement("script");
            googleScript.src = 'https://www.googleadservices.com/pagead/conversion_async.js';
            document.getElementsByTagName("head")[0].appendChild(googleScript);

            var pollInterval = 1000;
            var pollCount = 0;
            var pollLimit = 10;
            var setConversionValues = function() {
              if (window.google_trackConversion) {
                window.google_trackConversion({
                  google_conversion_id: 933077001,
                  google_remarketing_only: true
                });
              } else {
                pollCount++;
                if (pollCount < pollLimit) {
                  window.setTimeout(setConversionValues, pollInterval);
                }
              }
            }
            window.setTimeout(setConversionValues, pollInterval);

        });
    })();

    </script>
    <?php
   }

   if (!is_admin()) {
    add_action('wp_head', 'boostsuitewp_tracking');
   }

?>
