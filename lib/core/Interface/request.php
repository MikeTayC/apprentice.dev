<?php

/*
 * Request interface defining minimal contract of a request
 */
interface Core_Interface_Request
{
    /*
     * return true if the request is dispatched
     *
     * @return bool
     */
    public function isDispatched();

    /*
     * Dispatch the request again
     */
    public function dispatchAgain();

    /*
     * stop the request from being dispatched
     */
    public function stopDispatching();

    /*
     * get request parameter
     * returns a value of the request parameters struct
     */
    public function getParam();
}