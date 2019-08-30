package com.dinosoftlabs.foodies.android.Models;

/**
 * Created by Nabeel on 2/26/2018.
 */

public class CartFragParentModel {

    public String getItem_name() {
        return item_name;
    }

    public void setItem_name(String item_name) {
        this.item_name = item_name;
    }

    public String getItem_quantity() {
        return item_quantity;
    }

    public void setItem_quantity(String item_quantity) {
        this.item_quantity = item_quantity;
    }

    public String getItem_symbol() {
        return item_symbol;
    }

    public void setItem_symbol(String item_symbol) {
        this.item_symbol = item_symbol;
    }

    public String getItem_price() {
        return item_price;
    }

    public void setItem_price(String item_price) {
        this.item_price = item_price;
    }

    String item_name;
    String item_quantity;
    String item_symbol;
    String item_price;

    public String getItem_key() {
        return item_key;
    }

    public void setItem_key(String item_key) {
        this.item_key = item_key;
    }

    String item_key;
}
