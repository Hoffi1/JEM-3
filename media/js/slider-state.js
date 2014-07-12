/**
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * JavaScript behavior to allow selected collapse to be remained after save or page reload
 * keeping state in localstorage
 */

jQuery(function() {

    var loadcollapse = function() {
        var $ = jQuery.noConflict();

        jQuery(document).find('a[data-toggle="collapse"]').on('click', function(e) {
            // Store the selected collapse href in localstorage
            window.localStorage.setItem('collapse-href', $(e.target).attr('href'));
        });

        var activatecollapse = function(href) {
            var $el = $('a[data-toggle="collapse"]a[href*=' + href + ']');
            $el.collapse('show');
        };

        var hascollapse = function(href){
            return $('a[data-toggle="collapse"]a[href*=' + href + ']').length;
        };

        if (localStorage.getItem('collapse-href')) {
            // When moving from collapse area to a different view
            if(!hascollapse(localStorage.getItem('collapse-href'))){
                localStorage.removeItem('collapse-href');
                return true;
            }
            // Clean default collapse
            $('a[data-toggle="collapse"]').parent().removeClass('in');
            var collapsehref = localStorage.getItem('collapse-href');
            // Add active attribute for selected collapse indicated by url
            activatecollapse(collapsehref);
            // Check whether internal collapse is selected (in format <collapsename>-<id>)
            var seperatorIndex = collapsehref.indexOf('-');
            if (seperatorIndex !== -1) {
                var singular = collapsehref.substring(0, seperatorIndex);
                var plural = singular + "s";
                activatecollapse(plural);
            }
        }
    };
    setTimeout(loadcollapse, 100);

});
