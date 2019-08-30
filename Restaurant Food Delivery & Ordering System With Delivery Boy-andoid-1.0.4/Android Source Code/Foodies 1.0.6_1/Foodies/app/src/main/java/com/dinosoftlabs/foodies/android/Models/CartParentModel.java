package com.dinosoftlabs.foodies.android.Models;

/**
 * Created by Nabeel on 1/22/2018.
 */

public class CartParentModel {

    public String getParentName() {
        return parentName;
    }

    public void setParentName(String parentName) {
        this.parentName = parentName;
    }

    String parentName;

    public String getRestaurant_id() {
        return restaurant_id;
    }

    public void setRestaurant_id(String restaurant_id) {
        this.restaurant_id = restaurant_id;
    }

    public String getRestaurant_menu_item_id() {
        return restaurant_menu_item_id;
    }

    public void setRestaurant_menu_item_id(String restaurant_menu_item_id) {
        this.restaurant_menu_item_id = restaurant_menu_item_id;
    }

    public String getRequired() {
        return required;
    }

    public void setRequired(String required) {
        this.required = required;
    }

    String restaurant_id;
    String restaurant_menu_item_id;
    String required;

    public String getSymbol() {
        return symbol;
    }

    public void setSymbol(String symbol) {
        this.symbol = symbol;
    }

    String symbol;


}
