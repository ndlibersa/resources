<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MyClient
 *
 * @author amandine
 */
class MyClient extends \Phpoaipmh\Client{
      public function decodeResponse($resp){
            return parent::decodeResponse($resp);
      }
}
