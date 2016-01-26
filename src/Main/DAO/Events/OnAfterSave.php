<?php
/**
 * Self State Aware on after save interface
 * @author Aleksandr Babaev <babaev@adonweb.ru>
 * @date   2014.05.19
 */
namespace Hesper\Main\DAO\Events;

interface OnAfterSave {
    public function onAfterSave();
} 