<?php

namespace App\Enums;

enum CartItemType: string
{
    case Menu = 'Menu';
    case MenuOption = 'MenuOption';

    public function tbl()
    {
        switch($this) {
            case static::Menu:
                return 'menus';
            case static::MenuOption:
                return 'menu_options';
        }
    }

    public function modelClass()
    {

        switch($this) {
            case static::Menu:
                return \App\Models\Menu::class;
            case static::MenuOption:
                return \App\Models\MenuOption::class;
        }

    }

}
