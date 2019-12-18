<?php

function limpiarInput($input, $type) { // Eg.: limpiarInput('This, is a test.', 'alphanumericspecial');

    if($type == 'numeric') { // ALLOW NUMBERS
    
        $output = intval($input);
    
    } else if($type == 'mayus') { // ALLOW MAYUS
     
        $output = preg_replace('([^A-Z])', '', $input);
    
    } else if($type == 'minus') { // ALLOW MINUS
    
        $output = preg_replace('([^a-z])', '', $input);
    
    } else if($type == 'alpha') { // ALLOW LETTERS (MAYUS AND MINUS)
    
        $output = preg_replace('([^A-Za-z])', '', $input);
    
    } else if($type == 'alphanumeric') { // ALLOW ALPHANUMERIC
    
        $output = preg_replace('([^A-Za-z0-9])', '', $input);
    
    } else if($type == 'alphanumericspecial') { // ALLOW ALPHANUMERIC AND SPECIAL CHARS: space, ¡!¿?,-_"*+()=.
    
        $output = preg_replace('([^A-Za-z0-9 ¡!¿?,-_"*+()=.])', '', $input);
    
    } else {
    
        $output = $input
    
    }

    return $output;

}
