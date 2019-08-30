package com.dinosoftlabs.foodies.android.Models;

/**
 * Created by Nabeel on 2/16/2018.
 */

public class MenuExtraItemModelFirebase {
    public String getMenu_extra_item_id() {
        return menu_extra_item_id;
    }

    public void setMenu_extra_item_id(String menu_extra_item_id) {
        this.menu_extra_item_id = menu_extra_item_id;
    }

    public String getMenu_extra_item_name() {
        return menu_extra_item_name;
    }

    public void setMenu_extra_item_name(String menu_extra_item_name) {
        this.menu_extra_item_name = menu_extra_item_name;
    }

    public String getMenu_extra_item_price() {
        return menu_extra_item_price;
    }

    public void setMenu_extra_item_price(String menu_extra_item_price) {
        this.menu_extra_item_price = menu_extra_item_price;
    }

    public String getMenu_extra_item_quantity() {
        return menu_extra_item_quantity;
    }

    public void setMenu_extra_item_quantity(String menu_extra_item_quantity) {
        this.menu_extra_item_quantity = menu_extra_item_quantity;
    }

    public MenuExtraItemModelFirebase(String menu_extra_item_id,String menu_extra_item_name,String menu_extra_item_price,String menu_extra_item_quantity){
        this.menu_extra_item_id= menu_extra_item_id;
        this.menu_extra_item_name = menu_extra_item_name;
        this.menu_extra_item_price = menu_extra_item_price;
        this.menu_extra_item_quantity = menu_extra_item_quantity;
    }

    String menu_extra_item_id,menu_extra_item_name,menu_extra_item_price,menu_extra_item_quantity;
}
