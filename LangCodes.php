<?php
/**
 * @author: Cesar Hernandez
 * getLanguage: This method return the name of the language according to the language code
 */
class LangCodes{
    public function getLanguage($code){
        $all_lang=array(
        'fr_FR'=>_('French, FR'),
        'en_US'=>_('English, US'),
        'es_ES'=>_('Spanish, ES'),
        'es_MX'=>_('Spanish, MX'),
        'it_IT'=>_('Italian, IT')
        );
        return $all_lang[$code];
    }
}
?>