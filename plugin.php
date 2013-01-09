<?php

add_filter('define_response_contexts', 'PDFOutputPlugin::defineResponseContexts');
add_filter('define_action_contexts', 'PDFOutputPlugin::defineActionContexts');

class PDFOutputPlugin {
    public function __construct() { }
    
    public static function defineResponseContexts($contexts) {
        $contexts['pdf'] = array('suffix' => 'pdf', 
                                 'headers' => array('Content-Type' => 'application/pdf'));
        return $contexts;
    }
    
    public static function defineActionContexts($contexts, $controller) {
        if ($controller instanceof ItemsController) {
            $contexts['show'][] = 'pdf';
        }
        return $contexts;
    }
    
}
