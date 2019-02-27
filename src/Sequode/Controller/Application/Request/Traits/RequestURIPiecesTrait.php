<?php
namespace Sequode\Controller\Application\Request\Traits;

trait RequestURIPiecesTrait {

    public static function URIPieces(){

        $request_pieces = $_SERVER['REQUEST_URI'];
        $request_pieces = explode('?',$request_pieces)[0];
        $request_pieces = explode('#',$request_pieces)[0];
        $request_pieces = explode('/',$request_pieces);

        array_shift($request_pieces);
        return $request_pieces;
    }
}
