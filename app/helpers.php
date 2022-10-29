<?php

function flash($message, $level = 'info')
{
    session()->flash('flash-message', $message);
    session()->flash('flash-message-level', $level);
}

